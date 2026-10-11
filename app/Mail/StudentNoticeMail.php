<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class StudentNoticeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Carbon $handoverDate,
        public string $officeLocation,
    ) {}

    public function build(): self
    {
        return $this->subject('One week to collect your item from Lost & Found')
            ->view('emails.templates.student_notice', [
                'handoverDate' => $this->handoverDate,
                'officeLocation' => $this->officeLocation,
            ]);
    }
}
