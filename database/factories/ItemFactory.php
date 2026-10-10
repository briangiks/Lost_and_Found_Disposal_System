<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'reference' => 'LF-'.fake()->unique()->numberBetween(2000, 9000),
            'name' => fake()->randomElement(['Black Backpack', 'Blue Water Bottle', 'Silver Watch', 'Grey Hoodie', 'USB-C Charger']),
            'color' => fake()->safeColorName(),
            'category' => fake()->randomElement(['Electronics', 'Personal', 'Clothing', 'Stationery', 'Accessories', 'Bags']),
            'date_lost' => fake()->dateTimeBetween('-120 days', 'now'),
            'location' => fake()->randomElement(['MSB 13', 'LT 3', 'Main Library', 'Student Centre', 'Gazebo']),
            'markings' => fake()->sentence(4),
            'status' => 'active',
            'reporter' => 'admin@strathmore.edu',
        ];
    }
}
