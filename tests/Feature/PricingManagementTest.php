<?php

namespace Tests\Feature;

use App\Models\Coupon;
use App\Models\Discount;
use App\Models\PriceList;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PricingManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_price_lists_list(): void
    {
        $admin = User::factory()->admin()->create();
        PriceList::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get(route('price-lists.index'));

        $response->assertOk();
    }

    public function test_admin_can_create_a_price_list(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('price-lists.store'), [
            'name' => 'Testovací cenník',
            'code' => 'PL-TEST-001',
            'description' => 'Testovací popis',
            'is_default' => '0',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('price-lists.index'));

        $this->assertDatabaseHas('price_lists', [
            'name' => 'Testovací cenník',
            'code' => 'PL-TEST-001',
        ]);
    }

    public function test_admin_can_update_a_price_list(): void
    {
        $admin = User::factory()->admin()->create();
        $priceList = PriceList::factory()->create(['name' => 'Pôvodný cenník']);

        $response = $this->actingAs($admin)->put(route('price-lists.update', $priceList), [
            'name' => 'Upravený cenník',
            'code' => $priceList->code,
            'is_default' => '0',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('price-lists.index'));

        $this->assertDatabaseHas('price_lists', [
            'id' => $priceList->id,
            'name' => 'Upravený cenník',
        ]);
    }

    public function test_admin_can_delete_a_price_list(): void
    {
        $admin = User::factory()->admin()->create();
        $priceList = PriceList::factory()->create();

        $response = $this->actingAs($admin)->delete(route('price-lists.destroy', $priceList));

        $response->assertRedirect(route('price-lists.index'));

        $this->assertDatabaseMissing('price_lists', ['id' => $priceList->id]);
    }

    public function test_admin_can_add_an_item_to_a_price_list(): void
    {
        $admin = User::factory()->admin()->create();
        $priceList = PriceList::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($admin)->post(route('price-lists.items.store', $priceList), [
            'product_id' => $product->id,
            'price' => 19.99,
        ]);

        $response->assertRedirect(route('price-lists.show', $priceList));

        $this->assertDatabaseHas('price_list_items', [
            'price_list_id' => $priceList->id,
            'product_id' => $product->id,
            'price' => 19.99,
        ]);
    }

    public function test_admin_can_remove_an_item_from_a_price_list(): void
    {
        $admin = User::factory()->admin()->create();
        $priceList = PriceList::factory()->create();
        $item = $priceList->items()->create([
            'product_id' => Product::factory()->create()->id,
            'price' => 9.99,
        ]);

        $response = $this->actingAs($admin)->delete(route('price-list-items.destroy', $item));

        $response->assertRedirect(route('price-lists.show', $priceList));

        $this->assertDatabaseMissing('price_list_items', ['id' => $item->id]);
    }

    public function test_admin_can_create_a_discount(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('discounts.store'), [
            'name' => 'Testovacia zľava',
            'type' => 'percentage',
            'value' => 10,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('discounts.index'));

        $this->assertDatabaseHas('discounts', [
            'name' => 'Testovacia zľava',
            'type' => 'percentage',
        ]);
    }

    public function test_admin_can_update_a_discount(): void
    {
        $admin = User::factory()->admin()->create();
        $discount = Discount::factory()->create(['name' => 'Pôvodná zľava']);

        $response = $this->actingAs($admin)->put(route('discounts.update', $discount), [
            'name' => 'Upravená zľava',
            'type' => $discount->type->value,
            'value' => $discount->value,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('discounts.index'));

        $this->assertDatabaseHas('discounts', [
            'id' => $discount->id,
            'name' => 'Upravená zľava',
        ]);
    }

    public function test_admin_can_delete_a_discount(): void
    {
        $admin = User::factory()->admin()->create();
        $discount = Discount::factory()->create();

        $response = $this->actingAs($admin)->delete(route('discounts.destroy', $discount));

        $response->assertRedirect(route('discounts.index'));

        $this->assertDatabaseMissing('discounts', ['id' => $discount->id]);
    }

    public function test_admin_can_create_a_coupon(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('coupons.store'), [
            'code' => 'TESTCOUPON10',
            'type' => 'fixed',
            'value' => 5,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('coupons.index'));

        $this->assertDatabaseHas('coupons', [
            'code' => 'TESTCOUPON10',
            'type' => 'fixed',
        ]);
    }

    public function test_admin_can_update_a_coupon(): void
    {
        $admin = User::factory()->admin()->create();
        $coupon = Coupon::factory()->create(['code' => 'OLDCODE']);

        $response = $this->actingAs($admin)->put(route('coupons.update', $coupon), [
            'code' => 'NEWCODE',
            'type' => $coupon->type->value,
            'value' => $coupon->value,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('coupons.index'));

        $this->assertDatabaseHas('coupons', [
            'id' => $coupon->id,
            'code' => 'NEWCODE',
        ]);
    }

    public function test_admin_can_delete_a_coupon(): void
    {
        $admin = User::factory()->admin()->create();
        $coupon = Coupon::factory()->create();

        $response = $this->actingAs($admin)->delete(route('coupons.destroy', $coupon));

        $response->assertRedirect(route('coupons.index'));

        $this->assertDatabaseMissing('coupons', ['id' => $coupon->id]);
    }

    public function test_customer_cannot_access_pricing_management(): void
    {
        $customer = User::factory()->create();

        $this->actingAs($customer)->get(route('price-lists.index'))->assertForbidden();
        $this->actingAs($customer)->get(route('discounts.index'))->assertForbidden();
        $this->actingAs($customer)->get(route('coupons.index'))->assertForbidden();
    }

    public function test_guest_is_redirected_to_login_for_pricing_management(): void
    {
        $this->get(route('price-lists.index'))->assertRedirect(route('login'));
        $this->get(route('discounts.index'))->assertRedirect(route('login'));
        $this->get(route('coupons.index'))->assertRedirect(route('login'));
    }
}
