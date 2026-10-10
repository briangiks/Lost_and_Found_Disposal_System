<?php

namespace Tests\Feature;

use App\Models\DisposalBatch;
use App\Models\Item;
use App\Models\SentEmail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class DisposalWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create();
    }

    public function test_item_is_flagged_due_for_disposal_after_90_days(): void
    {
        $fresh = Item::factory()->create(['date_lost' => now()->subDays(10)]);
        $due = Item::factory()->create(['date_lost' => now()->subDays(95)]);

        $this->assertFalse($fresh->is_due_for_disposal);
        $this->assertTrue($due->is_due_for_disposal);
    }

    public function test_sending_the_notice_opens_a_seven_day_pickup_window_and_logs_an_email(): void
    {
        $this->actingAs($this->admin());

        Item::factory()->create(['date_lost' => now()->subDays(100)]);

        // Visiting the disposal page creates/returns the current open batch.
        $this->get('/disposal');
        $batch = DisposalBatch::firstOrFail();

        $this->post("/disposal/{$batch->id}/send-notice")
            ->assertRedirect('/disposal');

        $batch->refresh();

        $this->assertSame('pickup_window', $batch->stage);
        $this->assertNotNull($batch->handover_due_at);
        $this->assertEqualsWithDelta(
            now()->addDays(7)->timestamp,
            $batch->handover_due_at->timestamp,
            5
        );
        $this->assertDatabaseHas('sent_emails', [
            'type' => 'comms_notice',
            'disposal_batch_id' => $batch->id,
        ]);
    }

    public function test_releasing_a_batch_marks_remaining_items_as_donated(): void
    {
        $this->actingAs($this->admin());

        $item = Item::factory()->create(['date_lost' => now()->subDays(100)]);

        $this->get('/disposal');
        $batch = DisposalBatch::firstOrFail();
        $this->post("/disposal/{$batch->id}/send-notice");

        // Fast-forward the pickup window, as the "Skip wait (demo)" button does.
        $this->post("/disposal/{$batch->id}/skip-wait");

        $this->post("/disposal/{$batch->id}/release")
            ->assertRedirect('/disposal');

        $batch->refresh();
        $item->refresh();

        $this->assertSame('released', $batch->stage);
        $this->assertSame('donated', $item->status);
        $this->assertDatabaseHas('sent_emails', [
            'type' => 'outreach_handover',
            'disposal_batch_id' => $batch->id,
        ]);
    }

    public function test_a_claimed_item_is_not_released_in_the_batch(): void
    {
        $this->actingAs($this->admin());

        $claimed = Item::factory()->create(['date_lost' => now()->subDays(100)]);

        $this->get('/disposal');
        $batch = DisposalBatch::firstOrFail();
        $this->post("/disposal/{$batch->id}/send-notice");

        // Owner collects the item during the pickup window.
        $this->post("/items/{$claimed->id}/claim");

        $this->post("/disposal/{$batch->id}/skip-wait");
        $this->post("/disposal/{$batch->id}/release");

        $claimed->refresh();
        $this->assertSame('claimed', $claimed->status);
        $this->assertNotSame('donated', $claimed->status);
    }
}
