<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();

        if ($products->isEmpty()) {
            return;
        }

        $customers = Customer::all();
        $admin = User::where('role', 'admin')->first();

        $orders = [
            ['status' => OrderStatus::New, 'payment_status' => PaymentStatus::Unpaid, 'note' => null],
            ['status' => OrderStatus::Processing, 'payment_status' => PaymentStatus::Unpaid, 'note' => 'Čaká sa na sklad.'],
            ['status' => OrderStatus::Paid, 'payment_status' => PaymentStatus::Paid, 'note' => 'Platba prijatá bankovým prevodom.'],
            ['status' => OrderStatus::Shipped, 'payment_status' => PaymentStatus::Paid, 'note' => 'Zásielka odovzdaná kuriérovi.'],
            ['status' => OrderStatus::Completed, 'payment_status' => PaymentStatus::Paid, 'note' => 'Zákazník potvrdil prevzatie.'],
            ['status' => OrderStatus::Cancelled, 'payment_status' => PaymentStatus::Unpaid, 'note' => 'Zákazník objednávku zrušil.'],
            ['status' => OrderStatus::Returned, 'payment_status' => PaymentStatus::Refunded, 'note' => 'Tovar bol vrátený, peniaze vrátené.'],
            ['status' => OrderStatus::Processing, 'payment_status' => PaymentStatus::Paid, 'note' => 'Pripravuje sa na expedíciu.'],
        ];

        foreach ($orders as $data) {
            $orderNumber = 'ORD-'.strtoupper(Str::random(8));

            if (Order::where('order_number', $orderNumber)->exists()) {
                continue;
            }

            $order = Order::create([
                'order_number' => $orderNumber,
                'customer_id' => $customers->isNotEmpty() ? $customers->random()->id : null,
                'status' => OrderStatus::New,
                'payment_status' => $data['payment_status'],
                'total_amount' => 0,
                'currency' => 'EUR',
                'notes' => null,
            ]);

            $itemsCount = min(random_int(1, 3), $products->count());
            $totalAmount = 0;

            foreach ($products->random($itemsCount) as $product) {
                $quantity = random_int(1, 3);
                $unitPrice = (float) $product->price;
                $totalPrice = $unitPrice * $quantity;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                ]);

                $totalAmount += $totalPrice;
            }

            $order->update(['total_amount' => $totalAmount]);

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => OrderStatus::New,
                'note' => 'Objednávka bola vytvorená.',
                'user_id' => $admin?->id,
            ]);

            if ($data['status'] !== OrderStatus::New) {
                $order->update(['status' => $data['status']]);

                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'status' => $data['status'],
                    'note' => $data['note'],
                    'user_id' => $admin?->id,
                ]);
            }
        }
    }
}
