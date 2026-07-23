<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWarehouseRequest;
use App\Http\Requests\UpdateWarehouseRequest;
use App\Models\Warehouse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the warehouses.
     */
    public function index(): View
    {
        return view('warehouses.index', [
            'warehouses' => Warehouse::latest()->paginate(15),
        ]);
    }

    /**
     * Show the form for creating a new warehouse.
     */
    public function create(): View
    {
        return view('warehouses.create');
    }

    /**
     * Store a newly created warehouse in storage.
     */
    public function store(StoreWarehouseRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->boolean('is_active');

        Warehouse::create($validated);

        return redirect()->route('warehouses.index')->with('status', 'Sklad bol úspešne vytvorený.');
    }

    /**
     * Show the form for editing the specified warehouse.
     */
    public function edit(Warehouse $warehouse): View
    {
        return view('warehouses.edit', [
            'warehouse' => $warehouse,
        ]);
    }

    /**
     * Update the specified warehouse in storage.
     */
    public function update(UpdateWarehouseRequest $request, Warehouse $warehouse): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->boolean('is_active');

        $warehouse->update($validated);

        return redirect()->route('warehouses.index')->with('status', 'Sklad bol úspešne upravený.');
    }

    /**
     * Remove the specified warehouse from storage.
     */
    public function destroy(Warehouse $warehouse): RedirectResponse
    {
        $warehouse->delete();

        return redirect()->route('warehouses.index')->with('status', 'Sklad bol úspešne odstránený.');
    }
}
