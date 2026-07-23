@php
    $customer = $customer ?? null;
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
        <label for="first_name" class="block text-sm mb-1">Meno</label>
        <input id="first_name" type="text" name="first_name" value="{{ old('first_name', $customer?->first_name) }}" required
            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
    </div>

    <div>
        <label for="last_name" class="block text-sm mb-1">Priezvisko</label>
        <input id="last_name" type="text" name="last_name" value="{{ old('last_name', $customer?->last_name) }}" required
            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
    </div>

    <div>
        <label for="email" class="block text-sm mb-1">E-mail</label>
        <input id="email" type="email" name="email" value="{{ old('email', $customer?->email) }}" required
            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
    </div>

    <div>
        <label for="phone" class="block text-sm mb-1">Telefón</label>
        <input id="phone" type="text" name="phone" value="{{ old('phone', $customer?->phone) }}"
            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
    </div>
</div>

<div class="flex items-center gap-2 mt-4">
    <input id="is_company" type="checkbox" name="is_company" value="1" @checked(old('is_company', $customer?->is_company ?? false))>
    <label for="is_company" class="text-sm">Zákazník je firma</label>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
    <div>
        <label for="company_name" class="block text-sm mb-1">Názov firmy</label>
        <input id="company_name" type="text" name="company_name" value="{{ old('company_name', $customer?->company_name) }}"
            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
    </div>

    <div>
        <label for="ico" class="block text-sm mb-1">IČO</label>
        <input id="ico" type="text" name="ico" value="{{ old('ico', $customer?->ico) }}"
            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
    </div>

    <div>
        <label for="dic" class="block text-sm mb-1">DIČ</label>
        <input id="dic" type="text" name="dic" value="{{ old('dic', $customer?->dic) }}"
            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
    </div>
</div>

<div class="mt-4">
    <label for="notes" class="block text-sm mb-1">Poznámky</label>
    <textarea id="notes" name="notes" rows="4"
        class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">{{ old('notes', $customer?->notes) }}</textarea>
</div>
