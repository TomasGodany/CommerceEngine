<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\StockItem;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WarehouseManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_warehouses_list(): void
    {
        $admin = User::factory()->admin()->create();
        Warehouse::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get(route('warehouses.index'));

        $response->assertOk();
    }

    public function test_admin_can_create_a_warehouse(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('warehouses.store'), [
            'name' => 'Testovací sklad',
            'code' => 'WH-TEST-001',
            'address' => 'Testovacia 1, Bratislava',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('warehouses.index'));

        $this->assertDatabaseHas('warehouses', [
            'name' => 'Testovací sklad',
            'code' => 'WH-TEST-001',
        ]);
    }

    public function test_admin_can_update_a_warehouse(): void
    {
        $admin = User::factory()->admin()->create();
        $warehouse = Warehouse::factory()->create(['name' => 'Pôvodný sklad']);

        $response = $this->actingAs($admin)->put(route('warehouses.update', $warehouse), [
            'name' => 'Upravený sklad',
            'code' => $warehouse->code,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('warehouses.index'));

        $this->assertDatabaseHas('warehouses', [
            'id' => $warehouse->id,
            'name' => 'Upravený sklad',
        ]);
    }

    public function test_admin_can_delete_a_warehouse(): void
    {
        $admin = User::factory()->admin()->create();
        $warehouse = Warehouse::factory()->create();

        $response = $this->actingAs($admin)->delete(route('warehouses.destroy', $warehouse));

        $response->assertRedirect(route('warehouses.index'));

        $this->assertDatabaseMissing('warehouses', ['id' => $warehouse->id]);
    }

    public function test_admin_can_create_an_inbound_stock_movement_and_quantity_increases(): void
    {
        $admin = User::factory()->admin()->create();
        $warehouse = Warehouse::factory()->create();
        $product = Product::factory()->create();
        $stockItem = StockItem::factory()->create([
            'warehouse_id' => $warehouse->id,
            'product_id' => $product->id,
            'product_variant_id' => null,
            'quantity' => 10,
        ]);

        $response = $this->actingAs($admin)->post(route('stock-movements.store'), [
            'warehouse_id' => $warehouse->id,
            'product_id' => $product->id,
            'type' => 'in',
            'quantity' => 5,
        ]);

        $response->assertRedirect(route('stock-movements.index'));

        $this->assertSame(15, $stockItem->fresh()->quantity);
        $this->assertDatabaseHas('stock_movements', [
            'warehouse_id' => $warehouse->id,
            'product_id' => $product->id,
            'type' => 'in',
            'quantity' => 5,
            'user_id' => $admin->id,
        ]);
    }

    public function test_admin_can_create_an_outbound_stock_movement_and_quantity_decreases(): void
    {
        $admin = User::factory()->admin()->create();
        $warehouse = Warehouse::factory()->create();
        $product = Product::factory()->create();
        $stockItem = StockItem::factory()->create([
            'warehouse_id' => $warehouse->id,
            'product_id' => $product->id,
            'product_variant_id' => null,
            'quantity' => 10,
        ]);

        $response = $this->actingAs($admin)->post(route('stock-movements.store'), [
            'warehouse_id' => $warehouse->id,
            'product_id' => $product->id,
            'type' => 'out',
            'quantity' => 4,
        ]);

        $response->assertRedirect(route('stock-movements.index'));

        $this->assertSame(6, $stockItem->fresh()->quantity);
    }

    public function test_outbound_stock_movement_cannot_result_in_negative_quantity(): void
    {
        $admin = User::factory()->admin()->create();
        $warehouse = Warehouse::factory()->create();
        $product = Product::factory()->create();
        $stockItem = StockItem::factory()->create([
            'warehouse_id' => $warehouse->id,
            'product_id' => $product->id,
            'product_variant_id' => null,
            'quantity' => 3,
        ]);

        $response = $this->actingAs($admin)->post(route('stock-movements.store'), [
            'warehouse_id' => $warehouse->id,
            'product_id' => $product->id,
            'type' => 'out',
            'quantity' => 10,
        ]);

        $response->assertSessionHasErrors('quantity');

        $this->assertSame(3, $stockItem->fresh()->quantity);
        $this->assertDatabaseCount('stock_movements', 0);
    }

    public function test_customer_cannot_access_warehouse_management(): void
    {
        $customer = User::factory()->create();

        $response = $this->actingAs($customer)->get(route('warehouses.index'));

        $response->assertForbidden();
    }

    public function test_guest_is_redirected_to_login_for_warehouses(): void
    {
        $response = $this->get(route('warehouses.index'));

        $response->assertRedirect(route('login'));
    }
}
