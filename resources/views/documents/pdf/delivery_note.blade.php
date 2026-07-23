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
        </style>
    </head>
    <body>
        @include('documents.pdf._header', ['title' => 'Dodací list'])

        <table style="width: 100%; margin-bottom: 20px;">
            <tr>
                <td style="width: 100%; vertical-align: top;">
                    <strong>Príjemca</strong><br>
                    @if ($data['customer'])
                        {{ $data['customer']['company_name'] ?? $data['customer']['full_name'] }}<br>
                        @foreach ($data['customer']['addresses'] as $address)
                            @if ($address['type'] === 'shipping')
                                {{ $address['street'] }}<br>
                                {{ $address['zip'] }} {{ $address['city'] }}<br>
                                {{ $address['country'] }}<br>
                            @endif
                        @endforeach
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
                </tr>
            </thead>
            <tbody>
                @foreach ($data['items'] as $item)
                    <tr>
                        <td>{{ $item['product_name'] }}</td>
                        <td class="text-right">{{ $item['quantity'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 60px;">
            Odovzdal: ____________________ &nbsp;&nbsp;&nbsp;&nbsp; Prevzal: ____________________
        </div>
    </body>
</html>
