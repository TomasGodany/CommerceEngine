<?php

namespace Database\Factories;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Currency>
 */
class CurrencyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->currencyCode(),
            'symbol' => $this->faker->randomElement(['€', '$', 'Kč', '£']),
            'exchange_rate' => $this->faker->randomFloat(6, 0.5, 2),
            'is_default' => false,
        ];
    }
}
