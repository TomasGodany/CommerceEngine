<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Laravel\Sanctum\PersonalAccessToken;

class ApiTokenController extends Controller
{
    /**
     * Display a listing of the API tokens.
     */
    public function index(): View
    {
        return view('api-tokens.index', [
            'tokens' => PersonalAccessToken::with('tokenable')->latest()->paginate(20),
            'users' => User::orderBy('name')->get(),
        ]);
    }

    /**
     * Generate a new API token for the given user.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        $user = User::findOrFail($validated['user_id']);

        $token = $user->createToken($validated['name']);

        return redirect()->route('api-tokens.index')
            ->with('status', 'API kľúč bol úspešne vygenerovaný.')
            ->with('plain_text_token', $token->plainTextToken);
    }

    /**
     * Revoke the specified API token.
     */
    public function destroy(int $tokenId): RedirectResponse
    {
        $token = PersonalAccessToken::findOrFail($tokenId);
        $token->delete();

        return redirect()->route('api-tokens.index')->with('status', 'API kľúč bol úspešne zrušený.');
    }
}
