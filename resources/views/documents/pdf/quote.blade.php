<!DOCTYPE html>
<html lang="sk">
    <head>
        <meta charset="utf-8">
        <style>
            body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1c1c1c; }
            table.items { width: 100%; border-collapse: collapse; margin-top: 10px; }
            table.items th, table.items td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
            table.items th { background: #f4f4f2; }
            .text-right { text-align: right; }
            .total-row td { font-weight: bold; }
        </style>
    </head>
    <body>
        @include('documents.pdf._header', ['title' => 'Cenová ponuka'])

        <table style="width: 100%; margin-bottom: 20px;">
            <tr>
                <td style="width: 100%; vertical-align: top;">
                    <strong>Pre</strong><br>
                    @if ($data['customer'])
                        {{ $data['customer']['company_name'] ?? $data['customer']['full_name'] }}<br>
                        {{ $data['customer']['email'] }}
                    @else
                        —
                    @endif
                </td>
            </tr>
        </table>

        <table class="items">
            <thead>
                <tr>
                    <th>Produkt</th>
                    <th class="text-right">Množstvo</th>
                    <th class="text-right">Jedn. cena</th>
                    <th class="text-right">Spolu</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['items'] as $item)
                    <tr>
                        <td>{{ $item['product_name'] }}</td>
                        <td class="text-right">{{ $item['quantity'] }}</td>
                        <td class="text-right">{{ number_format($item['unit_price'], 2) }} {{ $data['currency'] }}</td>
                        <td class="text-right">{{ number_format($item['total_price'], 2) }} {{ $data['currency'] }}</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="3" class="text-right">Celková suma</td>
                    <td class="text-right">{{ number_format($data['total_amount'], 2) }} {{ $data['currency'] }}</td>
                </tr>
            </tbody>
        </table>

        <div style="margin-top: 20px; font-size: 11px;">
            Táto cenová ponuka je nezáväzná a slúži len na informačné účely.
        </div>
    </body>
</html>
