<?php

namespace App\Notifications;

use App\Models\Item;
use Illuminate\Notifications\Notification;

class ItemApproachingDisposalNotification extends Notification
{
    public function __construct(public Item $item) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'icon' => 'clock',
            'title' => "{$this->item->reference} is approaching disposal",
            'body' => "\"{$this->item->name}\" will reach the 90-day cut-off in ".
                max(0, 90 - $this->item->age_in_days).' day(s).',
            'url' => route('items.show', $this->item),
            'item_id' => $this->item->id,
        ];
    }
}
