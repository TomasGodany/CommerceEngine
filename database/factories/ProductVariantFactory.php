<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'sku' => strtoupper(fake()->unique()->bothify('VAR-####??')),
            'name' => fake()->optional()->word(),
            'price' => fake()->optional()->randomFloat(2, 5, 1000),
            'stock_quantity' => fake()->numberBetween(0, 200),
            'attributes' => ['color' => fake()->safeColorName(), 'size' => fake()->randomElement(['S', 'M', 'L', 'XL'])],
            'is_active' => true,
        ];
    }
}
