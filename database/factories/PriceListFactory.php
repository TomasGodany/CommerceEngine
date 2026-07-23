<?php

namespace Database\Factories;

use App\Models\PriceList;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PriceList>
 */
class PriceListFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Cenník '.fake()->unique()->word(),
            'code' => strtoupper(fake()->unique()->bothify('PL-###??')),
            'description' => fake()->optional()->sentence(),
            'is_default' => false,
            'is_active' => true,
        ];
    }
}
