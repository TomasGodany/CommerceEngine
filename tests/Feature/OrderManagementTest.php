<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_orders_list(): void
    {
        $admin = User::factory()->admin()->create();
        Order::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get(route('orders.index'));

        $response->assertOk();
    }

    public function test_admin_can_view_create_order_form(): void
    {
        $admin = User::factory()->admin()->create();
        Product::factory()->count(2)->create();

        $response = $this->actingAs($admin)->get(route('orders.create'));

        $response->assertOk();
    }

    public function test_admin_can_view_edit_order_form(): void
    {
        $admin = User::factory()->admin()->create();
        $order = Order::factory()->create();

        $response = $this->actingAs($admin)->get(route('orders.edit', $order));

        $response->assertOk();
    }

    public function test_admin_can_create_an_order_with_items_and_total_is_calculated(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = Customer::factory()->create();
        $productOne = Product::factory()->create(['price' => 10]);
        $productTwo = Product::factory()->create(['price' => 25]);

        $response = $this->actingAs($admin)->post(route('orders.store'), [
            'customer_id' => $customer->id,
            'currency' => 'EUR',
            'notes' => 'Testovacia objednávka',
            'items' => [
                ['product_id' => $productOne->id, 'quantity' => 2],
                ['product_id' => $productTwo->id, 'quantity' => 1],
            ],
        ]);

        $response->assertRedirect(route('orders.index'));

        $order = Order::where('customer_id', $customer->id)->firstOrFail();

        $this->assertSame(2, $order->items()->count());
        $this->assertEquals(45.00, (float) $order->total_amount);
        $this->assertSame(OrderStatus::New, $order->status);
    }

    public function test_admin_can_change_order_status_and_history_is_recorded(): void
    {
        $admin = User::factory()->admin()->create();
        $order = Order::factory()->create(['status' => OrderStatus::New]);

        $response = $this->actingAs($admin)->patch(route('orders.status.update', $order), [
            'status' => OrderStatus::Processing->value,
            'note' => 'Objednávka sa vybavuje.',
        ]);

        $response->assertRedirect(route('orders.show', $order));

        $this->assertSame(OrderStatus::Processing, $order->fresh()->status);

        $this->assertDatabaseHas('order_status_histories', [
            'order_id' => $order->id,
            'status' => OrderStatus::Processing->value,
            'note' => 'Objednávka sa vybavuje.',
            'user_id' => $admin->id,
        ]);
    }

    public function test_admin_can_view_order_detail(): void
    {
        $admin = User::factory()->admin()->create();
        $order = Order::factory()->create();

        $response = $this->actingAs($admin)->get(route('orders.show', $order));

        $response->assertOk();
    }

    public function test_admin_can_update_an_order(): void
    {
        $admin = User::factory()->admin()->create();
        $order = Order::factory()->create();
        $customer = Customer::factory()->create();

        $response = $this->actingAs($admin)->put(route('orders.update', $order), [
            'customer_id' => $customer->id,
            'currency' => 'USD',
            'notes' => 'Aktualizovaná poznámka',
        ]);

        $response->assertRedirect(route('orders.index'));

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'customer_id' => $customer->id,
            'currency' => 'USD',
            'notes' => 'Aktualizovaná poznámka',
        ]);
    }

    public function test_admin_can_delete_an_order(): void
    {
        $admin = User::factory()->admin()->create();
        $order = Order::factory()->create();

        $response = $this->actingAs($admin)->delete(route('orders.destroy', $order));

        $response->assertRedirect(route('orders.index'));

        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }

    public function test_customer_cannot_access_order_management(): void
    {
        $customer = User::factory()->create();

        $response = $this->actingAs($customer)->get(route('orders.index'));

        $response->assertForbidden();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('orders.index'));

        $response->assertRedirect(route('login'));
    }
}
