<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StockMovement>
 */
class StockMovementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'warehouse_id' => Warehouse::factory(),
            'product_id' => Product::factory(),
            'product_variant_id' => null,
            'type' => fake()->randomElement(['in', 'out', 'transfer', 'adjustment']),
            'quantity' => fake()->numberBetween(1, 100),
            'note' => fake()->optional()->sentence(),
            'user_id' => User::factory(),
        ];
    }
}
