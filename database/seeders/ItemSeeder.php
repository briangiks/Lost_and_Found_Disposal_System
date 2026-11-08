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

        // A handful of hand-written, named items so the dashboard's first
        // page always has specific, recognisable entries to point at during
        // a demo (rather than only generic generated ones).
        $showcase = [
            ['ref' => 'LF-1042', 'name' => 'Black HP Laptop', 'color' => 'Black', 'category' => 'Electronics', 'days' => 93, 'location' => 'Main Library', 'markings' => "Sticker 'Code or Die' on lid"],
            ['ref' => 'LF-1039', 'name' => 'Blue Water Bottle', 'color' => 'Blue', 'category' => 'Personal', 'days' => 92, 'location' => 'Gazebo', 'markings' => 'Dent on the base'],
            ['ref' => 'LF-1031', 'name' => 'Casio Calculator', 'color' => 'White', 'category' => 'Stationery', 'days' => 91, 'location' => 'LT 3', 'markings' => "Name 'A. Mwangi' on back"],
            ['ref' => 'LF-1027', 'name' => 'Grey Beanie', 'color' => 'Grey', 'category' => 'Clothing', 'days' => 90, 'location' => 'F4', 'markings' => 'Small hole on the seam'],
            ['ref' => 'LF-1026', 'name' => 'Denim Jacket', 'color' => 'Blue', 'category' => 'Clothing', 'days' => 86, 'location' => 'F2', 'markings' => 'Enamel pin on collar'],
            ['ref' => 'LF-1024', 'name' => 'White Earbuds Case', 'color' => 'White', 'category' => 'Electronics', 'days' => 84, 'location' => 'STMB Library', 'markings' => 'Left bud only'],
            ['ref' => 'LF-1025', 'name' => 'Brown Leather Wallet', 'color' => 'Brown', 'category' => 'Personal', 'days' => 78, 'location' => 'Student Centre', 'markings' => 'Empty, no ID inside'],
            ['ref' => 'LF-1018', 'name' => 'Red Hoodie (M)', 'color' => 'Red', 'category' => 'Clothing', 'days' => 70, 'location' => 'MSB 13', 'markings' => 'Strathmore crest on chest'],
            ['ref' => 'LF-1017', 'name' => 'USB-C Charger', 'color' => 'White', 'category' => 'Electronics', 'days' => 66, 'location' => 'STMB Library', 'markings' => '20W, no cable'],
            ['ref' => 'LF-1011', 'name' => 'AirPods Pro Case', 'color' => 'White', 'category' => 'Electronics', 'days' => 58, 'location' => 'STMB Basement', 'markings' => 'Scratched lid'],
            ['ref' => 'LF-1010', 'name' => 'Black Backpack', 'color' => 'Black', 'category' => 'Bags', 'days' => 52, 'location' => 'LT 6', 'markings' => 'Adidas, missing side pocket'],
            ['ref' => 'LF-1009', 'name' => 'Textbook — Calculus', 'color' => 'Blue', 'category' => 'Stationery', 'days' => 45, 'location' => 'MSB 7', 'markings' => "Name 'J. Otieno' inside cover"],
            ['ref' => 'LF-1008', 'name' => 'Reading Glasses', 'color' => 'Tortoise', 'category' => 'Accessories', 'days' => 38, 'location' => 'Main Library', 'markings' => 'Cracked left arm'],
            ['ref' => 'LF-1007', 'name' => 'Spiral Notebook', 'color' => 'Yellow', 'category' => 'Stationery', 'days' => 21, 'location' => 'F2', 'markings' => 'Course notes BBIT 2.2'],
            ['ref' => 'LF-1004', 'name' => 'Silver Wrist Watch', 'color' => 'Silver', 'category' => 'Accessories', 'days' => 14, 'location' => 'Oval Building', 'markings' => 'Cracked glass, leather strap'],
            ['ref' => 'LF-1001', 'name' => 'Black Umbrella', 'color' => 'Black', 'category' => 'Personal', 'days' => 6, 'location' => 'F5', 'markings' => 'Bent rib, wooden handle'],
        ];

        foreach ($showcase as $i) {
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

        $this->generateBulkItems($daysAgo);
    }

    /**
     * Generates a large, realistically-distributed bulk of items so the
     * dashboard, filters, and the "logged vs claimed vs disposed" chart all
     * have enough real data to be worth looking at — roughly 200 items on
     * top of the 16 showcase ones above, spread deliberately across every
     * age band (fresh / within the quarter / due) and across the last 9
     * months so the chart has a genuine month-by-month story to tell.
     */
    private function generateBulkItems(\Closure $daysAgo): void
    {
        $nameBank = [
            'Electronics' => ['Laptop Charger', 'Wireless Mouse', 'Bluetooth Speaker', 'Phone Case', 'Power Bank', 'Earphones', 'USB Flash Drive', 'Laptop Sleeve', 'Smart Watch', 'HDMI Cable'],
            'Personal' => ['Water Bottle', 'Sunglasses', 'Wallet', 'Keychain', 'Perfume Bottle', 'Hand Sanitizer', 'Makeup Pouch', 'Face Mask Pack', 'Hair Clip Set', 'Lip Balm'],
            'Clothing' => ['Hoodie', 'Cardigan', 'Scarf', 'Cap', 'Pair of Gloves', 'Rain Jacket', 'Sweater', 'T-Shirt', 'Track Jacket', 'Beanie'],
            'Stationery' => ['Scientific Calculator', 'Ring Binder', 'Highlighter Set', 'Textbook', 'Lecture Notes Folder', 'Geometry Set', 'Sticky Notes Pack', 'Ballpoint Pen Set', 'Lab Manual', 'Graph Notebook'],
            'Accessories' => ['Wrist Watch', 'Reading Glasses', 'Bracelet', 'Necklace', 'Belt', 'Hair Tie Pack', 'Tie', 'Cufflinks', 'Brooch', 'Earring Pair'],
            'Bags' => ['Backpack', 'Tote Bag', 'Laptop Bag', 'Drawstring Bag', 'Gym Bag', 'Lunch Bag', 'Sling Bag', 'Duffel Bag', 'Pencil Case', 'Document Folder'],
            'Documents' => ['Student ID Card', 'National ID Card', 'Library Card', 'Exam Slip', 'Receipt Booklet', 'Notebook with Notes', 'Printed Assignment', 'Admission Letter', 'Transcript Copy', 'Bank Card'],
            'Other' => ['Umbrella', 'Key Bunch', 'Padlock', 'Flash Drive Case', 'Travel Mug', 'Phone Charger Cable', 'Pair of Earbuds', 'Notebook Cover', 'Name Tag', 'Lanyard'],
        ];

        $colors = ['Black', 'White', 'Blue', 'Red', 'Grey', 'Green', 'Yellow', 'Brown', 'Pink', 'Navy', 'Maroon', 'Silver', 'Purple', 'Orange'];
        $locations = config('lostandfound.locations');
        $categories = array_keys($nameBank);
        $markingTemplates = [
            'Small scratch near the edge', 'Sticker on the front', "Owner's initials engraved",
            'Slightly worn at the corners', 'Strap is frayed', 'Missing one button',
            'Name tag attached', 'Faint stain on one side', 'Chipped at the corner',
            'Course code written inside', 'Barcode sticker still attached', 'No visible markings',
            'Dent on the base', 'Crack across the screen protector', 'Keychain attached',
        ];

        $reporters = ['admin@strathmore.edu', 'officer@strathmore.edu'];

        // [band weight, min days, max days] — weighted so there's a healthy
        // population in every band the dashboard/disposal workflow cares about.
        $ageBands = [
            ['weight' => 35, 'min' => 0, 'max' => 29],    // fresh
            ['weight' => 30, 'min' => 30, 'max' => 82],   // within the quarter
            ['weight' => 10, 'min' => 83, 'max' => 89],   // approaching disposal (early-warning band)
            ['weight' => 25, 'min' => 90, 'max' => 270],  // due for disposal (spread further back so claimed/disposed history has depth)
        ];

        $weighted = [];
        foreach ($ageBands as $band) {
            for ($w = 0; $w < $band['weight']; $w++) {
                $weighted[] = $band;
            }
        }

        $targetCount = 200;
        $startRef = 3000;

        for ($n = 0; $n < $targetCount; $n++) {
            $category = $categories[array_rand($categories)];
            $name = $nameBank[$category][array_rand($nameBank[$category])];
            $color = $colors[array_rand($colors)];
            $location = $locations[array_rand($locations)];
            $marking = $markingTemplates[array_rand($markingTemplates)];
            $band = $weighted[array_rand($weighted)];
            $daysOld = random_int($band['min'], $band['max']);
            $reference = 'LF-'.($startRef + $n);

            // ~30% of items that are old enough to plausibly have been
            // collected already (10+ days old) are marked claimed, with a
            // believable claimed_at somewhere between when they were lost
            // and today — this is what gives the dashboard's "Claimed" chart
            // series real month-by-month shape instead of being empty.
            $status = 'active';
            $claimedAt = null;
            if ($daysOld >= 10 && random_int(1, 100) <= 30) {
                $status = 'claimed';
                $claimedAfterDays = random_int(1, min($daysOld, 45));
                $claimedAt = Carbon::today()->subDays($daysOld)->addDays($claimedAfterDays);
                if ($claimedAt->isFuture()) {
                    $claimedAt = Carbon::today();
                }
            }

            Item::updateOrCreate(
                ['reference' => $reference],
                [
                    'name' => $name,
                    'color' => $color,
                    'category' => $category,
                    'date_lost' => ($daysAgo)($daysOld),
                    'location' => $location,
                    'markings' => $marking,
                    'status' => $status,
                    'claimed_at' => $claimedAt,
                    'reporter' => $reporters[array_rand($reporters)],
                ]
            );
        }
    }
}