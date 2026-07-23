<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateOrderStatusRequest;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;

class OrderStatusController extends Controller
{
    /**
     * Update the status of the specified order and record the change in its history.
     */
    public function update(UpdateOrderStatusRequest $request, Order $order): RedirectResponse
    {
        $validated = $request->validated();

        $order->update(['status' => $validated['status']]);

        $order->statusHistories()->create([
            'status' => $validated['status'],
            'note' => $validated['note'] ?? null,
            'user_id' => $request->user()?->id,
        ]);

        return redirect()->route('orders.show', $order)->with('status', 'Stav objednávky bol úspešne zmenený.');
    }
}
