<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Document;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DocumentManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_an_invoice_for_an_order(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = Customer::factory()->create();
        CustomerAddress::factory()->billing()->create(['customer_id' => $customer->id]);
        $order = Order::factory()->create(['customer_id' => $customer->id]);

        $response = $this->actingAs($admin)->post(route('orders.documents.store', $order), [
            'type' => 'invoice',
        ]);

        $response->assertRedirect(route('orders.show', $order));

        $this->assertDatabaseHas('documents', [
            'order_id' => $order->id,
            'type' => 'invoice',
        ]);

        $document = Document::where('order_id', $order->id)->firstOrFail();

        $this->assertStringStartsWith('FA-'.now()->year.'-', $document->document_number);
        $this->assertNotEmpty($document->file_path);
    }

    public function test_document_numbering_is_sequential_and_separated_by_type(): void
    {
        $admin = User::factory()->admin()->create();
        $orderOne = Order::factory()->create();
        $orderTwo = Order::factory()->create();
        $orderThree = Order::factory()->create();

        $this->actingAs($admin)->post(route('orders.documents.store', $orderOne), ['type' => 'invoice']);
        $this->actingAs($admin)->post(route('orders.documents.store', $orderTwo), ['type' => 'invoice']);
        $this->actingAs($admin)->post(route('orders.documents.store', $orderThree), ['type' => 'delivery_note']);

        $invoiceNumbers = Document::where('type', 'invoice')->orderBy('id')->pluck('document_number')->all();
        $deliveryNoteNumbers = Document::where('type', 'delivery_note')->pluck('document_number')->all();

        $this->assertSame(
            sprintf('FA-%d-0001', now()->year),
            $invoiceNumbers[0]
        );
        $this->assertSame(
            sprintf('FA-%d-0002', now()->year),
            $invoiceNumbers[1]
        );
        $this->assertSame(
            sprintf('DL-%d-0001', now()->year),
            $deliveryNoteNumbers[0]
        );
    }

    public function test_downloading_a_document_returns_a_pdf(): void
    {
        $admin = User::factory()->admin()->create();
        $order = Order::factory()->create();

        $this->actingAs($admin)->post(route('orders.documents.store', $order), ['type' => 'quote']);
        $document = Document::where('order_id', $order->id)->firstOrFail();

        $response = $this->actingAs($admin)->get(route('documents.show', $document));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_admin_can_delete_a_document(): void
    {
        $admin = User::factory()->admin()->create();
        $order = Order::factory()->create();

        $this->actingAs($admin)->post(route('orders.documents.store', $order), ['type' => 'invoice']);
        $document = Document::where('order_id', $order->id)->firstOrFail();

        $response = $this->actingAs($admin)->delete(route('documents.destroy', $document));

        $response->assertRedirect();
        $this->assertDatabaseMissing('documents', ['id' => $document->id]);
    }

    public function test_documents_list_can_be_filtered_by_type_and_searched(): void
    {
        $admin = User::factory()->admin()->create();
        $order = Order::factory()->create();

        $this->actingAs($admin)->post(route('orders.documents.store', $order), ['type' => 'invoice']);
        $document = Document::where('order_id', $order->id)->firstOrFail();

        $response = $this->actingAs($admin)->get(route('documents.index', ['type' => 'invoice', 'search' => $document->document_number]));

        $response->assertOk();
        $response->assertSee($document->document_number);
    }

    public function test_customer_cannot_access_document_routes(): void
    {
        $customer = User::factory()->create();
        $order = Order::factory()->create();

        $response = $this->actingAs($customer)->get(route('documents.index'));
        $response->assertForbidden();

        $response = $this->actingAs($customer)->post(route('orders.documents.store', $order), ['type' => 'invoice']);
        $response->assertForbidden();
    }

    public function test_guest_cannot_access_document_routes(): void
    {
        $response = $this->get(route('documents.index'));

        $response->assertRedirect(route('login'));
    }
}
