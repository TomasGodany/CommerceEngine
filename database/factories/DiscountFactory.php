<?php

namespace Database\Factories;

use App\Enums\DiscountType;
use App\Models\Discount;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Discount>
 */
class DiscountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Zľava '.fake()->unique()->word(),
            'type' => DiscountType::Percentage,
            'value' => fake()->randomFloat(2, 5, 50),
            'starts_at' => null,
            'ends_at' => null,
            'is_active' => true,
            'product_id' => null,
            'category_id' => null,
        ];
    }
}
