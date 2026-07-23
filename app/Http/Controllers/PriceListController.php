<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePriceListRequest;
use App\Http\Requests\UpdatePriceListRequest;
use App\Models\PriceList;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PriceListController extends Controller
{
    /**
     * Display a listing of the price lists.
     */
    public function index(): View
    {
        return view('price-lists.index', [
            'priceLists' => PriceList::withCount('items')->latest()->paginate(15),
        ]);
    }

    /**
     * Show the form for creating a new price list.
     */
    public function create(): View
    {
        return view('price-lists.create');
    }

    /**
     * Store a newly created price list in storage.
     */
    public function store(StorePriceListRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_default'] = $request->boolean('is_default');
        $validated['is_active'] = $request->boolean('is_active');

        PriceList::create($validated);

        return redirect()->route('price-lists.index')->with('status', 'Cenník bol úspešne vytvorený.');
    }

    /**
     * Display the specified price list along with its items.
     */
    public function show(PriceList $priceList): View
    {
        return view('price-lists.show', [
            'priceList' => $priceList->load('items.product'),
            'products' => Product::orderBy('name')->get(),
        ]);
    }

    /**
     * Show the form for editing the specified price list.
     */
    public function edit(PriceList $priceList): View
    {
        return view('price-lists.edit', [
            'priceList' => $priceList,
        ]);
    }

    /**
     * Update the specified price list in storage.
     */
    public function update(UpdatePriceListRequest $request, PriceList $priceList): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_default'] = $request->boolean('is_default');
        $validated['is_active'] = $request->boolean('is_active');

        $priceList->update($validated);

        return redirect()->route('price-lists.index')->with('status', 'Cenník bol úspešne upravený.');
    }

    /**
     * Remove the specified price list from storage.
     */
    public function destroy(PriceList $priceList): RedirectResponse
    {
        $priceList->delete();

        return redirect()->route('price-lists.index')->with('status', 'Cenník bol úspešne odstránený.');
    }
}
