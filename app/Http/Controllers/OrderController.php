<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index(Request $request): View
    {
        $query = Order::with('customer')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        return view('orders.index', [
            'orders' => $query->paginate(15)->withQueryString(),
            'statuses' => OrderStatus::cases(),
            'selectedStatus' => $request->string('status')->toString(),
        ]);
    }

    /**
     * Show the form for creating a new order.
     */
    public function create(): View
    {
        return view('orders.create', [
            'customers' => Customer::orderBy('first_name')->orderBy('last_name')->get(),
            'products' => Product::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(StoreOrderRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $items = $validated['items'];
        unset($validated['items']);

        $validated['order_number'] = $this->generateOrderNumber();
        $validated['currency'] = $validated['currency'] ?? 'EUR';

        $order = Order::create($validated);

        $totalAmount = 0;

        foreach ($items as $item) {
            $product = Product::find($item['product_id']);
            $quantity = (int) $item['quantity'];
            $unitPrice = (float) $product->price;
            $totalPrice = $unitPrice * $quantity;

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
            ]);

            $totalAmount += $totalPrice;
        }

        $order->update(['total_amount' => $totalAmount]);

        return redirect()->route('orders.index')->with('status', 'Objednávka bola úspešne vytvorená.');
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order): View
    {
        return view('orders.show', [
            'order' => $order->load(['customer', 'items.product', 'statusHistories.user']),
            'statuses' => OrderStatus::cases(),
        ]);
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit(Order $order): View
    {
        return view('orders.edit', [
            'order' => $order,
            'customers' => Customer::orderBy('first_name')->orderBy('last_name')->get(),
        ]);
    }

    /**
     * Update the specified order in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order): RedirectResponse
    {
        $order->update($request->validated());

        return redirect()->route('orders.index')->with('status', 'Objednávka bola úspešne upravená.');
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy(Order $order): RedirectResponse
    {
        $order->delete();

        return redirect()->route('orders.index')->with('status', 'Objednávka bola úspešne odstránená.');
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
