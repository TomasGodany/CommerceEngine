<?php

namespace Tests\Feature\Api;

use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_guest_can_add_an_item_to_the_cart(): void
    {
        $product = Product::factory()->create(['price' => 25, 'is_active' => true]);

        $response = $this->postJson('/api/v1/cart/items', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response->assertSuccessful();
        $response->assertJsonCount(1, 'data.items');
        $response->assertJsonPath('data.items.0.quantity', 2);

        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
    }

    public function test_a_guest_can_view_their_cart_using_the_guest_token(): void
    {
        $product = Product::factory()->create(['price' => 25, 'is_active' => true]);

        $addResponse = $this->postJson('/api/v1/cart/items', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $guestToken = $addResponse->json('data.guest_token');
        $this->assertNotEmpty($guestToken);

        $response = $this->withHeader('X-Guest-Token', $guestToken)->getJson('/api/v1/cart');

        $response->assertOk();
        $response->assertJsonCount(1, 'data.items');
    }

    public function test_an_item_quantity_can_be_updated(): void
    {
        $product = Product::factory()->create(['price' => 10, 'is_active' => true]);

        $addResponse = $this->postJson('/api/v1/cart/items', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $guestToken = $addResponse->json('data.guest_token');
        $cartItemId = $addResponse->json('data.items.0.id');

        $response = $this->withHeader('X-Guest-Token', $guestToken)
            ->patchJson("/api/v1/cart/items/{$cartItemId}", ['quantity' => 5]);

        $response->assertOk();
        $response->assertJsonPath('data.items.0.quantity', 5);
    }

    public function test_an_item_can_be_removed_from_the_cart(): void
    {
        $product = Product::factory()->create(['price' => 10, 'is_active' => true]);

        $addResponse = $this->postJson('/api/v1/cart/items', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $guestToken = $addResponse->json('data.guest_token');
        $cartItemId = $addResponse->json('data.items.0.id');

        $response = $this->withHeader('X-Guest-Token', $guestToken)
            ->deleteJson("/api/v1/cart/items/{$cartItemId}");

        $response->assertOk();
        $response->assertJsonCount(0, 'data.items');

        $this->assertDatabaseMissing('cart_items', ['id' => $cartItemId]);
    }

    public function test_a_guest_cannot_update_another_guests_cart_item(): void
    {
        $product = Product::factory()->create(['price' => 10, 'is_active' => true]);

        $addResponse = $this->postJson('/api/v1/cart/items', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $cartItemId = $addResponse->json('data.items.0.id');

        $response = $this->withHeader('X-Guest-Token', 'other-guest-token')
            ->patchJson("/api/v1/cart/items/{$cartItemId}", ['quantity' => 5]);

        $response->assertNotFound();
    }

    public function test_guest_cart_is_merged_into_customer_cart_on_login(): void
    {
        $product = Product::factory()->create(['price' => 15, 'is_active' => true]);
        $user = User::factory()->create(['email' => 'merge@example.com', 'password' => bcrypt('password123')]);
        Customer::factory()->create(['user_id' => $user->id]);

        $addResponse = $this->postJson('/api/v1/cart/items', [
            'product_id' => $product->id,
            'quantity' => 3,
        ]);

        $guestToken = $addResponse->json('data.guest_token');

        $loginResponse = $this->withHeader('X-Guest-Token', $guestToken)->postJson('/api/v1/auth/login', [
            'email' => 'merge@example.com',
            'password' => 'password123',
        ]);

        $loginResponse->assertOk();
        $token = $loginResponse->json('token');

        $cartResponse = $this->withHeader('Authorization', 'Bearer '.$token)->getJson('/api/v1/cart');

        $cartResponse->assertOk();
        $cartResponse->assertJsonCount(1, 'data.items');
        $cartResponse->assertJsonPath('data.items.0.quantity', 3);
    }
}
