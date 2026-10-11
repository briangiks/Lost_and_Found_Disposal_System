<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class DisposalNoticeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Carbon $handoverDate,
        public string $officeLocation,
    ) {}

    public function build(): self
    {
        return $this->subject('Disposal Notice: Unclaimed Lost & Found Items')
            ->view('emails.templates.comms_notice', [
                'handoverDate' => $this->handoverDate,
                'officeLocation' => $this->officeLocation,
            ]);
    }
}
