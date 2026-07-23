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
        <label for="type" class="block text-sm mb-1">Typ pohybu</label>
        <select id="type" name="type" required
            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
            <option value="">— vyberte typ —</option>
            @foreach (['in' => 'Príjem', 'out' => 'Výdaj', 'transfer' => 'Presun', 'adjustment' => 'Úprava stavu'] as $value => $label)
                <option value="{{ $value }}" @selected(old('type') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="product_id" class="block text-sm mb-1">Produkt</label>
        <select id="product_id" name="product_id" required
            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
            <option value="">— vyberte produkt —</option>
            @foreach ($products as $product)
                <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>
                    {{ $product->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="product_variant_id" class="block text-sm mb-1">Variant (nepovinné)</label>
        <select id="product_variant_id" name="product_variant_id"
            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
            <option value="">— žiadny —</option>
            @foreach ($products as $product)
                @foreach ($product->variants as $variant)
                    <option value="{{ $variant->id }}" @selected(old('product_variant_id') == $variant->id)>
                        {{ $product->name }} – {{ $variant->name ?? $variant->sku }}
                    </option>
                @endforeach
            @endforeach
        </select>
    </div>

    <div>
        <label for="quantity" class="block text-sm mb-1">Množstvo</label>
        <input id="quantity" type="number" step="1" min="1" name="quantity" value="{{ old('quantity') }}" required
            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
    </div>
</div>

<div class="mt-4">
    <label for="note" class="block text-sm mb-1">Poznámka</label>
    <textarea id="note" name="note" rows="3"
        class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">{{ old('note') }}</textarea>
</div>
