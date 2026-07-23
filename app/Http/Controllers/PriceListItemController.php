<?php

namespace App\Http\Controllers;

use App\Models\PriceList;
use App\Models\PriceListItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PriceListItemController extends Controller
{
    /**
     * Store a newly created price list item in storage.
     */
    public function store(Request $request, PriceList $priceList): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => [
                'required',
                'exists:products,id',
                Rule::unique('price_list_items', 'product_id')->where('price_list_id', $priceList->id),
            ],
            'price' => ['required', 'numeric', 'min:0'],
        ]);

        $priceList->items()->create($validated);

        return redirect()->route('price-lists.show', $priceList)->with('status', 'Položka bola úspešne pridaná do cenníka.');
    }

    /**
     * Remove the specified price list item from storage.
     */
    public function destroy(PriceListItem $priceListItem): RedirectResponse
    {
        $priceList = $priceListItem->priceList;

        $priceListItem->delete();

        return redirect()->route('price-lists.show', $priceList)->with('status', 'Položka bola úspešne odstránená z cenníka.');
    }
}
