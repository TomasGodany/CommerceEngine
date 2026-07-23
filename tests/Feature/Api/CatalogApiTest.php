<?php

namespace Tests\Feature\Api;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_only_active_categories(): void
    {
        Category::factory()->count(2)->create(['is_active' => true]);
        Category::factory()->create(['is_active' => false]);

        $response = $this->getJson('/api/v1/categories');

        $response->assertOk();
        $response->assertJsonCount(2, 'data');
    }

    public function test_it_lists_only_active_brands(): void
    {
        Brand::factory()->count(3)->create(['is_active' => true]);
        Brand::factory()->create(['is_active' => false]);

        $response = $this->getJson('/api/v1/brands');

        $response->assertOk();
        $response->assertJsonCount(3, 'data');
    }

    public function test_it_lists_only_active_products(): void
    {
        Product::factory()->count(2)->create(['is_active' => true]);
        Product::factory()->create(['is_active' => false]);

        $response = $this->getJson('/api/v1/products');

        $response->assertOk();
        $response->assertJsonCount(2, 'data');
    }

    public function test_it_filters_products_by_category_slug(): void
    {
        $category = Category::factory()->create(['is_active' => true]);
        $otherCategory = Category::factory()->create(['is_active' => true]);

        Product::factory()->create(['category_id' => $category->id, 'is_active' => true]);
        Product::factory()->create(['category_id' => $otherCategory->id, 'is_active' => true]);

        $response = $this->getJson('/api/v1/products?category='.$category->slug);

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
    }

    public function test_it_shows_a_product_by_slug(): void
    {
        $product = Product::factory()->create(['is_active' => true]);

        $response = $this->getJson('/api/v1/products/'.$product->slug);

        $response->assertOk();
        $response->assertJsonPath('data.id', $product->id);
        $response->assertJsonPath('data.slug', $product->slug);
    }

    public function test_it_returns_not_found_for_an_inactive_product(): void
    {
        $product = Product::factory()->create(['is_active' => false]);

        $response = $this->getJson('/api/v1/products/'.$product->slug);

        $response->assertNotFound();
    }
}
