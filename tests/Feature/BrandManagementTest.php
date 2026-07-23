<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BrandManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_brands_list(): void
    {
        $admin = User::factory()->admin()->create();
        Brand::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get(route('brands.index'));

        $response->assertOk();
    }

    public function test_admin_can_create_a_brand(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('brands.store'), [
            'name' => 'Testovacia značka',
            'slug' => '',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('brands.index'));

        $this->assertDatabaseHas('brands', [
            'name' => 'Testovacia značka',
            'slug' => 'testovacia-znacka',
        ]);
    }

    public function test_admin_can_upload_a_logo_when_creating_a_brand(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('brands.store'), [
            'name' => 'Značka s logom',
            'slug' => '',
            'is_active' => '1',
            'logo' => File::image('logo.jpg'),
        ]);

        $response->assertRedirect(route('brands.index'));

        $brand = Brand::where('name', 'Značka s logom')->firstOrFail();

        $this->assertNotNull($brand->logo_path);
        Storage::disk('public')->assertExists($brand->logo_path);
    }

    public function test_admin_can_update_a_brand(): void
    {
        $admin = User::factory()->admin()->create();
        $brand = Brand::factory()->create(['name' => 'Pôvodný názov']);

        $response = $this->actingAs($admin)->put(route('brands.update', $brand), [
            'name' => 'Upravený názov',
            'slug' => $brand->slug,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('brands.index'));

        $this->assertDatabaseHas('brands', [
            'id' => $brand->id,
            'name' => 'Upravený názov',
        ]);
    }

    public function test_admin_can_delete_a_brand(): void
    {
        $admin = User::factory()->admin()->create();
        $brand = Brand::factory()->create();

        $response = $this->actingAs($admin)->delete(route('brands.destroy', $brand));

        $response->assertRedirect(route('brands.index'));

        $this->assertDatabaseMissing('brands', ['id' => $brand->id]);
    }

    public function test_customer_cannot_access_brands_management(): void
    {
        $customer = User::factory()->create();

        $response = $this->actingAs($customer)->get(route('brands.index'));

        $response->assertForbidden();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('brands.index'));

        $response->assertRedirect(route('login'));
    }
}
