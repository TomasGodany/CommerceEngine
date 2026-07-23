<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'first_name' => 'Peter',
                'last_name' => 'Novák',
                'email' => 'peter.novak@example.com',
                'phone' => '0901123456',
                'is_company' => false,
                'notes' => 'Preferuje doručenie na adresu domov.',
                'addresses' => [
                    ['type' => 'shipping', 'street' => 'Hlavná 12', 'city' => 'Bratislava', 'zip' => '81101', 'is_default' => true],
                ],
            ],
            [
                'first_name' => 'Jana',
                'last_name' => 'Horváthová',
                'email' => 'jana.horvathova@example.com',
                'phone' => '0902234567',
                'is_company' => false,
                'notes' => null,
                'addresses' => [
                    ['type' => 'shipping', 'street' => 'Košická 45', 'city' => 'Košice', 'zip' => '04001', 'is_default' => true],
                ],
            ],
            [
                'first_name' => 'Martin',
                'last_name' => 'Kováč',
                'email' => 'martin.kovac@example.com',
                'phone' => '0903345678',
                'is_company' => false,
                'notes' => 'Zákazník má záujem o veľkoobchodné ceny.',
                'addresses' => [
                    ['type' => 'shipping', 'street' => 'Nová 3', 'city' => 'Žilina', 'zip' => '01001', 'is_default' => true],
                    ['type' => 'billing', 'street' => 'Fakturačná 3', 'city' => 'Žilina', 'zip' => '01001', 'is_default' => false],
                ],
            ],
            [
                'first_name' => 'Lucia',
                'last_name' => 'Baránková',
                'email' => 'lucia.barankova@example.com',
                'phone' => '0904456789',
                'is_company' => false,
                'notes' => null,
                'addresses' => [
                    ['type' => 'shipping', 'street' => 'Slnečná 8', 'city' => 'Nitra', 'zip' => '94901', 'is_default' => true],
                ],
            ],
            [
                'first_name' => 'Ondrej',
                'last_name' => 'Varga',
                'company_name' => 'Varga Trade s.r.o.',
                'ico' => '12345671',
                'dic' => 'SK2012345671',
                'email' => 'info@vargatrade.sk',
                'phone' => '0905567890',
                'is_company' => true,
                'notes' => 'Firemný odberateľ, faktúry na firemné IČO.',
                'addresses' => [
                    ['type' => 'billing', 'street' => 'Priemyselná 21', 'city' => 'Trnava', 'zip' => '91701', 'is_default' => true],
                    ['type' => 'shipping', 'street' => 'Skladová 4', 'city' => 'Trnava', 'zip' => '91701', 'is_default' => false],
                ],
            ],
            [
                'first_name' => 'Zuzana',
                'last_name' => 'Kollárová',
                'company_name' => 'Kollár Design s.r.o.',
                'ico' => '23456782',
                'dic' => 'SK2023456782',
                'email' => 'obchod@kollardesign.sk',
                'phone' => '0906678901',
                'is_company' => true,
                'notes' => null,
                'addresses' => [
                    ['type' => 'billing', 'street' => 'Dizajnová 9', 'city' => 'Bratislava', 'zip' => '82108', 'is_default' => true],
                ],
            ],
            [
                'first_name' => 'Tomáš',
                'last_name' => 'Poliak',
                'email' => 'tomas.poliak@example.com',
                'phone' => '0907789012',
                'is_company' => false,
                'notes' => null,
                'addresses' => [
                    ['type' => 'shipping', 'street' => 'Lesná 17', 'city' => 'Prešov', 'zip' => '08001', 'is_default' => true],
                ],
            ],
            [
                'first_name' => 'Katarína',
                'last_name' => 'Šimková',
                'company_name' => 'Šimko Logistika a.s.',
                'ico' => '34567893',
                'dic' => 'SK2034567893',
                'email' => 'kontakt@simkologistika.sk',
                'phone' => '0908890123',
                'is_company' => true,
                'notes' => 'Odberateľ s vlastnou dopravou.',
                'addresses' => [
                    ['type' => 'billing', 'street' => 'Logistická 2', 'city' => 'Zvolen', 'zip' => '96001', 'is_default' => true],
                    ['type' => 'shipping', 'street' => 'Prekladisková 6', 'city' => 'Zvolen', 'zip' => '96001', 'is_default' => false],
                ],
            ],
            [
                'first_name' => 'Michal',
                'last_name' => 'Ďurica',
                'email' => 'michal.durica@example.com',
                'phone' => '0909901234',
                'is_company' => false,
                'notes' => null,
                'addresses' => [
                    ['type' => 'shipping', 'street' => 'Záhradná 5', 'city' => 'Banská Bystrica', 'zip' => '97401', 'is_default' => true],
                ],
            ],
            [
                'first_name' => 'Eva',
                'last_name' => 'Krajčová',
                'email' => 'eva.krajcova@example.com',
                'phone' => '0900112233',
                'is_company' => false,
                'notes' => 'Stály zákazník, uprednostňuje e-mailovú komunikáciu.',
                'addresses' => [
                    ['type' => 'shipping', 'street' => 'Rybárska 14', 'city' => 'Trenčín', 'zip' => '91101', 'is_default' => true],
                ],
            ],
        ];

        foreach ($customers as $item) {
            $addresses = $item['addresses'];
            unset($item['addresses']);

            $item['company_name'] ??= null;
            $item['ico'] ??= null;
            $item['dic'] ??= null;
            $item['notes'] ??= null;

            $customer = Customer::firstOrCreate(
                ['email' => $item['email']],
                $item
            );

            foreach ($addresses as $address) {
                $customer->addresses()->firstOrCreate(
                    ['type' => $address['type'], 'street' => $address['street']],
                    [
                        'city' => $address['city'],
                        'zip' => $address['zip'],
                        'country' => 'SK',
                        'is_default' => $address['is_default'],
                    ]
                );
            }
        }
    }
}
