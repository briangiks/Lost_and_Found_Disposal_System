<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $daysAgo = fn (int $n) => Carbon::today()->subDays($n)->toDateString();

        $items = [
            // Just past 90 days -> due for disposal
            ['ref' => 'LF-1042', 'name' => 'Black HP Laptop', 'color' => 'Black', 'category' => 'Electronics', 'days' => 93, 'location' => 'Main Library', 'markings' => "Sticker 'Code or Die' on lid"],
            ['ref' => 'LF-1039', 'name' => 'Blue Water Bottle', 'color' => 'Blue', 'category' => 'Personal', 'days' => 92, 'location' => 'Gazebo', 'markings' => 'Dent on the base'],
            ['ref' => 'LF-1031', 'name' => 'Casio Calculator', 'color' => 'White', 'category' => 'Stationery', 'days' => 91, 'location' => 'LT 3', 'markings' => "Name 'A. Mwangi' on back"],
            ['ref' => 'LF-1027', 'name' => 'Grey Beanie', 'color' => 'Grey', 'category' => 'Clothing', 'days' => 90, 'location' => 'F4', 'markings' => 'Small hole on the seam'],
            // Approaching the 90-day cut-off
            ['ref' => 'LF-1026', 'name' => 'Denim Jacket', 'color' => 'Blue', 'category' => 'Clothing', 'days' => 86, 'location' => 'F2', 'markings' => 'Enamel pin on collar'],
            ['ref' => 'LF-1024', 'name' => 'White Earbuds Case', 'color' => 'White', 'category' => 'Electronics', 'days' => 84, 'location' => 'STMB Library', 'markings' => 'Left bud only'],
            // Within the quarter
            ['ref' => 'LF-1025', 'name' => 'Brown Leather Wallet', 'color' => 'Brown', 'category' => 'Personal', 'days' => 78, 'location' => 'Student Centre', 'markings' => 'Empty, no ID inside'],
            ['ref' => 'LF-1018', 'name' => 'Red Hoodie (M)', 'color' => 'Red', 'category' => 'Clothing', 'days' => 70, 'location' => 'MSB 13', 'markings' => 'Strathmore crest on chest'],
            ['ref' => 'LF-1017', 'name' => 'USB-C Charger', 'color' => 'White', 'category' => 'Electronics', 'days' => 66, 'location' => 'STMB Library', 'markings' => '20W, no cable'],
            ['ref' => 'LF-1011', 'name' => 'AirPods Pro Case', 'color' => 'White', 'category' => 'Electronics', 'days' => 58, 'location' => 'STMB Basement', 'markings' => 'Scratched lid'],
            ['ref' => 'LF-1010', 'name' => 'Black Backpack', 'color' => 'Black', 'category' => 'Bags', 'days' => 52, 'location' => 'LT 6', 'markings' => 'Adidas, missing side pocket'],
            ['ref' => 'LF-1009', 'name' => 'Textbook — Calculus', 'color' => 'Blue', 'category' => 'Stationery', 'days' => 45, 'location' => 'MSB 7', 'markings' => "Name 'J. Otieno' inside cover"],
            ['ref' => 'LF-1008', 'name' => 'Reading Glasses', 'color' => 'Tortoise', 'category' => 'Accessories', 'days' => 38, 'location' => 'Main Library', 'markings' => 'Cracked left arm'],
            // Fresh
            ['ref' => 'LF-1007', 'name' => 'Spiral Notebook', 'color' => 'Yellow', 'category' => 'Stationery', 'days' => 21, 'location' => 'F2', 'markings' => 'Course notes BBIT 2.2'],
            ['ref' => 'LF-1004', 'name' => 'Silver Wrist Watch', 'color' => 'Silver', 'category' => 'Accessories', 'days' => 14, 'location' => 'Oval Building', 'markings' => 'Cracked glass, leather strap'],
            ['ref' => 'LF-1001', 'name' => 'Black Umbrella', 'color' => 'Black', 'category' => 'Personal', 'days' => 6, 'location' => 'F5', 'markings' => 'Bent rib, wooden handle'],
        ];

        foreach ($items as $i) {
            Item::updateOrCreate(
                ['reference' => $i['ref']],
                [
                    'name' => $i['name'],
                    'color' => $i['color'],
                    'category' => $i['category'],
                    'date_lost' => $daysAgo($i['days']),
                    'location' => $i['location'],
                    'markings' => $i['markings'],
                    'status' => 'active',
                    'reporter' => 'admin@strathmore.edu',
                ]
            );
        }
    }
}
