<?php

namespace App\Services;

use App\Enums\CartStatus;
use App\Enums\DiscountType;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Exceptions\InsufficientStockException;
use App\Exceptions\InvalidCouponException;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Discount;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\StockItem;
use App\Models\TaxRate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutService
{
    /**
     * Turn the given cart into an order, validating stock and applying discounts/coupons/tax.
     *
     * @throws InsufficientStockException
     * @throws InvalidCouponException
     */
    public function checkout(Cart $cart, ?string $couponCode = null): Order
    {
        $cart->loadMissing(['items.product', 'items.productVariant']);

        $this->assertStockIsAvailable($cart->items);

        $coupon = $couponCode !== null ? $this->resolveCoupon($couponCode) : null;

        return DB::transaction(function () use ($cart, $coupon) {
            $subtotal = 0;
            $itemsData = [];

            foreach ($cart->items as $item) {
                $lineTotal = $this->discountedLineTotal($item);
                $subtotal += $lineTotal;

                $itemsData[] = [
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'product_name' => $item->product?->name ?? '',
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total_price' => $lineTotal,
                ];
            }

            $afterCoupon = $this->applyCoupon($subtotal, $coupon);
            $tax = $this->calculateTax($afterCoupon);
            $totalAmount = round($afterCoupon + $tax, 2);

            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'customer_id' => $cart->customer_id,
                'status' => OrderStatus::New,
                'payment_status' => PaymentStatus::Unpaid,
                'total_amount' => $totalAmount,
                'currency' => 'EUR',
            ]);

            foreach ($itemsData as $itemData) {
                OrderItem::create([
                    'order_id' => $order->id,
                    ...$itemData,
                ]);
            }

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => OrderStatus::New,
                'note' => 'Objednávka bola vytvorená cez API checkout.',
                'user_id' => null,
            ]);

            $this->reserveStock($cart->items);

            if ($coupon !== null) {
                $coupon->increment('used_count');
            }

            $cart->items()->delete();
            $cart->update(['status' => CartStatus::Converted]);

            return $order->load(['items', 'statusHistories']);
        });
    }

    /**
     * Ensure every cart item has enough available stock.
     *
     * @param  Collection<int, CartItem>  $items
     */
    private function assertStockIsAvailable($items): void
    {
        foreach ($items as $item) {
            $available = $this->stockItemsFor($item)
                ->sum(fn (StockItem $stockItem) => $stockItem->quantity - $stockItem->reserved_quantity);

            if ($available < $item->quantity) {
                throw new InsufficientStockException($item->product?->name ?? "#{$item->product_id}");
            }
        }
    }

    /**
     * Get the stock items matching the given cart item's product/variant.
     *
     * @return Collection<int, StockItem>
     */
    private function stockItemsFor(CartItem $item)
    {
        return StockItem::query()
            ->where('product_id', $item->product_id)
            ->where(function ($query) use ($item) {
                $item->product_variant_id === null
                    ? $query->whereNull('product_variant_id')
                    : $query->where('product_variant_id', $item->product_variant_id);
            })
            ->get();
    }

    /**
     * Reserve the stock for the given cart items.
     *
     * @param  Collection<int, CartItem>  $items
     */
    private function reserveStock($items): void
    {
        foreach ($items as $item) {
            $remaining = $item->quantity;

            $stockItems = $this->stockItemsFor($item);

            foreach ($stockItems as $stockItem) {
                if ($remaining <= 0) {
                    break;
                }

                $availableForItem = $stockItem->quantity - $stockItem->reserved_quantity;
                $reserveNow = min($availableForItem, $remaining);

                if ($reserveNow > 0) {
                    $stockItem->increment('reserved_quantity', $reserveNow);
                    $remaining -= $reserveNow;
                }
            }
        }
    }

    /**
     * Calculate the line total for a cart item after applying the best matching discount.
     */
    private function discountedLineTotal(CartItem $item): float
    {
        $lineSubtotal = (float) $item->unit_price * $item->quantity;

        $discount = Discount::query()
            ->where('is_active', true)
            ->where(function ($query) use ($item) {
                $query->where('product_id', $item->product_id)
                    ->orWhere('category_id', $item->product?->category_id);
            })
            ->where(function ($query) {
                $query->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->get()
            ->map(fn (Discount $discount) => $this->discountAmount($lineSubtotal, $discount))
            ->max();

        return round($lineSubtotal - (float) ($discount ?? 0), 2);
    }

    /**
     * Calculate the discount amount for a given base amount.
     */
    private function discountAmount(float $amount, Discount|Coupon $discount): float
    {
        return $discount->type === DiscountType::Percentage
            ? $amount * ((float) $discount->value / 100)
            : min($amount, (float) $discount->value);
    }

    /**
     * Resolve and validate a coupon by its code.
     *
     * @throws InvalidCouponException
     */
    private function resolveCoupon(string $couponCode): Coupon
    {
        $coupon = Coupon::query()->where('code', $couponCode)->first();

        if (! $coupon || ! $coupon->is_active) {
            throw new InvalidCouponException;
        }

        if ($coupon->starts_at && $coupon->starts_at->isFuture()) {
            throw new InvalidCouponException;
        }

        if ($coupon->ends_at && $coupon->ends_at->isPast()) {
            throw new InvalidCouponException;
        }

        if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
            throw new InvalidCouponException('Kupón bol už vyčerpaný.');
        }

        return $coupon;
    }

    /**
     * Apply the coupon discount to the subtotal.
     */
    private function applyCoupon(float $subtotal, ?Coupon $coupon): float
    {
        if ($coupon === null) {
            return round($subtotal, 2);
        }

        return round($subtotal - $this->discountAmount($subtotal, $coupon), 2);
    }

    /**
     * Calculate the tax amount for the given amount using the default tax rate.
     */
    private function calculateTax(float $amount): float
    {
        $taxRate = TaxRate::query()->where('is_default', true)->first();

        if (! $taxRate) {
            return 0;
        }

        return round($amount * ((float) $taxRate->rate / 100), 2);
    }

    /**
     * Generate a unique order number.
     */
    private function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'ORD-'.strtoupper(Str::random(8));
        } while (Order::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }
}
