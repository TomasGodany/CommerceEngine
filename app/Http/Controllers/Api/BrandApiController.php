<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class BrandApiController extends Controller
{
    /**
     * Display a listing of the active brands.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $brands = Brand::query()
            ->where('is_active', true)
            ->when($request->filled('search'), fn ($query) => $query->where('name', 'like', '%'.$request->string('search').'%'))
            ->orderBy('name')
            ->paginate($request->integer('per_page', 15))
            ->withQueryString();

        return BrandResource::collection($brands);
    }

    /**
     * Display the specified active brand.
     */
    public function show(Brand $brand): BrandResource
    {
        abort_unless($brand->is_active, Response::HTTP_NOT_FOUND);

        return new BrandResource($brand);
    }
}
