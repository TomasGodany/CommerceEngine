<?php

namespace App\Http\Controllers;

use App\Enums\DocumentType;
use App\Models\Document;
use App\Models\Order;
use App\Models\Setting;
use App\Services\DocumentNumberGenerator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DocumentController extends Controller
{
    /**
     * Display a listing of the documents.
     */
    public function index(Request $request): View
    {
        $query = Document::with('order.customer')->latest();

        if ($request->filled('type')) {
            $query->where('type', $request->string('type'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();

            $query->where(function ($inner) use ($search) {
                $inner->where('document_number', 'like', "%{$search}%")
                    ->orWhereHas('order', function ($orderQuery) use ($search) {
                        $orderQuery->where('order_number', 'like', "%{$search}%");
                    });
            });
        }

        return view('documents.index', [
            'documents' => $query->paginate(15)->withQueryString(),
            'types' => DocumentType::cases(),
            'selectedType' => $request->string('type')->toString(),
            'search' => $request->string('search')->toString(),
        ]);
    }

    /**
     * Store a newly created document for the given order.
     */
    public function store(Request $request, Order $order, DocumentNumberGenerator $numberGenerator): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'in:'.implode(',', array_column(DocumentType::cases(), 'value'))],
        ]);

        $type = DocumentType::from($validated['type']);

        $order->load(['customer.addresses', 'items']);

        $documentNumber = $numberGenerator->generate($type);

        $data = [
            'order_number' => $order->order_number,
            'currency' => $order->currency,
            'total_amount' => (float) $order->total_amount,
            'customer' => $order->customer ? [
                'full_name' => $order->customer->full_name,
                'company_name' => $order->customer->company_name,
                'ico' => $order->customer->ico,
                'dic' => $order->customer->dic,
                'email' => $order->customer->email,
                'phone' => $order->customer->phone,
                'addresses' => $order->customer->addresses->map(fn ($address) => [
                    'type' => $address->type,
                    'street' => $address->street,
                    'city' => $address->city,
                    'zip' => $address->zip,
                    'country' => $address->country,
                ])->all(),
            ] : null,
            'items' => $order->items->map(fn ($item) => [
                'product_name' => $item->product_name,
                'quantity' => $item->quantity,
                'unit_price' => (float) $item->unit_price,
                'total_price' => (float) $item->total_price,
            ])->all(),
        ];

        $document = Document::create([
            'order_id' => $order->id,
            'type' => $type->value,
            'document_number' => $documentNumber,
            'data' => $data,
            'file_path' => '',
            'issued_at' => now()->toDateString(),
        ]);

        $pdf = Pdf::loadView('documents.pdf.'.$type->value, [
            'document' => $document,
            'order' => $order,
            'data' => $data,
            'setting' => Setting::current(),
        ]);

        $filePath = 'documents/'.$document->document_number.'.pdf';
        Storage::disk('public')->put($filePath, $pdf->output());

        $document->update(['file_path' => $filePath]);

        return redirect()->route('orders.show', $order)->with('status', $type->label().' bola úspešne vytvorená.');
    }

    /**
     * Display/download the specified document as a PDF.
     */
    public function show(Document $document): Response
    {
        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            return response(Storage::disk('public')->get($document->file_path), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$document->document_number.'.pdf"',
            ]);
        }

        $pdf = Pdf::loadView('documents.pdf.'.$document->type->value, [
            'document' => $document,
            'order' => $document->order,
            'data' => $document->data,
            'setting' => Setting::current(),
        ]);

        return $pdf->stream($document->document_number.'.pdf');
    }

    /**
     * Remove the specified document from storage.
     */
    public function destroy(Document $document): RedirectResponse
    {
        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $orderId = $document->order_id;

        $document->delete();

        return redirect()->route('orders.show', $orderId)->with('status', 'Dokument bol úspešne odstránený.');
    }
}
