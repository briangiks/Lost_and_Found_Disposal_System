<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OutreachHandoverMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $officeLocation,
        public int $releasedCount,
    ) {}

    public function build(): self
    {
        return $this->subject('Disposed items — handover for donation')
            ->view('emails.templates.outreach_handover', [
                'officeLocation' => $this->officeLocation,
                'releasedCount' => $this->releasedCount,
            ]);
    }
}
