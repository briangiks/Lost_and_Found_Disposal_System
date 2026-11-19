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

    /**
     * How many days an item may sit in custody before it's due for
     * disposal. Admin-editable on the Settings page (see SettingsController)
     * — falls back to LNF_DISPOSAL_AGE_DAYS / 90 until an admin has ever
     * saved an override.
     */
    public static function disposalAgeDays(): int
    {
        return Setting::getInt('disposal_age_days', (int) config('lostandfound.disposal_age_days', 90));
    }

        /**
     * Groups a collection of items the way the Community Outreach / Comms
     * handover manifest is laid out: by category, then by item name within
     * that category, counting how many of each — never listing individual
     * items one by one.
     *
     * @param  \Illuminate\Support\Collection|\Illuminate\Database\Eloquent\Collection  $items
     * @return array<string, array<string, int>> e.g. ['Electronics' => ['Mouse' => 20, 'Laptop Charger' => 12]]
     */
    public static function groupedManifest($items): array
    {
        $byCategory = $items->groupBy('category');

        $order = array_values(array_unique(array_merge(
            config('lostandfound.categories', []),
            $byCategory->keys()->all()
        )));

        $manifest = [];

        foreach ($order as $category) {
            if (! $byCategory->has($category)) {
                continue;
            }

            $manifest[$category] = $byCategory->get($category)
                ->groupBy('name')
                ->map(fn ($group) => $group->count())
                ->all();
        }

        return $manifest;
    }

    public static function earlyWarningDays(): int
    {
        return Setting::getInt('early_warning_days', (int) config('lostandfound.early_warning_days', 83));
    }

    /** Number of whole days since this item was logged as lost. */
    public function getAgeInDaysAttribute(): int
    {
        return (int) $this->date_lost->diffInDays(now());
    }

    /**
     * Age band used for the registry's status chip:
     * "fresh" (<30d), "within_quarter" (30d - cutoff), "due" (cutoff+).
     */
    public function getAgeBandAttribute(): string
    {
        $days = $this->age_in_days;

        if ($days >= static::disposalAgeDays()) {
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
        return $this->status === 'active' && $this->age_in_days >= static::disposalAgeDays();
    }

    /** Whether the item is inside the "one week to disposal" early-warning band. */
    public function getIsApproachingDisposalAttribute(): bool
    {
        $days = $this->age_in_days;
        $early = static::earlyWarningDays();
        $cutoff = static::disposalAgeDays();

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
        $cutoff = now()->subDays(static::disposalAgeDays())->toDateString();

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

        return asset('images/categories/'.$this->categoryIconSlug().'.svg');
    }

    private function categoryIconSlug(): string
    {
        $slugs = [
            'Electronics' => 'electronics',
            'Personal' => 'personal',
            'Clothing' => 'clothing',
            'Stationery' => 'stationery',
            'Accessories' => 'accessories',
            'Bags' => 'bags',
            'Documents' => 'documents',
        ];

        return $slugs[$this->category] ?? 'other';
    }
}