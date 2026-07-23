<table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
    <tr>
        <td style="width: 50%; vertical-align: top;">
            <div style="font-size: 18px; font-weight: bold;">{{ $setting->company_name }}</div>
            <div>{{ $setting->company_street }}</div>
            <div>{{ $setting->company_city }}</div>
            <div>{{ $setting->company_country }}</div>
            <div>IČO: {{ $setting->ico }}</div>
            <div>DIČ: {{ $setting->dic }}</div>
            @if ($setting->ic_dph)
                <div>IČ DPH: {{ $setting->ic_dph }}</div>
            @endif
        </td>
        <td style="width: 50%; vertical-align: top; text-align: right;">
            <div style="font-size: 22px; font-weight: bold;">{{ $title }}</div>
            <div style="font-size: 14px; margin-top: 4px;">Číslo: {{ $document->document_number }}</div>
            <div>Dátum vystavenia: {{ $document->issued_at->format('d.m.Y') }}</div>
            <div>Objednávka: {{ $order->order_number }}</div>
        </td>
    </tr>
</table>
<hr style="border: none; border-top: 2px solid #1c1c1c; margin-bottom: 20px;">
