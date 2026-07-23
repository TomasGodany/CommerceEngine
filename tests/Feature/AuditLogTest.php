<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\AuditLog;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_a_product_creates_an_audit_log_entry(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('products.store'), [
            'name' => 'Audit produkt',
            'slug' => '',
            'sku' => 'AUDIT-001',
            'price' => 9.99,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('products.index'));

        $product = Product::where('sku', 'AUDIT-001')->firstOrFail();

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $admin->id,
            'action' => 'created',
            'auditable_type' => Product::class,
            'auditable_id' => $product->id,
        ]);
    }

    public function test_updating_a_product_creates_an_audit_log_entry(): void
    {
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create(['name' => 'Pôvodný názov']);

        $response = $this->actingAs($admin)->put(route('products.update', $product), [
            'name' => 'Nový názov',
            'slug' => $product->slug,
            'sku' => $product->sku,
            'price' => $product->price,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $admin->id,
            'action' => 'updated',
            'auditable_type' => Product::class,
            'auditable_id' => $product->id,
        ]);
    }

    public function test_deleting_a_product_creates_an_audit_log_entry(): void
    {
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($admin)->delete(route('products.destroy', $product));

        $response->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $admin->id,
            'action' => 'deleted',
            'auditable_type' => Product::class,
            'auditable_id' => $product->id,
        ]);
    }

    public function test_admin_can_view_audit_logs_list(): void
    {
        $admin = User::factory()->admin()->create();
        AuditLog::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get(route('audit-logs.index'));

        $response->assertOk();
    }

    public function test_admin_can_filter_audit_logs_by_user(): void
    {
        $admin = User::factory()->admin()->create();
        $otherUser = User::factory()->create();

        AuditLog::factory()->create(['user_id' => $admin->id]);
        AuditLog::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($admin)->get(route('audit-logs.index', ['user_id' => $otherUser->id]));

        $response->assertOk();
        $response->assertViewHas('auditLogs', function ($auditLogs) use ($otherUser) {
            return $auditLogs->every(fn (AuditLog $auditLog) => $auditLog->user_id === $otherUser->id);
        });
    }

    public function test_manager_cannot_access_audit_logs(): void
    {
        $manager = User::factory()->create(['role' => UserRole::Manager]);

        $response = $this->actingAs($manager)->get(route('audit-logs.index'));

        $response->assertForbidden();
    }

    public function test_customer_cannot_access_audit_logs(): void
    {
        $customer = User::factory()->create(['role' => UserRole::Customer]);

        $response = $this->actingAs($customer)->get(route('audit-logs.index'));

        $response->assertForbidden();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('audit-logs.index'));

        $response->assertRedirect(route('login'));
    }
}
