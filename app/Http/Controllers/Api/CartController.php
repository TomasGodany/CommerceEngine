<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Http\Resources\CartResource;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cartService) {}

    /**
     * Display the current cart (guest or authenticated customer).
     */
    public function show(Request $request): CartResource
    {
        $cart = $this->cartService->resolveForRequest($request);

        return new CartResource($cart->load('items.product'));
    }

    /**
     * Add an item to the current cart.
     */
    public function addItem(AddCartItemRequest $request): CartResource
    {
        $cart = $this->cartService->resolveForRequest($request);
        $validated = $request->validated();

        $product = Product::findOrFail($validated['product_id']);

        $existingItem = $cart->items()
            ->where('product_id', $validated['product_id'])
            ->where('product_variant_id', $validated['product_variant_id'] ?? null)
            ->first();

        if ($existingItem) {
            $existingItem->increment('quantity', $validated['quantity']);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'product_variant_id' => $validated['product_variant_id'] ?? null,
                'quantity' => $validated['quantity'],
                'unit_price' => $product->sale_price ?? $product->price,
            ]);
        }

        return new CartResource($cart->load('items.product'));
    }

    /**
     * Update the quantity of an item in the current cart.
     */
    public function updateItem(UpdateCartItemRequest $request, CartItem $cartItem): CartResource
    {
        $cart = $this->cartService->resolveForRequest($request);

        abort_unless($cartItem->cart_id === $cart->id, Response::HTTP_NOT_FOUND);

        $cartItem->update(['quantity' => $request->validated('quantity')]);

        return new CartResource($cart->load('items.product'));
    }

    /**
     * Remove an item from the current cart.
     */
    public function removeItem(Request $request, CartItem $cartItem): CartResource|JsonResponse
    {
        $cart = $this->cartService->resolveForRequest($request);

        abort_unless($cartItem->cart_id === $cart->id, Response::HTTP_NOT_FOUND);

        $cartItem->delete();

        return new CartResource($cart->load('items.product'));
    }
}
