<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\Response;

class ProductLabelController extends Controller
{
    /**
     * Generate and download the printable label PDF for the given product.
     */
    public function show(Product $product): Response
    {
        $pdf = Pdf::loadView('documents.pdf.product-label', [
            'product' => $product,
        ])->setPaper([0, 0, 226, 340]);

        return $pdf->download('stitok-'.$product->sku.'.pdf');
    }
}
