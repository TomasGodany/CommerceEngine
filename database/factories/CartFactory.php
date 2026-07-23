<?php

namespace Database\Factories;

use App\Enums\CartStatus;
use App\Models\Cart;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Cart>
 */
class CartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => null,
            'guest_token' => Str::uuid()->toString(),
            'status' => CartStatus::Active,
        ];
    }

    /**
     * Indicate that the cart belongs to a customer.
     */
    public function forCustomer(?Customer $customer = null): static
    {
        return $this->state(fn (array $attributes) => [
            'customer_id' => $customer?->id ?? Customer::factory(),
            'guest_token' => null,
        ]);
    }
}
