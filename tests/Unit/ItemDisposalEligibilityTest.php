<?php

namespace Tests\Unit;

use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * These exercise Item::isDue() / Item::age_band in isolation from the rest of
 * the disposal workflow — the boundary at exactly 90 days is the single most
 * important rule in the whole system (get it wrong by one day either way and
 * the university either disposes of something too early, or holds onto
 * everything past its lawful window), so it's worth pinning down precisely.
 *
 * Note: these live in tests/Unit (as requested) but extend the framework
 * TestCase + RefreshDatabase rather than a bare PHPUnit TestCase, because
 * Item's accessors read config() and the disposal_age_days/early_warning_days
 * business rules, which only exist once the framework has booted. A "pure"
 * PHPUnit unit test (no framework) can't exercise real Eloquent accessors.
 */
class ItemDisposalEligibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_item_is_not_due_at_89_days(): void
    {
        $item = Item::factory()->create(['date_lost' => now()->subDays(89)]);

        $this->assertFalse($item->isDue());
        $this->assertFalse($item->is_due_for_disposal);
    }

    public function test_an_item_becomes_due_at_exactly_90_days(): void
    {
        $item = Item::factory()->create(['date_lost' => now()->subDays(90)]);

        $this->assertTrue($item->isDue());
        $this->assertTrue($item->is_due_for_disposal);
    }

    public function test_an_item_remains_due_past_90_days(): void
    {
        $item = Item::factory()->create(['date_lost' => now()->subDays(140)]);

        $this->assertTrue($item->isDue());
    }

    public function test_a_claimed_item_is_never_due_regardless_of_age(): void
    {
        $item = Item::factory()->create([
            'date_lost' => now()->subDays(200),
            'status' => 'claimed',
            'claimed_at' => now(),
        ]);

        $this->assertFalse($item->isDue());
    }

    public function test_a_donated_item_is_never_due_regardless_of_age(): void
    {
        $item = Item::factory()->create([
            'date_lost' => now()->subDays(400),
            'status' => 'donated',
        ]);

        $this->assertFalse($item->isDue());
    }

    /** @dataProvider ageBandProvider */
    public function test_age_band_boundaries(int $daysOld, string $expectedBand): void
    {
        $item = Item::factory()->create(['date_lost' => now()->subDays($daysOld)]);

        $this->assertSame($expectedBand, $item->age_band);
    }

    public static function ageBandProvider(): array
    {
        return [
            'day 0 is fresh' => [0, 'fresh'],
            'day 29 is still fresh' => [29, 'fresh'],
            'day 30 enters the quarter band' => [30, 'within_quarter'],
            'day 82 is still within the quarter' => [82, 'within_quarter'],
            'day 83 enters the early-warning band' => [83, 'within_quarter'],
            'day 89 is still within the quarter' => [89, 'within_quarter'],
            'day 90 is due' => [90, 'due'],
            'day 91 is due' => [91, 'due'],
        ];
    }

    public function test_is_approaching_disposal_is_true_only_in_the_7_day_warning_window(): void
    {
        $tooEarly = Item::factory()->create(['date_lost' => now()->subDays(82)]);
        $inWindow = Item::factory()->create(['date_lost' => now()->subDays(85)]);
        $alreadyDue = Item::factory()->create(['date_lost' => now()->subDays(90)]);

        $this->assertFalse($tooEarly->is_approaching_disposal);
        $this->assertTrue($inWindow->is_approaching_disposal);
        $this->assertFalse($alreadyDue->is_approaching_disposal, 'an item that is already due should no longer be flagged as merely "approaching"');
    }

    public function test_due_for_disposal_scope_matches_the_isDue_accessor(): void
    {
        Item::factory()->create(['date_lost' => now()->subDays(89)]);
        $due = Item::factory()->create(['date_lost' => now()->subDays(90)]);

        $scoped = Item::dueForDisposal()->get();

        $this->assertCount(1, $scoped);
        $this->assertTrue($scoped->first()->is($due));
    }
}
