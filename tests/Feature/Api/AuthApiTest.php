<?php

namespace Tests\Feature\Api;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_customer_can_register(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'first_name' => 'Jana',
            'last_name' => 'Nováková',
            'email' => 'jana.novakova@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertCreated();
        $response->assertJsonStructure(['customer', 'token']);

        $this->assertDatabaseHas('users', [
            'email' => 'jana.novakova@example.com',
            'role' => UserRole::Customer->value,
        ]);

        $this->assertDatabaseHas('customers', [
            'email' => 'jana.novakova@example.com',
            'first_name' => 'Jana',
            'last_name' => 'Nováková',
        ]);

        $user = User::where('email', 'jana.novakova@example.com')->firstOrFail();
        $this->assertNotNull($user->customer);
    }

    public function test_registration_fails_with_invalid_data(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'first_name' => 'Jana',
        ]);

        $response->assertStatus(422);
    }

    public function test_a_customer_can_login_with_correct_credentials(): void
    {
        User::factory()->create([
            'email' => 'customer@example.com',
            'password' => bcrypt('secret123'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'customer@example.com',
            'password' => 'secret123',
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['user', 'token']);
    }

    public function test_login_fails_with_incorrect_credentials(): void
    {
        User::factory()->create([
            'email' => 'customer@example.com',
            'password' => bcrypt('secret123'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'customer@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401);
    }

    public function test_a_customer_can_logout(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/v1/auth/logout');

        $response->assertOk();
        $this->assertDatabaseCount('personal_access_tokens', 0);
    }
}
