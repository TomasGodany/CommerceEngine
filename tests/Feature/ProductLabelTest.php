<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductLabelTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_download_a_product_label_pdf(): void
    {
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create(['sku' => 'TEST-SKU-001']);

        $response = $this->actingAs($admin)->get(route('products.label', $product));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_customer_cannot_access_product_label(): void
    {
        $customer = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($customer)->get(route('products.label', $product));

        $response->assertForbidden();
    }

    public function test_guest_cannot_access_product_label(): void
    {
        $product = Product::factory()->create();

        $response = $this->get(route('products.label', $product));

        $response->assertRedirect(route('login'));
    }
}
