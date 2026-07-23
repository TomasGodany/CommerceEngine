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
            return response()->json(['message' => 'Nemáte oprávnenie na vykonanie tejto akcie.'], 403);
        }

        $allowedRoles = array_map(fn (string $role) => UserRole::from($role), $roles);

        if (! empty($allowedRoles) && ! in_array($user->role, $allowedRoles, true)) {
            return response()->json(['message' => 'Nemáte oprávnenie na vykonanie tejto akcie.'], 403);
        }

        return $next($request);
    }
}
