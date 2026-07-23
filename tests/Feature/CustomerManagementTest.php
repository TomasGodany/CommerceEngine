<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_customers_list(): void
    {
        $admin = User::factory()->admin()->create();
        Customer::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get(route('customers.index'));

        $response->assertOk();
    }

    public function test_admin_can_create_a_customer(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('customers.store'), [
            'first_name' => 'Ján',
            'last_name' => 'Testovací',
            'email' => 'jan.testovaci@example.com',
            'phone' => '0900123456',
            'is_company' => '0',
        ]);

        $response->assertRedirect(route('customers.index'));

        $this->assertDatabaseHas('customers', [
            'first_name' => 'Ján',
            'last_name' => 'Testovací',
            'email' => 'jan.testovaci@example.com',
        ]);
    }

    public function test_admin_can_update_a_customer(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = Customer::factory()->create(['first_name' => 'Pôvodné meno']);

        $response = $this->actingAs($admin)->put(route('customers.update', $customer), [
            'first_name' => 'Upravené meno',
            'last_name' => $customer->last_name,
            'email' => $customer->email,
            'is_company' => '0',
        ]);

        $response->assertRedirect(route('customers.index'));

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'first_name' => 'Upravené meno',
        ]);
    }

    public function test_admin_can_view_a_customer(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = Customer::factory()->create();

        $response = $this->actingAs($admin)->get(route('customers.show', $customer));

        $response->assertOk();
        $response->assertSee($customer->full_name);
    }

    public function test_admin_can_delete_a_customer(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = Customer::factory()->create();

        $response = $this->actingAs($admin)->delete(route('customers.destroy', $customer));

        $response->assertRedirect(route('customers.index'));

        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    }

    public function test_admin_can_add_an_address_to_a_customer(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = Customer::factory()->create();

        $response = $this->actingAs($admin)->post(route('customers.addresses.store', $customer), [
            'type' => 'shipping',
            'street' => 'Hlavná 1',
            'city' => 'Bratislava',
            'zip' => '81101',
            'country' => 'SK',
        ]);

        $response->assertRedirect(route('customers.show', $customer));

        $this->assertDatabaseHas('customer_addresses', [
            'customer_id' => $customer->id,
            'street' => 'Hlavná 1',
            'city' => 'Bratislava',
        ]);
    }

    public function test_customer_cannot_access_customer_management(): void
    {
        $customer = User::factory()->create();

        $response = $this->actingAs($customer)->get(route('customers.index'));

        $response->assertForbidden();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('customers.index'));

        $response->assertRedirect(route('login'));
    }
}
