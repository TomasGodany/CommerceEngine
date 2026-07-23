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
        @include('documents.pdf._header', ['title' => 'Faktúra'])

        <table style="width: 100%; margin-bottom: 20px;">
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    <strong>Odberateľ</strong><br>
                    @if ($data['customer'])
                        {{ $data['customer']['company_name'] ?? $data['customer']['full_name'] }}<br>
                        @foreach ($data['customer']['addresses'] as $address)
                            @if ($address['type'] === 'billing')
                                {{ $address['street'] }}<br>
                                {{ $address['zip'] }} {{ $address['city'] }}<br>
                                {{ $address['country'] }}<br>
                            @endif
                        @endforeach
                        @if ($data['customer']['ico'])
                            IČO: {{ $data['customer']['ico'] }}<br>
                        @endif
                        @if ($data['customer']['dic'])
                            DIČ: {{ $data['customer']['dic'] }}<br>
                        @endif
                        {{ $data['customer']['email'] }}
                    @else
                        —
                    @endif
                </td>
                <td style="width: 50%; vertical-align: top; text-align: right;">
                    <strong>Platobné údaje</strong><br>
                    IBAN: {{ config('company.iban') }}<br>
                    BIC: {{ config('company.bic') }}<br>
                    Variabilný symbol: {{ $order->order_number }}<br>
                    Suma na úhradu: {{ number_format($data['total_amount'], 2) }} {{ $data['currency'] }}
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
                    <td colspan="3" class="text-right">Celková suma na úhradu</td>
                    <td class="text-right">{{ number_format($data['total_amount'], 2) }} {{ $data['currency'] }}</td>
                </tr>
            </tbody>
        </table>

        <table style="width: 100%; margin-top: 30px;">
            <tr>
                <td style="width: 70%; vertical-align: top;"></td>
                <td style="width: 30%; text-align: center;">
                    {!! QrCode::size(140)->generate(
                        'SPD*1.0*ACC:'.config('company.iban').'*AM:'.number_format($data['total_amount'], 2, '.', '').'*CC:'.$data['currency'].'*X-VS:'.preg_replace('/\D/', '', $order->order_number)
                    ) !!}
                    <div style="font-size: 10px; margin-top: 4px;">QR platba</div>
                </td>
            </tr>
        </table>
    </body>
</html>
