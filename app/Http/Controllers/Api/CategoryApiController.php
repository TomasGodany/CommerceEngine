<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class CategoryApiController extends Controller
{
    /**
     * Display a listing of the active categories.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->when($request->filled('search'), fn ($query) => $query->where('name', 'like', '%'.$request->string('search').'%'))
            ->with('parent')
            ->orderBy('position')
            ->paginate($request->integer('per_page', 15))
            ->withQueryString();

        return CategoryResource::collection($categories);
    }

    /**
     * Display the specified active category.
     */
    public function show(Category $category): CategoryResource
    {
        abort_unless($category->is_active, Response::HTTP_NOT_FOUND);

        return new CategoryResource($category->load('parent', 'children'));
    }
}
