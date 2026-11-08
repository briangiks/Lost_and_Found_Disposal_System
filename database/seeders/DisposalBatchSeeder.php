<?php

namespace Database\Seeders;

use App\Models\DisposalBatch;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DisposalBatchSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@strathmore.edu')->first();
        $daysAgo = fn (int $n) => Carbon::today()->subDays($n);

        $batches = [
            [
                'ref' => 'B-014',
                'cycle' => 'Jan – March',
                'released' => 180,
                'items' => [
                    ['LF-0921', 'Grey Hoodie', 'Grey', 'Clothing', 275, 'MSB 13', 'Nike logo, small tear on cuff'],
                    ['LF-0918', 'Scientific Calculator', 'Black', 'Stationery', 272, 'LT 3', "Sticker 'BBIT 1.1'"],
                    ['LF-0905', 'Reading Glasses', 'Tortoise', 'Accessories', 268, 'Main Library', 'Cracked left arm'],
                    ['LF-0894', 'Blue Backpack', 'Blue', 'Bags', 261, 'Oval Building', 'Adidas, missing side pocket'],
                ],
            ],
            [
                'ref' => 'B-013',
                'cycle' => 'Oct – December',
                'released' => 272,
                'items' => [
                    ['LF-0871', 'White Earbuds', 'White', 'Electronics', 365, 'STMB Library', 'Left bud only'],
                    ['LF-0864', 'Silver Water Bottle', 'Silver', 'Personal', 361, 'Gazebo', 'Deep dent near cap'],
                    ['LF-0852', 'Denim Jacket', 'Blue', 'Clothing', 354, 'F4', 'Enamel pin on collar'],
                ],
            ],
            [
                'ref' => 'B-012',
                'cycle' => 'Jul – September',
                'released' => 365,
                'items' => [
                    ['LF-0803', 'Green Umbrella', 'Green', 'Personal', 455, 'Student Centre', 'Wooden handle'],
                    ['LF-0798', 'USB-C Charger', 'White', 'Electronics', 451, 'STMB Basement', '20W Apple, no cable'],
                    ['LF-0790', 'Yellow Cap', 'Yellow', 'Clothing', 447, 'F5', 'Strathmore Alumni print'],
                    ['LF-0781', 'Textbook — Calculus', 'Blue', 'Stationery', 440, 'MSB 7', "Name 'J. Otieno' inside cover"],
                    ['LF-0774', 'Rain Jacket', 'Navy', 'Clothing', 435, 'Main Library', 'Reflective strip on back'],
                ],
            ],
        ];

        foreach ($batches as $b) {
            $releasedAt = $daysAgo($b['released']);

            $batch = DisposalBatch::updateOrCreate(
                ['reference' => $b['ref']],
                [
                    'cycle_label' => $b['cycle'],
                    'stage' => 'released',
                    'officer_id' => $admin?->id,
                    'notice_sent_at' => $releasedAt->copy()->subDays(7),
                    'handover_due_at' => $releasedAt,
                    'released_at' => $releasedAt,
                ]
            );

            foreach ($b['items'] as [$ref, $name, $color, $category, $daysOld, $location, $markings]) {
                Item::updateOrCreate(
                    ['reference' => $ref],
                    [
                        'name' => $name,
                        'color' => $color,
                        'category' => $category,
                        'date_lost' => $daysAgo($daysOld)->toDateString(),
                        'location' => $location,
                        'markings' => $markings,
                        'status' => 'donated',
                        'reporter' => 'admin@strathmore.edu',
                        'disposal_batch_id' => $batch->id,
                    ]
                );
            }
        }

        $this->generateRecentBatches($admin, $daysAgo);
    }

    /**
     * The three hand-written batches above sit 6-12 months back, outside the
     * dashboard chart's 6-month window — so on their own, the "Disposed"
     * series on the dashboard would look empty for most of the chart. This
     * adds one more batch roughly every month for the last ~5 months so
     * every bar on the chart has real disposed-item data behind it.
     */
    private function generateRecentBatches(?User $admin, \Closure $daysAgo): void
    {
        $nameBank = [
            ['Grey Hoodie', 'Grey', 'Clothing'], ['USB-C Charger', 'White', 'Electronics'],
            ['Reading Glasses', 'Tortoise', 'Accessories'], ['Blue Backpack', 'Blue', 'Bags'],
            ['Student ID Card', 'White', 'Documents'], ['Water Bottle', 'Silver', 'Personal'],
            ['Scientific Calculator', 'Black', 'Stationery'], ['Wrist Watch', 'Black', 'Accessories'],
            ['Rain Jacket', 'Navy', 'Clothing'], ['Earphones', 'White', 'Electronics'],
            ['Umbrella', 'Black', 'Other'], ['Textbook', 'Blue', 'Stationery'],
        ];
        $locations = config('lostandfound.locations');

        $recentBatches = [
            ['ref' => 'B-015', 'cycle' => 'This quarter (older)', 'releasedDaysAgo' => 150, 'count' => 11],
            ['ref' => 'B-016', 'cycle' => 'Last quarter', 'releasedDaysAgo' => 120, 'count' => 14],
            ['ref' => 'B-017', 'cycle' => 'Two quarters ago', 'releasedDaysAgo' => 90, 'count' => 9],
            ['ref' => 'B-018', 'cycle' => 'Three months back', 'releasedDaysAgo' => 60, 'count' => 13],
            ['ref' => 'B-019', 'cycle' => 'Last month', 'releasedDaysAgo' => 30, 'count' => 10],
        ];

        $nextRef = 4000;

        foreach ($recentBatches as $b) {
            $releasedAt = $daysAgo($b['releasedDaysAgo']);

            $batch = DisposalBatch::updateOrCreate(
                ['reference' => $b['ref']],
                [
                    'cycle_label' => $b['cycle'],
                    'stage' => 'released',
                    'officer_id' => $admin?->id,
                    'notice_sent_at' => $releasedAt->copy()->subDays(7),
                    'handover_due_at' => $releasedAt,
                    'released_at' => $releasedAt,
                ]
            );

            for ($i = 0; $i < $b['count']; $i++) {
                [$name, $color, $category] = $nameBank[array_rand($nameBank)];
                $reference = 'LF-'.$nextRef;
                $nextRef++;
                // Items must already be ≥90 days old at the moment they were released.
                $ageAtRelease = random_int(90, 130);

                Item::updateOrCreate(
                    ['reference' => $reference],
                    [
                        'name' => $name,
                        'color' => $color,
                        'category' => $category,
                        'date_lost' => $releasedAt->copy()->subDays($ageAtRelease)->toDateString(),
                        'location' => $locations[array_rand($locations)],
                        'markings' => 'Handed over in batch #'.$b['ref'],
                        'status' => 'donated',
                        'reporter' => 'admin@strathmore.edu',
                        'disposal_batch_id' => $batch->id,
                    ]
                );
            }
        }
    }
}