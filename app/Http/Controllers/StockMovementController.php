<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStockMovementRequest;
use App\Models\Product;
use App\Models\StockItem;
use App\Models\StockMovement;
use App\Models\Warehouse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StockMovementController extends Controller
{
    /**
     * Display a listing of the stock movements.
     */
    public function index(Request $request): View
    {
        $movements = StockMovement::query()
            ->with(['warehouse', 'product', 'productVariant', 'user'])
            ->when($request->filled('warehouse_id'), fn ($query) => $query->where('warehouse_id', $request->integer('warehouse_id')))
            ->when($request->filled('product_id'), fn ($query) => $query->where('product_id', $request->integer('product_id')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $stockItems = StockItem::with(['warehouse', 'product', 'productVariant'])
            ->orderBy('warehouse_id')
            ->get();

        return view('stock-movements.index', [
            'movements' => $movements,
            'stockItems' => $stockItems,
            'warehouses' => Warehouse::orderBy('name')->get(),
            'products' => Product::orderBy('name')->get(),
        ]);
    }

    /**
     * Show the form for creating a new stock movement.
     */
    public function create(): View
    {
        return view('stock-movements.create', [
            'warehouses' => Warehouse::orderBy('name')->get(),
            'products' => Product::with('variants')->orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created stock movement in storage, adjusting the stock item quantity accordingly.
     */
    public function store(StoreStockMovementRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['product_variant_id'] = $validated['product_variant_id'] ?? null;

        $stockItem = StockItem::firstOrNew([
            'warehouse_id' => $validated['warehouse_id'],
            'product_id' => $validated['product_id'],
            'product_variant_id' => $validated['product_variant_id'],
        ]);

        $newQuantity = match ($validated['type']) {
            'in' => $stockItem->quantity + $validated['quantity'],
            'out', 'transfer' => $stockItem->quantity - $validated['quantity'],
            'adjustment' => $validated['quantity'],
            default => $stockItem->quantity,
        };

        if ($newQuantity < 0) {
            return back()->withInput()->withErrors([
                'quantity' => 'Na sklade nie je dostatočné množstvo pre tento pohyb.',
            ]);
        }

        DB::transaction(function () use ($stockItem, $newQuantity, $validated, $request) {
            $stockItem->quantity = $newQuantity;
            $stockItem->save();

            StockMovement::create([
                ...$validated,
                'user_id' => $request->user()->id,
            ]);
        });

        return redirect()->route('stock-movements.index')->with('status', 'Skladový pohyb bol úspešne zaznamenaný.');
    }
}
