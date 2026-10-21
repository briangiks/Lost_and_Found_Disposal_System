<?php

namespace App\Jobs;

use App\Mail\StudentNoticeMail;
use App\Models\DisposalBatch;
use App\Models\SentEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendStudentNoticeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public DisposalBatch $batch) {}

    public function handle(): void
    {
        $to = config('lostandfound.emails.students_list');
        $replyTo = config('lostandfound.emails.reply_to');
        $officeLocation = config('lostandfound.office_location');

        $mailable = new StudentNoticeMail($this->batch->handover_due_at, $officeLocation);

        if (! empty($replyTo)) {
            $mailable->replyTo($replyTo);
        }

        // A free host with no dedicated mail worker sends this live, inside
        // the web request — so a one-off slow/dropped connection to the mail
        // server shouldn't fail the whole thing. Try up to 3 times, 3
        // seconds apart, before actually giving up.
        retry(3, fn () => Mail::to($to)->send($mailable), 3000);

        SentEmail::create([
            'type' => 'student_notice',
            'from_address' => config('mail.from.address'),
            'to_address' => implode(', ', $to),
            'subject' => 'One week to collect your item from Lost & Found',
            'body_html' => $mailable->render(),
            'disposal_batch_id' => $this->batch->id,
            'sent_at' => now(),
        ]);
    }
}