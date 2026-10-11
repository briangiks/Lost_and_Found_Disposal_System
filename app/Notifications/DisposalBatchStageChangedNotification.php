<?php

namespace App\Notifications;

use App\Models\DisposalBatch;
use Illuminate\Notifications\Notification;

class DisposalBatchStageChangedNotification extends Notification
{
    public function __construct(
        public DisposalBatch $batch,
        public string $message,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'icon' => 'workflow',
            'title' => "Batch #{$this->batch->reference}: {$this->batch->stageLabel()}",
            'body' => $this->message,
            'url' => route('disposal.index'),
            'batch_id' => $this->batch->id,
        ];
    }
}
