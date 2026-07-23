<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_settings_page(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('settings.edit'));

        $response->assertOk();
    }

    public function test_admin_can_update_company_settings(): void
    {
        $admin = User::factory()->admin()->create();
        Setting::current();

        $response = $this->actingAs($admin)->put(route('settings.update'), [
            'company_name' => 'Nová firma s.r.o.',
            'company_street' => 'Nová 1',
            'company_city' => 'Košice',
            'company_country' => 'Slovensko',
            'ico' => '87654321',
            'dic' => '2099999999',
            'ic_dph' => 'SK2099999999',
            'iban' => 'SK1234567890123456789012',
            'bic' => 'TATRSKBX',
            'email' => 'nova@firma.test',
            'phone' => '+421 900 111 222',
            'default_currency_code' => 'EUR',
        ]);

        $response->assertRedirect(route('settings.edit'));

        $this->assertDatabaseHas('settings', [
            'company_name' => 'Nová firma s.r.o.',
            'iban' => 'SK1234567890123456789012',
        ]);
    }

    public function test_customer_cannot_access_settings(): void
    {
        $customer = User::factory()->create();

        $response = $this->actingAs($customer)->get(route('settings.edit'));

        $response->assertForbidden();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('settings.edit'));

        $response->assertRedirect(route('login'));
    }
}
