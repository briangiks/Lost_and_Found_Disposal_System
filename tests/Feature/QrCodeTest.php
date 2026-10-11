<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QrCodeTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_qr_image_route_returns_an_svg(): void
    {
        $this->actingAs(User::factory()->create());
        $item = Item::factory()->create();

        $response = $this->get("/items/{$item->id}/qr");

        $response->assertOk();
        $response->assertHeader('Content-Type', 'image/svg+xml');
    }

    public function test_the_printable_desk_card_shows_the_item_reference(): void
    {
        $this->actingAs(User::factory()->create());
        $item = Item::factory()->create();

        $this->get("/items/{$item->id}/qr-card")
            ->assertOk()
            ->assertSee($item->reference);
    }
}
