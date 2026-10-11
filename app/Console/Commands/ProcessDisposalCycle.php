<?php

namespace App\Console\Commands;

use App\Services\DisposalWorkflowService;
use Illuminate\Console\Command;

/**
 * Run daily (see routes/console.php) so the quarterly disposal cycle keeps
 * moving even if no officer logs in: sends the Day-90 notice to Communications
 * and students, then releases the batch to Community Outreach once the 7-day
 * pickup window has elapsed. Safe to run as often as you like — every step is
 * idempotent (it only acts on a batch that's actually ready for that step).
 */
class ProcessDisposalCycle extends Command
{
    protected $signature = 'disposal:process-cycle';

    protected $description = "Advance the Lost & Found disposal workflow: send the Day-90 notice and release batches whose pickup window has elapsed.";

    public function handle(DisposalWorkflowService $workflow): int
    {
        $this->info('Processing the Lost & Found disposal cycle…');

        $log = $workflow->processAutomatedCycle();

        foreach ($log as $line) {
            $this->line('  • '.$line);
        }

        $this->info('Done.');

        return self::SUCCESS;
    }
}
