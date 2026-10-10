<?php

namespace App\Http\Controllers;

use App\Models\DisposalBatch;
use App\Models\Item;
use App\Models\SentEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DisposalController extends Controller
{
    public function index(Request $request)
    {
        $batch = $this->currentBatch();

        $dueItems = Item::dueForDisposal()->orderByDesc('date_lost')->get();

        $pickupComplete = $batch->stage === 'pickup_window'
            && $batch->handover_due_at
            && now()->greaterThanOrEqualTo($batch->handover_due_at);

        // Step shown inside the "review" stage before anything has actually been sent.
        $composing = $request->boolean('compose') && $batch->stage === 'review';

        $collectedCount = $batch->stage === 'pickup_window' || $pickupComplete
            ? $batch->items()->where('status', 'claimed')->count()
            : 0;

        return view('disposal.index', [
            'batch' => $batch,
            'dueItems' => $dueItems,
            'totalToDispose' => Item::dueForDisposal()->count(),
            'composing' => $composing,
            'pickupComplete' => $pickupComplete,
            'collectedCount' => $collectedCount,
            'officeLocation' => config('lostandfound.office_location'),
            'commsEmail' => config('lostandfound.emails.communications'),
            'officeEmail' => config('lostandfound.emails.office'),
            'outreachEmail' => config('lostandfound.emails.outreach'),
        ]);
    }

    /** Stage 2 -> send the quarterly disposal notice to Strathmore Communications. */
    public function sendNotice(Request $request, DisposalBatch $batch)
    {
        if ($batch->stage !== 'review') {
            return redirect()->route('disposal.index');
        }

        $handoverAt = now()->addDays((int) config('lostandfound.pickup_window_days', 7));

        $batch->update([
            'stage' => 'pickup_window',
            'notice_sent_at' => now(),
            'handover_due_at' => $handoverAt,
            'officer_id' => Auth::id(),
        ]);

        // Snapshot every currently-due item into this batch.
        Item::dueForDisposal()->update(['disposal_batch_id' => $batch->id]);

        $body = view('emails.templates.comms_notice', [
            'handoverDate' => $handoverAt,
            'officeLocation' => config('lostandfound.office_location'),
        ])->render();

        SentEmail::create([
            'type' => 'comms_notice',
            'from_address' => config('lostandfound.emails.office'),
            'to_address' => config('lostandfound.emails.communications'),
            'cc_address' => config('lostandfound.emails.office'),
            'subject' => 'Disposal Notice: Unclaimed Lost & Found Items',
            'body_html' => $body,
            'disposal_batch_id' => $batch->id,
            'sent_at' => now(),
        ]);

        return redirect()
            ->route('disposal.index')
            ->with('status', 'Notice sent to Strathmore Communications. The 7-day pickup window has started.');
    }

    /** Demo-only: fast-forward the 7-day pickup window so the release step can be reached. */
    public function skipWait(DisposalBatch $batch)
    {
        if ($batch->stage !== 'pickup_window') {
            return redirect()->route('disposal.index');
        }

        $batch->update(['handover_due_at' => now()]);

        return redirect()->route('disposal.index');
    }

    /** Stage 4 -> release the remaining unclaimed items to the Community Outreach Club. */
    public function release(Request $request, DisposalBatch $batch)
    {
        if ($batch->stage !== 'pickup_window') {
            return redirect()->route('disposal.index');
        }

        $releasedCount = $batch->items()->where('status', 'active')->count();

        $batch->items()->where('status', 'active')->update(['status' => 'donated']);

        $batch->update([
            'stage' => 'released',
            'released_at' => now(),
        ]);

        $body = view('emails.templates.outreach_handover', [
            'officeLocation' => config('lostandfound.office_location'),
            'releasedCount' => $releasedCount,
        ])->render();

        SentEmail::create([
            'type' => 'outreach_handover',
            'from_address' => config('lostandfound.emails.office'),
            'to_address' => config('lostandfound.emails.outreach'),
            'subject' => 'Disposed items — handover for donation',
            'body_html' => $body,
            'disposal_batch_id' => $batch->id,
            'sent_at' => now(),
        ]);

        return redirect()
            ->route('disposal.index')
            ->with('status', "Batch {$batch->reference} archived. {$releasedCount} items handed over. Next quarterly cycle has started.");
    }

    /**
     * There's always exactly one "open" (not yet released) batch — find it, or start a new cycle.
     */
    private function currentBatch(): DisposalBatch
    {
        $batch = DisposalBatch::where('stage', '!=', 'released')->latest()->first();

        if ($batch) {
            return $batch;
        }

        return DisposalBatch::create([
            'reference' => $this->nextBatchReference(),
            'cycle_label' => now()->format('M').' – '.now()->addMonths(3)->format('M'),
            'stage' => 'review',
        ]);
    }

    private function nextBatchReference(): string
    {
        $count = DisposalBatch::count();

        return 'B-'.Str::padLeft((string) ($count + 15), 3, '0');
    }
}
