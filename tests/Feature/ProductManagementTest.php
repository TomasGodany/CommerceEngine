<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_products_list(): void
    {
        $admin = User::factory()->admin()->create();
        Product::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get(route('products.index'));

        $response->assertOk();
    }

    public function test_admin_can_create_a_product(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('products.store'), [
            'name' => 'Test produkt',
            'slug' => '',
            'sku' => 'TEST-001',
            'price' => 19.99,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('products', [
            'name' => 'Test produkt',
            'slug' => 'test-produkt',
            'sku' => 'TEST-001',
        ]);
    }

    public function test_admin_can_upload_an_image_when_creating_a_product(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('products.store'), [
            'name' => 'Tričko s obrázkom',
            'slug' => '',
            'sku' => 'TEST-IMG-001',
            'price' => 19.99,
            'is_active' => '1',
            'image' => File::image('product.jpg'),
        ]);

        $response->assertRedirect(route('products.index'));

        $product = Product::where('sku', 'TEST-IMG-001')->firstOrFail();

        $this->assertNotNull($product->image_path);
        Storage::disk('public')->assertExists($product->image_path);
    }

    public function test_admin_can_remove_a_product_image(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $imagePath = Storage::disk('public')->putFile('products', File::image('product.jpg'));
        $product = Product::factory()->create(['image_path' => $imagePath]);

        $response = $this->actingAs($admin)->put(route('products.update', $product), [
            'name' => $product->name,
            'slug' => $product->slug,
            'sku' => $product->sku,
            'price' => $product->price,
            'is_active' => '1',
            'remove_image' => '1',
        ]);

        $response->assertRedirect(route('products.index'));

        $this->assertNull($product->fresh()->image_path);
        Storage::disk('public')->assertMissing($imagePath);
    }

    public function test_admin_can_update_a_product(): void
    {
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create(['name' => 'Pôvodný názov']);

        $response = $this->actingAs($admin)->put(route('products.update', $product), [
            'name' => 'Upravený názov',
            'slug' => $product->slug,
            'sku' => $product->sku,
            'price' => 29.99,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Upravený názov',
        ]);
    }

    public function test_admin_can_delete_a_product(): void
    {
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($admin)->delete(route('products.destroy', $product));

        $response->assertRedirect(route('products.index'));

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_customer_cannot_access_products_management(): void
    {
        $customer = User::factory()->create();

        $response = $this->actingAs($customer)->get(route('products.index'));

        $response->assertForbidden();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('products.index'));

        $response->assertRedirect(route('login'));
    }
}
