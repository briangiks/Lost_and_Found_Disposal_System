<?php

namespace App\Jobs;

use App\Mail\DisposalNoticeMail;
use App\Models\DisposalBatch;
use App\Models\SentEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

/**
 * Sends the quarterly disposal notice to Strathmore Communications and
 * records it in `sent_emails` so the Email Preview page always has a copy,
 * whether or not real SMTP delivery is configured (MAIL_MAILER=log is fine
 * for a demo — see .env.example).
 */
class SendDisposalNoticeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public DisposalBatch $batch) {}

    public function handle(): void
    {
        $to = config('lostandfound.emails.communications');
        $cc = config('lostandfound.emails.office');
        $officeLocation = config('lostandfound.office_location');

        $mailable = new DisposalNoticeMail($this->batch->handover_due_at, $officeLocation);

        Mail::to($to)->cc($cc)->send($mailable);

        SentEmail::create([
            'type' => 'comms_notice',
            'from_address' => config('mail.from.address'),
            'to_address' => $to,
            'cc_address' => $cc,
            'subject' => 'Disposal Notice: Unclaimed Lost & Found Items',
            'body_html' => $mailable->render(),
            'disposal_batch_id' => $this->batch->id,
            'sent_at' => now(),
        ]);
    }
}
