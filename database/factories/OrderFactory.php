<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_number' => 'ORD-'.strtoupper(fake()->unique()->bothify('########')),
            'customer_id' => Customer::factory(),
            'status' => OrderStatus::New,
            'payment_status' => PaymentStatus::Unpaid,
            'total_amount' => 0,
            'currency' => 'EUR',
            'notes' => fake()->optional()->sentence(),
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Order $order) {
            $items = OrderItem::factory()
                ->count(fake()->numberBetween(1, 3))
                ->create(['order_id' => $order->id]);

            $order->update(['total_amount' => $items->sum('total_price')]);
        });
    }
}
