<?php

namespace Tests\Feature;

use App\Models\DisposalBatch;
use App\Models\Item;
use App\Models\User;
use App\Notifications\DisposalBatchStageChangedNotification;
use App\Notifications\ItemApproachingDisposalNotification;
use App\Services\DisposalWorkflowService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class DisposalAutomationTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_scheduler_command_sends_the_notice_for_a_due_item_with_no_officer_interaction(): void
    {
        Mail::fake();
        Notification::fake();
        User::factory()->create(['email' => 'admin@strathmore.edu']);

        Item::factory()->create(['date_lost' => now()->subDays(95)]);

        $this->artisan('disposal:process-cycle')->assertSuccessful();

        $batch = DisposalBatch::firstOrFail();
        $this->assertSame('pickup_window', $batch->stage);
        $this->assertDatabaseHas('sent_emails', ['type' => 'comms_notice']);
        $this->assertDatabaseHas('sent_emails', ['type' => 'student_notice']);
    }

    public function test_the_scheduler_releases_a_batch_whose_pickup_window_has_elapsed(): void
    {
        Mail::fake();
        Notification::fake();
        User::factory()->create();

        $item = Item::factory()->create(['date_lost' => now()->subDays(95)]);

        $workflow = app(DisposalWorkflowService::class);
        $batch = $workflow->sendNotice($workflow->currentBatch());
        $batch->update(['handover_due_at' => now()->subMinute()]); // simulate the window having elapsed

        $this->artisan('disposal:process-cycle')->assertSuccessful();

        $batch->refresh();
        $item->refresh();
        $this->assertSame('released', $batch->stage);
        $this->assertSame('donated', $item->status);
    }

    public function test_automation_can_be_disabled_via_config(): void
    {
        config(['lostandfound.automation_enabled' => false]);
        Mail::fake();

        Item::factory()->create(['date_lost' => now()->subDays(95)]);

        $this->artisan('disposal:process-cycle')->assertSuccessful();

        $this->assertDatabaseMissing('disposal_batches', ['stage' => 'pickup_window']);
        Mail::assertNothingSent();
    }

    public function test_officers_receive_a_database_notification_when_a_batch_stage_changes(): void
    {
        Mail::fake();
        $admin = User::factory()->create();
        Item::factory()->create(['date_lost' => now()->subDays(95)]);

        $this->artisan('disposal:process-cycle');

        $admin->refresh();
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $admin->id,
            'type' => DisposalBatchStageChangedNotification::class,
        ]);
    }

    public function test_officers_receive_one_notification_per_item_approaching_disposal_not_a_duplicate_each_run(): void
    {
        Mail::fake();
        $admin = User::factory()->create();
        Item::factory()->create(['date_lost' => now()->subDays(85)]); // within the 83-89 day warning band

        $this->artisan('disposal:process-cycle');
        $this->artisan('disposal:process-cycle'); // running it again should not duplicate the notification

        $count = $admin->notifications()->where('type', ItemApproachingDisposalNotification::class)->count();
        $this->assertSame(1, $count);
    }
}
