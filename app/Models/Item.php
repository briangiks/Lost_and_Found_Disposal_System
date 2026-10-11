<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'name',
        'color',
        'category',
        'date_lost',
        'location',
        'markings',
        'image_path',
        'status',
        'reporter',
        'claimed_at',
        'disposal_batch_id',
    ];

    protected $casts = [
        'date_lost' => 'date',
        'claimed_at' => 'datetime',
    ];

    public function disposalBatch()
    {
        return $this->belongsTo(DisposalBatch::class);
    }

    /** Number of whole days since this item was logged as lost. */
    public function getAgeInDaysAttribute(): int
    {
        return (int) $this->date_lost->diffInDays(now());
    }

    /**
     * Age band used for the registry's status chip:
     * "fresh" (<30d), "within_quarter" (30-89d), "due" (90d+).
     */
    public function getAgeBandAttribute(): string
    {
        $days = $this->age_in_days;
        $disposalAge = (int) config('lostandfound.disposal_age_days', 90);

        if ($days >= $disposalAge) {
            return 'due';
        }

        if ($days >= 30) {
            return 'within_quarter';
        }

        return 'fresh';
    }

    /** Whether the item has reached the quarterly disposal cut-off. */
    public function getIsDueForDisposalAttribute(): bool
    {
        return $this->status === 'active'
            && $this->age_in_days >= (int) config('lostandfound.disposal_age_days', 90);
    }

    /** Whether the item is inside the "one week to disposal" early-warning band. */
    public function getIsApproachingDisposalAttribute(): bool
    {
        $days = $this->age_in_days;
        $early = (int) config('lostandfound.early_warning_days', 83);
        $cutoff = (int) config('lostandfound.disposal_age_days', 90);

        return $this->status === 'active' && $days >= $early && $days < $cutoff;
    }

    /**
     * Convenience wrapper around is_due_for_disposal — kept as an explicit
     * method (rather than only the accessor) because it's the natural unit
     * under test: an item becomes due the instant it reaches exactly
     * `disposal_age_days` (90 by default), not a day before or after.
     */
    public function isDue(): bool
    {
        return $this->is_due_for_disposal;
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeDueForDisposal(Builder $query): Builder
    {
        $cutoff = now()->subDays((int) config('lostandfound.disposal_age_days', 90))->toDateString();

        return $query->where('status', 'active')->whereDate('date_lost', '<=', $cutoff);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'active' => 'In Custody',
            'claimed' => 'Claimed by Owner',
            'donated' => 'Donated',
            default => ucfirst($this->status),
        };
    }

    public function imageUrl(): string
    {
        if ($this->image_path) {
            return str_starts_with($this->image_path, 'http')
                ? $this->image_path
                : asset('storage/'.$this->image_path);
        }

        return asset('images/placeholder-item.svg');
    }
}
