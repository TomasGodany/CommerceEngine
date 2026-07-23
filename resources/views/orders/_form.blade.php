@php
    $order = $order ?? null;
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
        <label for="customer_id" class="block text-sm mb-1">Zákazník</label>
        <select id="customer_id" name="customer_id"
            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
            <option value="">— bez zákazníka —</option>
            @foreach ($customers as $customer)
                <option value="{{ $customer->id }}" @selected(old('customer_id', $order?->customer_id) == $customer->id)>
                    {{ $customer->full_name }}{{ $customer->company_name ? ' — '.$customer->company_name : '' }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="currency" class="block text-sm mb-1">Mena</label>
        <input id="currency" type="text" name="currency" value="{{ old('currency', $order?->currency ?? 'EUR') }}" required
            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
    </div>
</div>

<div class="mt-4">
    <label for="notes" class="block text-sm mb-1">Poznámka</label>
    <textarea id="notes" name="notes" rows="3"
        class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">{{ old('notes', $order?->notes) }}</textarea>
</div>
