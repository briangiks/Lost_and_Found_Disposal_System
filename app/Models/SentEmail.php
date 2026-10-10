<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SentEmail extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'from_address',
        'to_address',
        'cc_address',
        'subject',
        'body_html',
        'disposal_batch_id',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function disposalBatch()
    {
        return $this->belongsTo(DisposalBatch::class);
    }

    public function typeLabel(): string
    {
        return match ($this->type) {
            'comms_notice' => 'To Strathmore Comms',
            'student_notice' => 'To Students (drafted by Comms)',
            'outreach_handover' => 'To Community Outreach',
            default => ucfirst($this->type),
        };
    }
}
