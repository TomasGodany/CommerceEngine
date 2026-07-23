<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorController extends Controller
{
    /**
     * Show the two-factor authentication settings for the current user.
     */
    public function show(): View
    {
        /** @var User $user */
        $user = auth()->user();

        $qrCodeUrl = null;

        if (! $user->hasTwoFactorEnabled()) {
            $google2fa = new Google2FA;

            $secret = $google2fa->generateSecretKey();

            session(['2fa_pending_secret' => $secret]);

            $qrCodeUrl = $google2fa->getQRCodeUrl(config('app.name'), $user->email, $secret);
        }

        return view('two-factor.show', [
            'user' => $user,
            'qrCodeUrl' => $qrCodeUrl,
        ]);
    }

    /**
     * Enable two-factor authentication for the current user.
     */
    public function enable(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $secret = session('2fa_pending_secret');

        if (! $secret) {
            return redirect()->route('two-factor.show')->withErrors([
                'code' => 'Najprv si vygenerujte nový QR kód.',
            ]);
        }

        $google2fa = new Google2FA;

        if (! $google2fa->verifyKey($secret, $request->input('code'))) {
            return back()->withErrors([
                'code' => 'Neplatný overovací kód.',
            ]);
        }

        /** @var User $user */
        $user = auth()->user();

        $user->two_factor_secret = $secret;
        $user->two_factor_enabled_at = now();
        $user->save();

        $request->session()->forget('2fa_pending_secret');

        return redirect()->route('two-factor.show')->with('status', 'Dvojfaktorové overenie bolo úspešne zapnuté.');
    }

    /**
     * Disable two-factor authentication for the current user.
     */
    public function disable(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        /** @var User $user */
        $user = auth()->user();

        $google2fa = new Google2FA;

        if (! $google2fa->verifyKey($user->two_factor_secret, $request->input('code'))) {
            return back()->withErrors([
                'code' => 'Neplatný overovací kód.',
            ]);
        }

        $user->two_factor_secret = null;
        $user->two_factor_enabled_at = null;
        $user->save();

        return redirect()->route('two-factor.show')->with('status', 'Dvojfaktorové overenie bolo úspešne vypnuté.');
    }
}
