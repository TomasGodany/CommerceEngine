<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTokenManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_generate_an_api_token_for_a_user(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->post(route('api-tokens.store'), [
            'user_id' => $user->id,
            'name' => 'Integrácia',
        ]);

        $response->assertRedirect(route('api-tokens.index'));
        $response->assertSessionHas('plain_text_token');

        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'tokenable_type' => User::class,
            'name' => 'Integrácia',
        ]);
    }

    public function test_admin_can_view_the_api_tokens_list(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $user->createToken('Existujúci kľúč');

        $response = $this->actingAs($admin)->get(route('api-tokens.index'));

        $response->assertOk();
        $response->assertSee('Existujúci kľúč');
    }

    public function test_admin_can_revoke_an_api_token(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $token = $user->createToken('Kľúč na zrušenie');

        $response = $this->actingAs($admin)->delete(route('api-tokens.destroy', $token->accessToken->id));

        $response->assertRedirect(route('api-tokens.index'));

        $this->assertDatabaseMissing('personal_access_tokens', [
            'id' => $token->accessToken->id,
        ]);
    }

    public function test_manager_cannot_access_api_tokens(): void
    {
        $manager = User::factory()->create(['role' => UserRole::Manager]);

        $response = $this->actingAs($manager)->get(route('api-tokens.index'));

        $response->assertForbidden();
    }

    public function test_customer_cannot_access_api_tokens(): void
    {
        $customer = User::factory()->create(['role' => UserRole::Customer]);

        $response = $this->actingAs($customer)->get(route('api-tokens.index'));

        $response->assertForbidden();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('api-tokens.index'));

        $response->assertRedirect(route('login'));
    }
}
