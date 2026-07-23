<table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
    <tr>
        <td style="width: 50%; vertical-align: top;">
            <div style="font-size: 18px; font-weight: bold;">{{ config('company.name') }}</div>
            <div>{{ config('company.street') }}</div>
            <div>{{ config('company.city') }}</div>
            <div>{{ config('company.country') }}</div>
            <div>IČO: {{ config('company.ico') }}</div>
            <div>DIČ: {{ config('company.dic') }}</div>
            <div>IČ DPH: {{ config('company.ic_dph') }}</div>
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
