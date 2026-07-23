<?php

namespace Database\Factories;

use App\Enums\DocumentType;
use App\Models\Document;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Document>
 */
class DocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(DocumentType::cases());

        return [
            'order_id' => Order::factory(),
            'type' => $type->value,
            'document_number' => sprintf('%s-%d-%04d', $type->prefix(), now()->year, fake()->unique()->numberBetween(1, 9999)),
            'data' => [],
            'file_path' => 'documents/'.fake()->uuid().'.pdf',
            'issued_at' => now()->toDateString(),
        ];
    }
}
