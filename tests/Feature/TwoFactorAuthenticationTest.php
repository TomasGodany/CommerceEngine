<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PragmaRX\Google2FA\Google2FA;
use Tests\TestCase;

class TwoFactorAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_enable_two_factor_authentication_with_a_valid_code(): void
    {
        $user = User::factory()->create();
        $google2fa = new Google2FA;

        $this->actingAs($user)->get(route('two-factor.show'))->assertOk();

        $secret = session('2fa_pending_secret');
        $this->assertNotNull($secret);

        $response = $this->actingAs($user)->post(route('two-factor.enable'), [
            'code' => $google2fa->getCurrentOtp($secret),
        ]);

        $response->assertRedirect(route('two-factor.show'));
        $response->assertSessionHas('status');

        $this->assertTrue($user->fresh()->hasTwoFactorEnabled());
    }

    public function test_login_with_two_factor_enabled_redirects_to_challenge_without_logging_in(): void
    {
        $google2fa = new Google2FA;
        $secret = $google2fa->generateSecretKey();

        $user = User::factory()->create([
            'two_factor_secret' => $secret,
            'two_factor_enabled_at' => now(),
        ]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('two-factor.challenge'));
        $this->assertGuest();
        $this->assertSame($user->id, session('2fa_user_id'));
    }

    public function test_correct_two_factor_code_logs_the_user_in(): void
    {
        $google2fa = new Google2FA;
        $secret = $google2fa->generateSecretKey();

        $user = User::factory()->create([
            'two_factor_secret' => $secret,
            'two_factor_enabled_at' => now(),
        ]);

        $this->withSession(['2fa_user_id' => $user->id]);

        $response = $this->post(route('two-factor.challenge.store'), [
            'code' => $google2fa->getCurrentOtp($secret),
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_incorrect_two_factor_code_rejects_login(): void
    {
        $google2fa = new Google2FA;
        $secret = $google2fa->generateSecretKey();

        $user = User::factory()->create([
            'two_factor_secret' => $secret,
            'two_factor_enabled_at' => now(),
        ]);

        $this->withSession(['2fa_user_id' => $user->id]);

        $response = $this->post(route('two-factor.challenge.store'), [
            'code' => '000000',
        ]);

        $response->assertSessionHasErrors('code');
        $this->assertGuest();
    }

    public function test_user_can_disable_two_factor_authentication_with_a_valid_code(): void
    {
        $google2fa = new Google2FA;
        $secret = $google2fa->generateSecretKey();

        $user = User::factory()->create([
            'two_factor_secret' => $secret,
            'two_factor_enabled_at' => now(),
        ]);

        $response = $this->actingAs($user)->post(route('two-factor.disable'), [
            'code' => $google2fa->getCurrentOtp($secret),
        ]);

        $response->assertRedirect(route('two-factor.show'));
        $this->assertFalse($user->fresh()->hasTwoFactorEnabled());
    }
}
