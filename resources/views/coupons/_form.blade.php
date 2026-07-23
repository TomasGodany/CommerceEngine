@php
    $coupon = $coupon ?? null;
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
        <label for="code" class="block text-sm mb-1">Kód kupónu</label>
        <input id="code" type="text" name="code" value="{{ old('code', $coupon?->code) }}" required
            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
    </div>

    <div>
        <label for="type" class="block text-sm mb-1">Typ zľavy</label>
        <select id="type" name="type" required
            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
            @foreach (\App\Enums\DiscountType::cases() as $type)
                <option value="{{ $type->value }}" @selected(old('type', $coupon?->type?->value) === $type->value)>
                    {{ $type === \App\Enums\DiscountType::Percentage ? 'Percentuálna' : 'Fixná' }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="value" class="block text-sm mb-1">Hodnota</label>
        <input id="value" type="number" step="0.01" min="0" name="value" value="{{ old('value', $coupon?->value) }}" required
            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
    </div>

    <div>
        <label for="usage_limit" class="block text-sm mb-1">Limit použití</label>
        <input id="usage_limit" type="number" min="1" name="usage_limit" value="{{ old('usage_limit', $coupon?->usage_limit) }}"
            placeholder="ponechajte prázdne pre neobmedzené použitie"
            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
    <div>
        <label for="starts_at" class="block text-sm mb-1">Platnosť od</label>
        <input id="starts_at" type="date" name="starts_at" value="{{ old('starts_at', $coupon?->starts_at?->format('Y-m-d')) }}"
            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
    </div>

    <div>
        <label for="ends_at" class="block text-sm mb-1">Platnosť do</label>
        <input id="ends_at" type="date" name="ends_at" value="{{ old('ends_at', $coupon?->ends_at?->format('Y-m-d')) }}"
            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
    </div>
</div>

<div class="flex items-center gap-2 mt-4">
    <input id="is_active" type="checkbox" name="is_active" value="1" @checked(old('is_active', $coupon?->is_active ?? true))>
    <label for="is_active" class="text-sm">Kupón je aktívny</label>
</div>
