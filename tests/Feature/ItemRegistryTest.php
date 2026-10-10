<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemRegistryTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_authenticated_officer_can_log_a_new_item(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->post('/items', [
            'name' => 'Black HP Laptop',
            'color' => 'Black',
            'category' => 'Electronics',
            'date_lost' => now()->toDateString(),
            'location' => 'Main Library',
            'markings' => 'Sticker on lid',
        ]);

        $item = Item::firstOrFail();
        $response->assertRedirect("/items/{$item->id}");

        $this->assertDatabaseHas('items', [
            'name' => 'Black HP Laptop',
            'status' => 'active',
        ]);
        $this->assertStringStartsWith('LF-', $item->reference);
    }

    public function test_marking_an_item_as_claimed_updates_its_status(): void
    {
        $this->actingAs(User::factory()->create());
        $item = Item::factory()->create(['status' => 'active']);

        $this->post("/items/{$item->id}/claim")
            ->assertRedirect("/items/{$item->id}");

        $item->refresh();
        $this->assertSame('claimed', $item->status);
        $this->assertNotNull($item->claimed_at);
    }

    public function test_dashboard_search_filters_the_registry(): void
    {
        $this->actingAs(User::factory()->create());

        Item::factory()->create(['name' => 'Black HP Laptop']);
        Item::factory()->create(['name' => 'Blue Water Bottle']);

        $response = $this->get('/dashboard?q=Laptop');

        $response->assertSee('Black HP Laptop');
        $response->assertDontSee('Blue Water Bottle');
    }

    public function test_dashboard_counts_only_active_items_in_custody(): void
    {
        $this->actingAs(User::factory()->create());

        Item::factory()->create(['status' => 'active']);
        Item::factory()->create(['status' => 'claimed']);
        Item::factory()->create(['status' => 'donated']);

        $response = $this->get('/dashboard');

        $response->assertViewHas('totalInCustody', 1);
    }
}
