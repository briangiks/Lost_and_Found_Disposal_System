<?php

namespace App\Http\Controllers;

use App\Models\DisposalBatch;
use App\Models\SentEmail;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    private const TYPES = ['comms_notice', 'student_notice', 'outreach_handover'];

    public function index(Request $request)
    {
        $activeType = $request->get('type', 'comms_notice');
        if (! in_array($activeType, self::TYPES, true)) {
            $activeType = 'comms_notice';
        }

        $latestBatch = DisposalBatch::latest()->first();
        $handoverDate = $latestBatch?->handover_due_at ?? now()->addDays((int) config('lostandfound.pickup_window_days', 7));

        $sent = SentEmail::where('type', $activeType)->latest('sent_at')->first();

        if ($sent) {
            $preview = [
                'from' => $sent->from_address,
                'to' => $sent->to_address,
                'cc' => $sent->cc_address,
                'subject' => $sent->subject,
                'body' => $sent->body_html,
                'is_live' => true,
                'sent_at' => $sent->sent_at,
            ];
        } else {
            $preview = $this->draftFor($activeType, $handoverDate);
        }

        return view('emails.index', [
            'types' => [
                'comms_notice' => 'To Strathmore Comms',
                'student_notice' => 'To Students (drafted by Comms)',
                'outreach_handover' => 'To Community Outreach',
            ],
            'activeType' => $activeType,
            'preview' => $preview,
        ]);
    }

    /**
     * Live (not-yet-sent) draft of what each email will look like, built from
     * the same `lostandfound.emails.*` lists the real Jobs send to — so the
     * preview always matches reality, including when an address list has
     * more than one recipient.
     */
    private function draftFor(string $type, $handoverDate): array
    {
        $officeLocation = config('lostandfound.office_location');
        $fromAddress = config('mail.from.address');
        $office = implode(', ', config('lostandfound.emails.office'));

        return match ($type) {
            'comms_notice' => [
                'from' => $fromAddress,
                'to' => implode(', ', config('lostandfound.emails.communications')),
                'cc' => $office,
                'subject' => 'Disposal Notice: Unclaimed Lost & Found Items',
                'body' => view('emails.templates.comms_notice', compact('handoverDate', 'officeLocation'))->render(),
                'is_live' => false,
                'sent_at' => null,
            ],
            'student_notice' => [
                'from' => $fromAddress,
                'to' => implode(', ', config('lostandfound.emails.students_list')),
                'cc' => null,
                'subject' => 'One week to collect your item from Lost & Found',
                'body' => view('emails.templates.student_notice', compact('handoverDate', 'officeLocation'))->render(),
                'is_live' => false,
                'sent_at' => null,
            ],
            default => [
                'from' => $fromAddress,
                'to' => implode(', ', config('lostandfound.emails.outreach')),
                'cc' => $office,
                'subject' => 'Disposed items — handover for donation',
                'body' => view('emails.templates.outreach_handover', ['officeLocation' => $officeLocation, 'releasedCount' => null])->render(),
                'is_live' => false,
                'sent_at' => null,
            ],
        };
    }
}