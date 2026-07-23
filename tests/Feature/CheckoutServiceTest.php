<?php

namespace Tests\Feature;

use App\Enums\CartStatus;
use App\Enums\DiscountType;
use App\Enums\OrderStatus;
use App\Exceptions\InsufficientStockException;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Discount;
use App\Models\Product;
use App\Models\StockItem;
use App\Models\TaxRate;
use App\Models\Warehouse;
use App\Services\CheckoutService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_creates_an_order_from_the_cart_and_empties_it(): void
    {
        $customer = Customer::factory()->create();
        $product = Product::factory()->create(['price' => 100]);
        $warehouse = Warehouse::factory()->create();

        StockItem::factory()->create([
            'warehouse_id' => $warehouse->id,
            'product_id' => $product->id,
            'product_variant_id' => null,
            'quantity' => 10,
            'reserved_quantity' => 0,
        ]);

        TaxRate::create(['name' => 'DPH', 'rate' => 20, 'is_default' => true]);

        $cart = Cart::factory()->forCustomer($customer)->create();
        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'product_variant_id' => null,
            'quantity' => 2,
            'unit_price' => 100,
        ]);

        $order = (new CheckoutService)->checkout($cart->fresh());

        $this->assertSame(OrderStatus::New, $order->status);
        $this->assertSame($customer->id, $order->customer_id);
        $this->assertEquals(240.00, (float) $order->total_amount);
        $this->assertSame(1, $order->items()->count());
        $this->assertDatabaseHas('order_status_histories', [
            'order_id' => $order->id,
            'status' => OrderStatus::New->value,
        ]);

        $this->assertSame(0, $cart->items()->count());
        $this->assertSame(CartStatus::Converted, $cart->fresh()->status);
        $this->assertSame(2, StockItem::first()->fresh()->reserved_quantity);
    }

    public function test_checkout_throws_exception_when_stock_is_insufficient(): void
    {
        $customer = Customer::factory()->create();
        $product = Product::factory()->create(['price' => 50]);
        $warehouse = Warehouse::factory()->create();

        StockItem::factory()->create([
            'warehouse_id' => $warehouse->id,
            'product_id' => $product->id,
            'product_variant_id' => null,
            'quantity' => 1,
            'reserved_quantity' => 0,
        ]);

        $cart = Cart::factory()->forCustomer($customer)->create();
        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'product_variant_id' => null,
            'quantity' => 5,
            'unit_price' => 50,
        ]);

        $this->expectException(InsufficientStockException::class);

        (new CheckoutService)->checkout($cart->fresh());

        $this->assertDatabaseMissing('orders', ['customer_id' => $customer->id]);
    }

    public function test_checkout_applies_an_active_discount_for_the_product(): void
    {
        $customer = Customer::factory()->create();
        $product = Product::factory()->create(['price' => 100]);
        $warehouse = Warehouse::factory()->create();

        StockItem::factory()->create([
            'warehouse_id' => $warehouse->id,
            'product_id' => $product->id,
            'product_variant_id' => null,
            'quantity' => 10,
            'reserved_quantity' => 0,
        ]);

        Discount::factory()->create([
            'type' => DiscountType::Percentage,
            'value' => 10,
            'is_active' => true,
            'product_id' => $product->id,
            'category_id' => null,
        ]);

        $cart = Cart::factory()->forCustomer($customer)->create();
        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'product_variant_id' => null,
            'quantity' => 1,
            'unit_price' => 100,
        ]);

        $order = (new CheckoutService)->checkout($cart->fresh());

        // 100 - 10% discount = 90, no tax rate configured.
        $this->assertEquals(90.00, (float) $order->total_amount);
        $this->assertEquals(90.00, (float) $order->items()->first()->total_price);
    }

    public function test_checkout_applies_a_valid_coupon(): void
    {
        $customer = Customer::factory()->create();
        $product = Product::factory()->create(['price' => 100]);
        $warehouse = Warehouse::factory()->create();

        StockItem::factory()->create([
            'warehouse_id' => $warehouse->id,
            'product_id' => $product->id,
            'product_variant_id' => null,
            'quantity' => 10,
            'reserved_quantity' => 0,
        ]);

        $coupon = Coupon::factory()->create([
            'code' => 'ZLAVA10',
            'type' => DiscountType::Percentage,
            'value' => 10,
            'is_active' => true,
        ]);

        $cart = Cart::factory()->forCustomer($customer)->create();
        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'product_variant_id' => null,
            'quantity' => 1,
            'unit_price' => 100,
        ]);

        $order = (new CheckoutService)->checkout($cart->fresh(), $coupon->code);

        $this->assertEquals(90.00, (float) $order->total_amount);
        $this->assertSame(1, $coupon->fresh()->used_count);
    }
}
