<?php

namespace App\Http\Controllers;

use App\Models\DisposalBatch;
use App\Models\Item;
use App\Services\DisposalWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DisposalController extends Controller
{
    public function __construct(private DisposalWorkflowService $workflow) {}

    public function index(Request $request)
    {
        $batch = $this->workflow->currentBatch();

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
            'commsEmail' => implode(', ', config('lostandfound.emails.communications')),
            'officeEmail' => implode(', ', config('lostandfound.emails.office')),
            'outreachEmail' => implode(', ', config('lostandfound.emails.outreach')),
            'automationEnabled' => config('lostandfound.automation_enabled', true),
        ]);
    }

    public function sendNotice(Request $request, DisposalBatch $batch)
    {
        $this->authorize('sendNotice', $batch);

        try {
            $this->workflow->sendNotice($batch, Auth::id());
        } catch (\Throwable $e) {
            Log::error('Disposal notice send failed.', ['batch_id' => $batch->id, 'error' => $e->getMessage()]);

            return redirect()
                ->route('disposal.index', ['compose' => 1])
                ->with('error', 'Could not send the notice email. Nothing was changed, so it is safe to try again.'.
                    "\n\nActual error from the mail server: ".$e->getMessage());
        }

        return redirect()
            ->route('disposal.index')
            ->with('status', 'Notice sent to Strathmore Communications and students. The 7-day pickup window has started.');
    }

    /** Demo-only: fast-forward the 7-day pickup window so the release step can be reached. */
    public function skipWait(DisposalBatch $batch)
    {
        $this->authorize('sendNotice', $batch);

        $this->workflow->skipWait($batch);

        return redirect()->route('disposal.index');
    }

    public function release(Request $request, DisposalBatch $batch)
    {
        $this->authorize('release', $batch);

        $releasedCount = $batch->items()->where('status', 'active')->count();

        try {
            $this->workflow->release($batch);
        } catch (\Throwable $e) {
            Log::error('Disposal batch release failed.', ['batch_id' => $batch->id, 'error' => $e->getMessage()]);

            return redirect()
                ->route('disposal.index')
                ->with('error', 'Could not send the handover email. Nothing was changed, so it is safe to try again.'.
                    "\n\nActual error from the mail server: ".$e->getMessage());
        }

        return redirect()
            ->route('disposal.index')
            ->with('status', "Batch {$batch->reference} archived. {$releasedCount} items handed over. Next quarterly cycle has started.");
    }
}
