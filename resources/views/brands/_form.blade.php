@php
    $brand = $brand ?? null;
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
        <label for="name" class="block text-sm mb-1">Názov</label>
        <input id="name" type="text" name="name" value="{{ old('name', $brand?->name) }}" required
            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
    </div>

    <div>
        <label for="slug" class="block text-sm mb-1">Slug</label>
        <input id="slug" type="text" name="slug" value="{{ old('slug', $brand?->slug) }}"
            placeholder="ponechajte prázdne pre automatické vygenerovanie"
            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
    </div>
</div>

<div class="mt-4">
    <label for="logo" class="block text-sm mb-1">Logo značky</label>

    @if ($brand?->logo_path)
        <div class="mb-2 flex items-center gap-3">
            <img src="{{ Illuminate\Support\Facades\Storage::disk('public')->url($brand->logo_path) }}" alt="{{ $brand->name }}" class="w-20 h-20 object-cover rounded border border-[#2e2e2e]">
            <label class="flex items-center gap-2 text-sm opacity-80">
                <input type="checkbox" name="remove_logo" value="1">
                Odstrániť aktuálne logo
            </label>
        </div>
    @endif

    <input id="logo" type="file" name="logo" accept="image/*"
        class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 file:mr-3 file:rounded file:border-0 file:bg-[#d7e600] file:text-[#1c1c1c] file:px-3 file:py-1.5 file:font-medium focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
</div>

<div class="mt-4">
    <label for="description" class="block text-sm mb-1">Popis</label>
    <textarea id="description" name="description" rows="4"
        class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">{{ old('description', $brand?->description) }}</textarea>
</div>

<div class="flex items-center gap-2 mt-4">
    <input id="is_active" type="checkbox" name="is_active" value="1" @checked(old('is_active', $brand?->is_active ?? true))>
    <label for="is_active" class="text-sm">Značka je aktívna</label>
</div>
