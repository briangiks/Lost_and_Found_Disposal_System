<?php

namespace Tests\Feature;

use App\Models\DisposalBatch;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RolePermissionTest extends TestCase
{
    use RefreshDatabase;

    private function officer(): User
    {
        return User::factory()->create(['role' => 'officer']);
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_an_officer_can_log_and_claim_items(): void
    {
        $this->actingAs($this->officer());

        $this->post('/items', [
            'name' => 'Blue Water Bottle',
            'category' => 'Personal',
            'date_lost' => now()->toDateString(),
            'location' => 'Gazebo',
        ])->assertRedirect();

        $this->assertDatabaseHas('items', ['name' => 'Blue Water Bottle']);
    }

    public function test_an_officer_cannot_delete_an_item(): void
    {
        $this->actingAs($this->officer());
        $item = Item::factory()->create();

        $this->delete("/items/{$item->id}")->assertForbidden();
        $this->assertDatabaseHas('items', ['id' => $item->id]);
    }

    public function test_an_admin_can_delete_an_item(): void
    {
        $this->actingAs($this->admin());
        $item = Item::factory()->create();

        $this->delete("/items/{$item->id}")->assertRedirect('/dashboard');
        $this->assertDatabaseMissing('items', ['id' => $item->id]);
    }

    public function test_an_officer_can_send_the_disposal_notice_but_not_release_the_batch(): void
    {
        Mail::fake();
        $this->actingAs($this->officer());

        Item::factory()->create(['date_lost' => now()->subDays(95)]);
        $this->get('/disposal');
        $batch = DisposalBatch::firstOrFail();

        $this->post("/disposal/{$batch->id}/send-notice")->assertRedirect('/disposal');
        $batch->refresh();
        $this->assertSame('pickup_window', $batch->stage);

        $this->post("/disposal/{$batch->id}/release")->assertForbidden();
        $batch->refresh();
        $this->assertNotSame('released', $batch->stage);
    }

    public function test_an_admin_can_release_the_batch(): void
    {
        Mail::fake();
        $this->actingAs($this->admin());

        Item::factory()->create(['date_lost' => now()->subDays(95)]);
        $this->get('/disposal');
        $batch = DisposalBatch::firstOrFail();
        $this->post("/disposal/{$batch->id}/send-notice");
        $batch->update(['handover_due_at' => now()->subMinute()]);

        $this->post("/disposal/{$batch->id}/release")->assertRedirect('/disposal');
        $this->assertSame('released', $batch->fresh()->stage);
    }
}
