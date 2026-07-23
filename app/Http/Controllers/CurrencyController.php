<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCurrencyRequest;
use App\Http\Requests\UpdateCurrencyRequest;
use App\Models\Currency;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the currencies.
     */
    public function index(): View
    {
        return view('currencies.index', [
            'currencies' => Currency::orderBy('code')->paginate(15),
        ]);
    }

    /**
     * Show the form for creating a new currency.
     */
    public function create(): View
    {
        return view('currencies.create');
    }

    /**
     * Store a newly created currency in storage.
     */
    public function store(StoreCurrencyRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_default'] = $request->boolean('is_default');

        Currency::create($validated);

        return redirect()->route('currencies.index')->with('status', 'Mena bola úspešne vytvorená.');
    }

    /**
     * Show the form for editing the specified currency.
     */
    public function edit(Currency $currency): View
    {
        return view('currencies.edit', [
            'currency' => $currency,
        ]);
    }

    /**
     * Update the specified currency in storage.
     */
    public function update(UpdateCurrencyRequest $request, Currency $currency): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_default'] = $request->boolean('is_default');

        $currency->update($validated);

        return redirect()->route('currencies.index')->with('status', 'Mena bola úspešne upravená.');
    }

    /**
     * Remove the specified currency from storage.
     */
    public function destroy(Currency $currency): RedirectResponse
    {
        $currency->delete();

        return redirect()->route('currencies.index')->with('status', 'Mena bola úspešne odstránená.');
    }
}
