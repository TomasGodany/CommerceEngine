<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterCustomerRequest;
use App\Models\Customer;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(private readonly CartService $cartService) {}

    /**
     * Register a new customer account and issue an API token.
     */
    public function register(RegisterCustomerRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $customer = DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => trim("{$validated['first_name']} {$validated['last_name']}"),
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => UserRole::Customer,
            ]);

            return Customer::create([
                'user_id' => $user->id,
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
            ]);
        });

        $token = $customer->user->createToken('api-token')->plainTextToken;

        return response()->json([
            'customer' => $customer,
            'token' => $token,
        ], 201);
    }

    /**
     * Authenticate the customer and issue an API token.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (! Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Zadané prihlasovacie údaje nie sú správne.',
            ], 401);
        }

        /** @var User $user */
        $user = Auth::user();

        if (! $user->is_active) {
            Auth::logout();

            return response()->json([
                'message' => 'Váš účet je deaktivovaný.',
            ], 403);
        }

        if ($user->customer) {
            $this->cartService->mergeGuestCartIntoCustomer($request, $user->customer);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    /**
     * Revoke the current access token.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Boli ste úspešne odhlásený.',
        ]);
    }

    /**
     * Get the authenticated user.
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }
}
