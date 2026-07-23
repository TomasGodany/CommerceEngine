<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaxRateRequest;
use App\Http\Requests\UpdateTaxRateRequest;
use App\Models\TaxRate;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TaxRateController extends Controller
{
    /**
     * Display a listing of the tax rates.
     */
    public function index(): View
    {
        return view('tax-rates.index', [
            'taxRates' => TaxRate::orderBy('rate')->paginate(15),
        ]);
    }

    /**
     * Show the form for creating a new tax rate.
     */
    public function create(): View
    {
        return view('tax-rates.create');
    }

    /**
     * Store a newly created tax rate in storage.
     */
    public function store(StoreTaxRateRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_default'] = $request->boolean('is_default');

        TaxRate::create($validated);

        return redirect()->route('tax-rates.index')->with('status', 'Daňová sadzba bola úspešne vytvorená.');
    }

    /**
     * Show the form for editing the specified tax rate.
     */
    public function edit(TaxRate $taxRate): View
    {
        return view('tax-rates.edit', [
            'taxRate' => $taxRate,
        ]);
    }

    /**
     * Update the specified tax rate in storage.
     */
    public function update(UpdateTaxRateRequest $request, TaxRate $taxRate): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_default'] = $request->boolean('is_default');

        $taxRate->update($validated);

        return redirect()->route('tax-rates.index')->with('status', 'Daňová sadzba bola úspešne upravená.');
    }

    /**
     * Remove the specified tax rate from storage.
     */
    public function destroy(TaxRate $taxRate): RedirectResponse
    {
        $taxRate->delete();

        return redirect()->route('tax-rates.index')->with('status', 'Daňová sadzba bola úspešne odstránená.');
    }
}
