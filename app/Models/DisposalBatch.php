<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisposalBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'cycle_label',
        'stage',
        'officer_id',
        'notice_sent_at',
        'handover_due_at',
        'released_at',
    ];

    protected $casts = [
        'notice_sent_at' => 'datetime',
        'handover_due_at' => 'datetime',
        'released_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function officer()
    {
        return $this->belongsTo(User::class, 'officer_id');
    }

    public function sentEmails()
    {
        return $this->hasMany(SentEmail::class);
    }

    public function isOpen(): bool
    {
        return $this->stage !== 'released';
    }

    public function stageLabel(): string
    {
        return match ($this->stage) {
            'review' => 'Reviewing batch',
            'notice_sent' => 'Notice sent to Communications',
            'pickup_window' => 'One-week pickup window',
            'released' => 'Released to Community Outreach',
            default => ucfirst($this->stage),
        };
    }

    public function secondsRemaining(): int
    {
        if (! $this->handover_due_at) {
            return 0;
        }

        return max(0, now()->diffInSeconds($this->handover_due_at, false));
    }
}
