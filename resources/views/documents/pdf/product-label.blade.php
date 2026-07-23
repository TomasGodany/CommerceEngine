<!DOCTYPE html>
<html lang="sk">
    <head>
        <meta charset="utf-8">
        <style>
            body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1c1c1c; text-align: center; margin: 0; padding: 8px; }
            .name { font-size: 13px; font-weight: bold; margin-bottom: 4px; }
            .price { font-size: 16px; font-weight: bold; margin: 6px 0; }
            .sku { font-size: 10px; margin-top: 4px; letter-spacing: 1px; }
        </style>
    </head>
    <body>
        <div class="name">{{ $product->name }}</div>
        <div class="price">{{ number_format((float) ($product->sale_price ?? $product->price), 2) }} €</div>

        <div>{!! QrCode::size(120)->generate($product->sku) !!}</div>

        <div class="sku">SKU: {{ $product->sku }}</div>
    </body>
</html>
