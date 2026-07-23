<?php

namespace App\Services;

use App\Enums\CartStatus;
use App\Models\Cart;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartService
{
    /**
     * Resolve the active cart for the current request (authenticated customer or guest).
     */
    public function resolveForRequest(Request $request): Cart
    {
        $customer = $request->user('sanctum')?->customer;

        if ($customer) {
            return $this->activeCartForCustomer($customer);
        }

        $guestToken = $request->header('X-Guest-Token');

        if ($guestToken) {
            $cart = Cart::query()->where('guest_token', $guestToken)->where('status', CartStatus::Active)->first();

            if ($cart) {
                return $cart;
            }
        }

        return Cart::create([
            'guest_token' => Str::uuid()->toString(),
            'status' => CartStatus::Active,
        ]);
    }

    /**
     * Get or create the active cart for the given customer.
     */
    public function activeCartForCustomer(Customer $customer): Cart
    {
        $cart = Cart::query()
            ->where('customer_id', $customer->id)
            ->where('status', CartStatus::Active)
            ->first();

        if ($cart) {
            return $cart;
        }

        return Cart::create([
            'customer_id' => $customer->id,
            'status' => CartStatus::Active,
        ]);
    }

    /**
     * Merge the guest cart (identified by the request's X-Guest-Token header) into the
     * customer's own cart, then discard the guest cart.
     */
    public function mergeGuestCartIntoCustomer(Request $request, Customer $customer): void
    {
        $guestToken = $request->header('X-Guest-Token');

        if (! $guestToken) {
            return;
        }

        $guestCart = Cart::query()
            ->where('guest_token', $guestToken)
            ->where('status', CartStatus::Active)
            ->first();

        if (! $guestCart) {
            return;
        }

        $customerCart = $this->activeCartForCustomer($customer);

        foreach ($guestCart->items as $guestItem) {
            $existingItem = $customerCart->items()
                ->where('product_id', $guestItem->product_id)
                ->where('product_variant_id', $guestItem->product_variant_id)
                ->first();

            if ($existingItem) {
                $existingItem->increment('quantity', $guestItem->quantity);
            } else {
                $customerCart->items()->create([
                    'product_id' => $guestItem->product_id,
                    'product_variant_id' => $guestItem->product_variant_id,
                    'quantity' => $guestItem->quantity,
                    'unit_price' => $guestItem->unit_price,
                ]);
            }
        }

        $guestCart->items()->delete();
        $guestCart->update(['status' => CartStatus::Converted, 'guest_token' => null]);
    }
}
