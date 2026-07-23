<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class CustomerOrderController extends Controller
{
    /**
     * Display a listing of the authenticated customer's orders.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $customer = $request->user()->customer;

        abort_unless($customer, Response::HTTP_FORBIDDEN);

        $orders = Order::query()
            ->where('customer_id', $customer->id)
            ->with('items')
            ->latest()
            ->paginate($request->integer('per_page', 15))
            ->withQueryString();

        return OrderResource::collection($orders);
    }

    /**
     * Display the specified order, scoped to the authenticated customer.
     */
    public function show(Request $request, Order $order): OrderResource
    {
        $customer = $request->user()->customer;

        abort_unless($customer && $order->customer_id === $customer->id, Response::HTTP_FORBIDDEN);

        return new OrderResource($order->load(['items', 'statusHistories']));
    }
}
