<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDiscountRequest;
use App\Http\Requests\UpdateDiscountRequest;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DiscountController extends Controller
{
    /**
     * Display a listing of the discounts.
     */
    public function index(): View
    {
        return view('discounts.index', [
            'discounts' => Discount::with(['product', 'category'])->latest()->paginate(15),
        ]);
    }

    /**
     * Show the form for creating a new discount.
     */
    public function create(): View
    {
        return view('discounts.create', [
            'products' => Product::orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created discount in storage.
     */
    public function store(StoreDiscountRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->boolean('is_active');

        Discount::create($validated);

        return redirect()->route('discounts.index')->with('status', 'Zľava bola úspešne vytvorená.');
    }

    /**
     * Show the form for editing the specified discount.
     */
    public function edit(Discount $discount): View
    {
        return view('discounts.edit', [
            'discount' => $discount,
            'products' => Product::orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified discount in storage.
     */
    public function update(UpdateDiscountRequest $request, Discount $discount): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->boolean('is_active');

        $discount->update($validated);

        return redirect()->route('discounts.index')->with('status', 'Zľava bola úspešne upravená.');
    }

    /**
     * Remove the specified discount from storage.
     */
    public function destroy(Discount $discount): RedirectResponse
    {
        $discount->delete();

        return redirect()->route('discounts.index')->with('status', 'Zľava bola úspešne odstránená.');
    }
}
