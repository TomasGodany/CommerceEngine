<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\InsufficientStockException;
use App\Exceptions\InvalidCouponException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Http\Resources\OrderResource;
use App\Services\CartService;
use App\Services\CheckoutService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly CheckoutService $checkoutService,
    ) {}

    /**
     * Turn the authenticated customer's cart into an order.
     */
    public function store(CheckoutRequest $request): OrderResource|JsonResponse
    {
        $customer = $request->user()->customer;

        abort_unless($customer, Response::HTTP_FORBIDDEN);

        $cart = $this->cartService->activeCartForCustomer($customer);

        if ($cart->items()->doesntExist()) {
            return response()->json([
                'message' => 'Košík je prázdny.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $order = $this->checkoutService->checkout($cart, $request->validated('coupon_code'));
        } catch (InsufficientStockException|InvalidCouponException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return new OrderResource($order);
    }
}
