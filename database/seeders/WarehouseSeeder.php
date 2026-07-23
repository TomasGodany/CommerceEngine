<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\StockItem;
use App\Models\Warehouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehouse = Warehouse::firstOrCreate(
            ['code' => 'WH-01'],
            ['name' => 'Centrálny sklad', 'address' => 'Priemyselná 1, Bratislava', 'is_active' => true]
        );

        $products = Product::all();

        foreach ($products as $product) {
            $quantity = fake()->numberBetween(0, 200);

            StockItem::firstOrCreate(
                [
                    'warehouse_id' => $warehouse->id,
                    'product_id' => $product->id,
                    'product_variant_id' => null,
                ],
                [
                    'quantity' => $quantity,
                    'reserved_quantity' => fake()->numberBetween(0, min(20, $quantity)),
                ]
            );
        }
    }
}
