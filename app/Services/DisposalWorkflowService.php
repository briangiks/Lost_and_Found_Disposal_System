<?php

namespace App\Services;

use App\Jobs\SendDisposalNoticeJob;
use App\Jobs\SendOutreachHandoverJob;
use App\Jobs\SendStudentNoticeJob;
use App\Models\DisposalBatch;
use App\Models\Item;
use App\Models\User;
use App\Notifications\DisposalBatchStageChangedNotification;
use App\Notifications\ItemApproachingDisposalNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

/**
 * All disposal-workflow state transitions live here, in one place, so that:
 *  - the manual "click through the stepper" admin UI (DisposalController), and
 *  - the fully automated Day-90 / Day-97 scheduler (ProcessDisposalCycle),
 * can never drift apart or duplicate logic.
 */
class DisposalWorkflowService
{
    /**
     * There's always exactly one "open" (not yet released) batch — find it, or
     * start a new quarterly cycle.
     */
    public function currentBatch(): DisposalBatch
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

    /**
     * Stage 2 -> notify Communications + students and open the 7-day pickup window.
     *
     * Wrapped in a DB transaction: on QUEUE_CONNECTION=sync (the default, and
     * the only option on hosts with no free queue worker, e.g. Render's free
     * tier), the dispatched mail jobs below actually run inline, during this
     * same request — so if the mail server hiccups and throws, we need the
     * batch/item changes above it to roll back too. Without this, a failed
     * send still leaves the batch stuck in "pickup_window" with the email
     * never having gone out, and the next click silently skips re-sending it
     * because the stage guard above no longer matches.
     *
     * @throws \Throwable re-thrown after rollback so the controller can show
     *                     a friendly error instead of a raw 500 page.
     */
    public function sendNotice(DisposalBatch $batch, ?int $officerId = null): DisposalBatch
    {
        if ($batch->stage !== 'review') {
            return $batch;
        }

        return DB::transaction(function () use ($batch, $officerId) {
            $handoverAt = now()->addDays((int) config('lostandfound.pickup_window_days', 7));

            $batch->update([
                'stage' => 'pickup_window',
                'notice_sent_at' => now(),
                'handover_due_at' => $handoverAt,
                'officer_id' => $officerId,
            ]);

            // Snapshot every currently-due item into this batch.
            Item::dueForDisposal()->update(['disposal_batch_id' => $batch->id]);

            try {
                SendDisposalNoticeJob::dispatch($batch);
                SendStudentNoticeJob::dispatch($batch);
            } catch (\Throwable $e) {
                Log::error('Failed to send disposal notice emails — rolling back batch stage.', [
                    'batch_id' => $batch->id,
                    'error' => $e->getMessage(),
                ]);

                throw $e; // rolls back the transaction; caller decides what the user sees
            }

            $this->notifyOfficers($batch, 'Disposal notice sent to Communications and students. The 7-day pickup window has started.');

            return $batch->fresh();
        });
    }

    /** Demo-only: fast-forward the 7-day pickup window. */
    public function skipWait(DisposalBatch $batch): DisposalBatch
    {
        if ($batch->stage !== 'pickup_window') {
            return $batch;
        }

        $batch->update(['handover_due_at' => now()]);

        return $batch->fresh();
    }

    /**
     * Stage 4 -> release remaining unclaimed items to Community Outreach and
     * open the next quarterly cycle. See sendNotice() above for why this is
     * wrapped in a transaction.
     *
     * @throws \Throwable
     */
    public function release(DisposalBatch $batch): DisposalBatch
    {
        if ($batch->stage !== 'pickup_window') {
            return $batch;
        }

        return DB::transaction(function () use ($batch) {
            $releasedCount = $batch->items()->where('status', 'active')->count();

            $batch->items()->where('status', 'active')->update(['status' => 'donated']);

            $batch->update([
                'stage' => 'released',
                'released_at' => now(),
            ]);

            try {
                SendOutreachHandoverJob::dispatch($batch, $releasedCount);
            } catch (\Throwable $e) {
                Log::error('Failed to send outreach handover email — rolling back batch release.', [
                    'batch_id' => $batch->id,
                    'error' => $e->getMessage(),
                ]);

                throw $e;
            }

            $this->notifyOfficers($batch, "Batch #{$batch->reference} archived — {$releasedCount} item(s) handed over to Community Outreach.");

            return $batch->fresh();
        });
    }

    /**
     * Entry point for the scheduler (php artisan disposal:process-cycle):
     * advances the current batch through its stages automatically, with no
     * officer interaction required. Returns a short log of what it did.
     */
    public function processAutomatedCycle(): array
    {
        $log = [];

        if (! config('lostandfound.automation_enabled', true)) {
            return ['Automation is disabled (LNF_AUTOMATION_ENABLED=false). Skipped.'];
        }

        $batch = $this->currentBatch();

        if ($batch->stage === 'review' && Item::dueForDisposal()->exists()) {
            $this->sendNotice($batch);
            $log[] = "Batch #{$batch->reference}: notice sent to Communications and students, 7-day pickup window opened.";
        }

        $batch->refresh();

        if ($batch->stage === 'pickup_window'
            && $batch->handover_due_at
            && now()->greaterThanOrEqualTo($batch->handover_due_at)
        ) {
            $this->release($batch);
            $log[] = "Batch #{$batch->reference}: pickup window elapsed, items released to Community Outreach.";
        }

        $this->raiseApproachingDisposalNotifications();

        return $log ?: ['Nothing to do — no items are due and no pickup window has elapsed.'];
    }

    /**
     * Raise one notification per item that has just entered the "approaching
     * disposal" window (and doesn't already have one), so officers aren't
     * spammed every time the scheduler runs.
     */
    public function raiseApproachingDisposalNotifications(): int
    {
        $officers = User::all();
        if ($officers->isEmpty()) {
            return 0;
        }

        $count = 0;

        Item::active()->get()->each(function (Item $item) use ($officers, &$count) {
            if (! $item->is_approaching_disposal) {
                return;
            }

            $alreadyNotified = $officers->first()
                ->notifications()
                ->where('type', ItemApproachingDisposalNotification::class)
                ->whereJsonContains('data->item_id', $item->id)
                ->exists();

            if ($alreadyNotified) {
                return;
            }

            Notification::send($officers, new ItemApproachingDisposalNotification($item));
            $count++;
        });

        return $count;
    }

    private function notifyOfficers(DisposalBatch $batch, string $message): void
    {
        $officers = User::all();
        if ($officers->isEmpty()) {
            return;
        }

        Notification::send($officers, new DisposalBatchStageChangedNotification($batch, $message));
    }

    private function nextBatchReference(): string
    {
        $count = DisposalBatch::count();

        return 'B-'.Str::padLeft((string) ($count + 15), 3, '0');
    }
}
