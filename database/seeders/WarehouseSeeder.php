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
        $warehouses = collect([
            ['name' => 'Centrálny sklad Bratislava', 'code' => 'WH-BA-01', 'address' => 'Priemyselná 1, Bratislava'],
            ['name' => 'Sklad Košice', 'code' => 'WH-KE-01', 'address' => 'Logistická 5, Košice'],
            ['name' => 'Sklad Žilina', 'code' => 'WH-ZA-01', 'address' => 'Skladová 12, Žilina'],
        ])->map(fn (array $item) => Warehouse::firstOrCreate(
            ['code' => $item['code']],
            ['name' => $item['name'], 'address' => $item['address'], 'is_active' => true]
        ));

        $products = Product::all();

        foreach ($warehouses as $warehouse) {
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
}
