<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use App\Notifications\ItemApproachingDisposalNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationCenterTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_see_their_notifications(): void
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        $user->notify(new ItemApproachingDisposalNotification($item));

        $this->actingAs($user)
            ->get('/notifications')
            ->assertOk()
            ->assertSee($item->reference);
    }

    public function test_marking_a_notification_as_read_redirects_to_its_url_and_clears_the_unread_badge(): void
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        $user->notify(new ItemApproachingDisposalNotification($item));
        $notification = $user->notifications()->first();

        $this->actingAs($user)
            ->post("/notifications/{$notification->id}/read")
            ->assertRedirect("/items/{$item->id}");

        $this->assertNotNull($notification->fresh()->read_at);
        $this->assertSame(0, $user->unreadNotifications()->count());
    }

    public function test_mark_all_as_read_clears_every_unread_notification(): void
    {
        $user = User::factory()->create();
        $itemA = Item::factory()->create();
        $itemB = Item::factory()->create();
        $user->notify(new ItemApproachingDisposalNotification($itemA));
        $user->notify(new ItemApproachingDisposalNotification($itemB));

        $this->actingAs($user)->post('/notifications/read-all')->assertRedirect();

        $this->assertSame(0, $user->unreadNotifications()->count());
    }
}
