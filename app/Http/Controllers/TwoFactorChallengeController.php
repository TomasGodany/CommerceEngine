<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorChallengeController extends Controller
{
    /**
     * Show the two-factor authentication challenge form.
     */
    public function show(): View|RedirectResponse
    {
        if (! session('2fa_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.two-factor-challenge');
    }

    /**
     * Verify the two-factor authentication code and log the user in.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $userId = session('2fa_user_id');

        if (! $userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);

        $google2fa = new Google2FA;

        if (! $user || ! $google2fa->verifyKey($user->two_factor_secret, $request->input('code'))) {
            return back()->withErrors([
                'code' => 'Neplatný overovací kód.',
            ]);
        }

        Auth::login($user);

        $request->session()->forget('2fa_user_id');

        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }
}
