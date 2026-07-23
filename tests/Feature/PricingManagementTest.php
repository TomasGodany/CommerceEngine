<?php

namespace Tests\Feature;

use App\Models\Coupon;
use App\Models\Discount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PricingManagementTest extends TestCase
{
    use RefreshDatabase;

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

        $this->actingAs($customer)->get(route('discounts.index'))->assertForbidden();
        $this->actingAs($customer)->get(route('coupons.index'))->assertForbidden();
    }

    public function test_guest_is_redirected_to_login_for_pricing_management(): void
    {
        $this->get(route('discounts.index'))->assertRedirect(route('login'));
        $this->get(route('coupons.index'))->assertRedirect(route('login'));
    }
}
