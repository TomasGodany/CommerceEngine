<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! $user->is_active) {
            return $this->deny($request);
        }

        $allowedRoles = array_map(fn (string $role) => UserRole::from($role), $roles);

        if (! empty($allowedRoles) && ! in_array($user->role, $allowedRoles, true)) {
            return $this->deny($request);
        }

        return $next($request);
    }

    /**
     * Build the response for a denied request.
     */
    private function deny(Request $request): Response
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Nemáte oprávnenie na vykonanie tejto akcie.'], 403);
        }

        abort(403, 'Nemáte oprávnenie na vykonanie tejto akcie.');
    }
}
