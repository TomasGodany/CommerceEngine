@php
    $currency = $currency ?? null;
@endphp

@if ($errors->any())
    <div class="mb-4 text-sm text-red-400 bg-[#1c1c1c] border border-[#2e2e2e] rounded px-3 py-2">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label for="code" class="block text-sm mb-1">Kód meny</label>
        <input id="code" type="text" name="code" value="{{ old('code', $currency?->code) }}" required
            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
    </div>

    <div>
        <label for="symbol" class="block text-sm mb-1">Symbol</label>
        <input id="symbol" type="text" name="symbol" value="{{ old('symbol', $currency?->symbol) }}" required
            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
    </div>
</div>

<div class="mt-4">
    <label for="exchange_rate" class="block text-sm mb-1">Výmenný kurz</label>
    <input id="exchange_rate" type="number" step="0.000001" min="0" name="exchange_rate" value="{{ old('exchange_rate', $currency?->exchange_rate) }}" required
        class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
</div>

<div class="flex items-center gap-2 mt-4">
    <input id="is_default" type="checkbox" name="is_default" value="1" @checked(old('is_default', $currency?->is_default ?? false))>
    <label for="is_default" class="text-sm">Predvolená mena</label>
</div>
