<?php

namespace App\Jobs;

use App\Mail\OutreachHandoverMail;
use App\Models\DisposalBatch;
use App\Models\SentEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOutreachHandoverJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @param Collection<int, \App\Models\Item> $releasedItems the items actually handed over in this batch */
    public function __construct(
        public DisposalBatch $batch,
        public Collection $releasedItems,
    ) {}

    public function handle(): void
    {
        $to = config('lostandfound.emails.outreach');
        $cc = config('lostandfound.emails.office');
        $replyTo = config('lostandfound.emails.reply_to');
        $officeLocation = config('lostandfound.office_location');

        $mailable = new OutreachHandoverMail($officeLocation, $this->releasedItems, $this->batch->reference);

        if (! empty($replyTo)) {
            $mailable->replyTo($replyTo);
        }

        retry(2, fn () => Mail::to($to)->cc($cc)->send($mailable), 1000);

        SentEmail::create([
            'type' => 'outreach_handover',
            'from_address' => config('mail.from.address'),
            'to_address' => implode(', ', $to),
            'cc_address' => implode(', ', $cc),
            'subject' => 'Disposed items — handover for donation',
            'body_html' => $mailable->render(),
            'disposal_batch_id' => $this->batch->id,
            'sent_at' => now(),
        ]);
    }
}