<?php

namespace Tests\Feature;

use App\Models\Currency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CurrencyManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_currencies_list(): void
    {
        $admin = User::factory()->admin()->create();
        Currency::factory()->count(2)->create();

        $response = $this->actingAs($admin)->get(route('currencies.index'));

        $response->assertOk();
    }

    public function test_admin_can_create_a_currency(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('currencies.store'), [
            'code' => 'USD',
            'symbol' => '$',
            'exchange_rate' => '1.08',
            'is_default' => '0',
        ]);

        $response->assertRedirect(route('currencies.index'));

        $this->assertDatabaseHas('currencies', [
            'code' => 'USD',
            'symbol' => '$',
        ]);
    }

    public function test_admin_can_update_a_currency(): void
    {
        $admin = User::factory()->admin()->create();
        $currency = Currency::factory()->create(['code' => 'EUR']);

        $response = $this->actingAs($admin)->put(route('currencies.update', $currency), [
            'code' => 'EUR',
            'symbol' => '€',
            'exchange_rate' => '1.00',
            'is_default' => '1',
        ]);

        $response->assertRedirect(route('currencies.index'));

        $this->assertDatabaseHas('currencies', [
            'id' => $currency->id,
            'is_default' => true,
        ]);
    }

    public function test_admin_can_delete_a_currency(): void
    {
        $admin = User::factory()->admin()->create();
        $currency = Currency::factory()->create();

        $response = $this->actingAs($admin)->delete(route('currencies.destroy', $currency));

        $response->assertRedirect(route('currencies.index'));

        $this->assertDatabaseMissing('currencies', ['id' => $currency->id]);
    }

    public function test_customer_cannot_access_currency_management(): void
    {
        $customer = User::factory()->create();

        $response = $this->actingAs($customer)->get(route('currencies.index'));

        $response->assertForbidden();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('currencies.index'));

        $response->assertRedirect(route('login'));
    }
}
