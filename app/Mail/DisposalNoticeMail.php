<?php

namespace App\Mail;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class DisposalNoticeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /** @param Collection<int, \App\Models\Item> $items */
    public function __construct(
        public Carbon $handoverDate,
        public string $officeLocation,
        public Collection $items,
    ) {}

    public function build(): self
    {
        $mail = $this->subject('Disposal Notice: Unclaimed Lost & Found Items')
            ->view('emails.templates.comms_notice', [
                'handoverDate' => $this->handoverDate,
                'officeLocation' => $this->officeLocation,
                'itemCount' => $this->items->count(),
            ]);

        if ($this->items->isNotEmpty()) {
            $pdf = Pdf::loadView('emails.templates.manifest-pdf', [
                'items' => $this->items,
                'monthLabel' => $this->handoverDate->format('F Y'),
            ])->output();

            $mail->attachData($pdf, 'unclaimed-items-'.$this->handoverDate->format('F-Y').'.pdf', [
                'mime' => 'application/pdf',
            ]);
        }

        return $mail;
    }
}