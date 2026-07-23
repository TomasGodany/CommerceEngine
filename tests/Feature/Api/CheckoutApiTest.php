<?php

namespace Tests\Feature\Api;

use App\Enums\OrderStatus;
use App\Models\Customer;
use App\Models\Product;
use App\Models\StockItem;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class CheckoutApiTest extends TestCase
{
    use RefreshDatabase;

    private function authenticatedCustomer(): array
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);
        $token = $user->createToken('api-token')->plainTextToken;

        return [$user, $customer, $token];
    }

    public function test_a_customer_can_checkout_and_the_cart_is_emptied(): void
    {
        [$user, $customer, $token] = $this->authenticatedCustomer();

        $product = Product::factory()->create(['price' => 50, 'is_active' => true]);
        $warehouse = Warehouse::factory()->create();
        StockItem::factory()->create([
            'warehouse_id' => $warehouse->id,
            'product_id' => $product->id,
            'product_variant_id' => null,
            'quantity' => 10,
            'reserved_quantity' => 0,
        ]);

        $this->withHeader('Authorization', 'Bearer '.$token)->postJson('/api/v1/cart/items', [
            'product_id' => $product->id,
            'quantity' => 2,
        ])->assertSuccessful();

        $response = $this->withHeader('Authorization', 'Bearer '.$token)->postJson('/api/v1/checkout');

        $response->assertCreated();
        $response->assertJsonPath('data.status', OrderStatus::New->value);

        $this->assertDatabaseHas('orders', [
            'customer_id' => $customer->id,
        ]);

        $cartResponse = $this->withHeader('Authorization', 'Bearer '.$token)->getJson('/api/v1/cart');
        $cartResponse->assertJsonCount(0, 'data.items');
    }

    public function test_checkout_fails_when_cart_is_empty(): void
    {
        [$user, $customer, $token] = $this->authenticatedCustomer();

        $response = $this->withHeader('Authorization', 'Bearer '.$token)->postJson('/api/v1/checkout');

        $response->assertStatus(422);
    }

    public function test_customer_can_list_their_orders(): void
    {
        [$user, $customer, $token] = $this->authenticatedCustomer();

        $product = Product::factory()->create(['price' => 50, 'is_active' => true]);
        $warehouse = Warehouse::factory()->create();
        StockItem::factory()->create([
            'warehouse_id' => $warehouse->id,
            'product_id' => $product->id,
            'product_variant_id' => null,
            'quantity' => 10,
            'reserved_quantity' => 0,
        ]);

        $this->withHeader('Authorization', 'Bearer '.$token)->postJson('/api/v1/cart/items', [
            'product_id' => $product->id,
            'quantity' => 1,
        ])->assertSuccessful();

        $this->withHeader('Authorization', 'Bearer '.$token)->postJson('/api/v1/checkout')->assertCreated();

        $response = $this->withHeader('Authorization', 'Bearer '.$token)->getJson('/api/v1/orders');

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
    }

    public function test_a_customer_cannot_view_another_customers_order(): void
    {
        [$userOne, $customerOne, $tokenOne] = $this->authenticatedCustomer();
        [$userTwo, $customerTwo, $tokenTwo] = $this->authenticatedCustomer();

        $product = Product::factory()->create(['price' => 50, 'is_active' => true]);
        $warehouse = Warehouse::factory()->create();
        StockItem::factory()->create([
            'warehouse_id' => $warehouse->id,
            'product_id' => $product->id,
            'product_variant_id' => null,
            'quantity' => 10,
            'reserved_quantity' => 0,
        ]);

        $this->withHeader('Authorization', 'Bearer '.$tokenOne)->postJson('/api/v1/cart/items', [
            'product_id' => $product->id,
            'quantity' => 1,
        ])->assertSuccessful();

        $checkoutResponse = $this->withHeader('Authorization', 'Bearer '.$tokenOne)
            ->postJson('/api/v1/checkout')
            ->assertCreated();

        $orderId = $checkoutResponse->json('data.id');

        // The "sanctum" guard caches the resolved user on the guard instance, so it must
        // be reset here before authenticating as a different customer within the same test.
        Auth::forgetGuards();

        $response = $this->withHeader('Authorization', 'Bearer '.$tokenTwo)
            ->getJson("/api/v1/orders/{$orderId}");

        $response->assertForbidden();
    }
}
