<?php

namespace App\Mail;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OutreachHandoverMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /** @param Collection<int, \App\Models\Item> $releasedItems */
    public function __construct(
        public string $officeLocation,
        public Collection $releasedItems,
        public string $batchReference,
    ) {}

    public function build(): self
    {
        $mail = $this->subject("Disposed items — handover for donation (Batch #{$this->batchReference})")
            ->view('emails.templates.outreach_handover', [
                'officeLocation' => $this->officeLocation,
                'releasedCount' => $this->releasedItems->count(),
                'batchReference' => $this->batchReference,
            ]);

        if ($this->releasedItems->isNotEmpty()) {
            $pdf = Pdf::loadView('emails.templates.manifest-pdf', [
                'items' => $this->releasedItems,
                'monthLabel' => now()->format('F Y'),
            ])->output();

            $mail->attachData($pdf, 'disposed-items-batch-'.$this->batchReference.'.pdf', [
                'mime' => 'application/pdf',
            ]);
        }

        return $mail;
    }
}