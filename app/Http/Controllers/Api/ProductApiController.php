<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class ProductApiController extends Controller
{
    /**
     * Display a listing of the active products.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $products = Product::query()
            ->where('is_active', true)
            ->when(
                $request->filled('category'),
                fn ($query) => $query->whereHas(
                    'category',
                    fn ($categoryQuery) => $categoryQuery->where('slug', $request->string('category'))
                )
            )
            ->when(
                $request->filled('brand'),
                fn ($query) => $query->whereHas(
                    'brand',
                    fn ($brandQuery) => $brandQuery->where('slug', $request->string('brand'))
                )
            )
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search');

                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->with(['category', 'brand'])
            ->latest()
            ->paginate($request->integer('per_page', 15))
            ->withQueryString();

        return ProductResource::collection($products);
    }

    /**
     * Display the specified active product.
     */
    public function show(Product $product): ProductResource
    {
        abort_unless($product->is_active, Response::HTTP_NOT_FOUND);

        return new ProductResource($product->load(['category', 'brand', 'variants']));
    }
}
