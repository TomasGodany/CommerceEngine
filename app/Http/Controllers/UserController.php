<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of the backend users.
     */
    public function index(): View
    {
        return view('users.index', [
            'users' => User::orderBy('name')->paginate(15),
        ]);
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        return view('users.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified user's role and status in storage.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->withErrors([
                'role' => 'Nemôžete zmeniť rolu ani stav svojho vlastného účtu.',
            ]);
        }

        $validated = $request->validated();
        $validated['is_active'] = $request->boolean('is_active');

        $user->update($validated);

        return redirect()->route('users.index')->with('status', 'Používateľ bol úspešne upravený.');
    }
}
