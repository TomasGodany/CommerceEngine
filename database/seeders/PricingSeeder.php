<?php

namespace Database\Seeders;

use App\Enums\DiscountType;
use App\Models\Coupon;
use App\Models\Discount;
use App\Models\PriceList;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PricingSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $retailPriceList = PriceList::firstOrCreate(
            ['code' => 'PL-RETAIL'],
            [
                'name' => 'Maloobchod',
                'description' => 'Základný cenník pre bežných koncových zákazníkov.',
                'is_default' => true,
                'is_active' => true,
            ]
        );

        $wholesalePriceList = PriceList::firstOrCreate(
            ['code' => 'PL-B2B'],
            [
                'name' => 'Veľkoobchod B2B',
                'description' => 'Zvýhodnený cenník pre firemných zákazníkov s množstevnými zľavami.',
                'is_default' => false,
                'is_active' => true,
            ]
        );

        $products = Product::all();

        foreach ($products as $product) {
            $retailPrice = (float) $product->price;

            $retailPriceList->items()->firstOrCreate(
                ['product_id' => $product->id],
                ['price' => $retailPrice]
            );

            $wholesalePriceList->items()->firstOrCreate(
                ['product_id' => $product->id],
                ['price' => round($retailPrice * 0.85, 2)]
            );
        }

        $firstProduct = $products->first();
        $firstCategory = $firstProduct?->category;

        collect([
            [
                'name' => 'Letná zľava na tričká',
                'type' => DiscountType::Percentage,
                'value' => 15,
                'starts_at' => now()->startOfMonth(),
                'ends_at' => now()->addMonths(2),
                'is_active' => true,
                'product_id' => null,
                'category_id' => $firstCategory?->id,
            ],
            [
                'name' => 'Zľava na vlajkový produkt',
                'type' => DiscountType::Fixed,
                'value' => 5,
                'starts_at' => null,
                'ends_at' => null,
                'is_active' => true,
                'product_id' => $firstProduct?->id,
                'category_id' => null,
            ],
            [
                'name' => 'Novoročná zľava',
                'type' => DiscountType::Percentage,
                'value' => 10,
                'starts_at' => now()->startOfYear(),
                'ends_at' => now()->startOfYear()->addDays(14),
                'is_active' => false,
                'product_id' => null,
                'category_id' => null,
            ],
            [
                'name' => 'Výpredaj skladu',
                'type' => DiscountType::Percentage,
                'value' => 25,
                'starts_at' => null,
                'ends_at' => now()->addWeeks(3),
                'is_active' => true,
                'product_id' => null,
                'category_id' => null,
            ],
        ])->each(fn (array $discount) => Discount::firstOrCreate(
            ['name' => $discount['name']],
            $discount
        ));

        collect([
            [
                'code' => 'VITAJTE10',
                'type' => DiscountType::Percentage,
                'value' => 10,
                'usage_limit' => 100,
                'used_count' => 0,
                'starts_at' => null,
                'ends_at' => null,
                'is_active' => true,
            ],
            [
                'code' => 'ZLAVA5EUR',
                'type' => DiscountType::Fixed,
                'value' => 5,
                'usage_limit' => 50,
                'used_count' => 0,
                'starts_at' => null,
                'ends_at' => null,
                'is_active' => true,
            ],
            [
                'code' => 'LETO2026',
                'type' => DiscountType::Percentage,
                'value' => 20,
                'usage_limit' => null,
                'used_count' => 0,
                'starts_at' => now()->startOfMonth(),
                'ends_at' => now()->addMonths(3),
                'is_active' => true,
            ],
            [
                'code' => 'VIP15',
                'type' => DiscountType::Percentage,
                'value' => 15,
                'usage_limit' => 20,
                'used_count' => 3,
                'starts_at' => null,
                'ends_at' => null,
                'is_active' => false,
            ],
        ])->each(fn (array $coupon) => Coupon::firstOrCreate(
            ['code' => $coupon['code']],
            $coupon
        ));
    }
}
