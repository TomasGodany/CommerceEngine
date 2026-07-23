@php
    $category = $category ?? null;
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
        <input id="name" type="text" name="name" value="{{ old('name', $category?->name) }}" required
            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
    </div>

    <div>
        <label for="slug" class="block text-sm mb-1">Slug</label>
        <input id="slug" type="text" name="slug" value="{{ old('slug', $category?->slug) }}"
            placeholder="ponechajte prázdne pre automatické vygenerovanie"
            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
    </div>

    <div>
        <label for="parent_id" class="block text-sm mb-1">Nadradená kategória</label>
        <select id="parent_id" name="parent_id"
            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
            <option value="">— žiadna —</option>
            @foreach ($categories as $parentOption)
                <option value="{{ $parentOption->id }}" @selected(old('parent_id', $category?->parent_id) == $parentOption->id)>
                    {{ $parentOption->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="position" class="block text-sm mb-1">Pozícia</label>
        <input id="position" type="number" min="0" name="position" value="{{ old('position', $category?->position ?? 0) }}"
            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
    </div>
</div>

<div class="mt-4">
    <label for="description" class="block text-sm mb-1">Popis</label>
    <textarea id="description" name="description" rows="4"
        class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">{{ old('description', $category?->description) }}</textarea>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
    <div>
        <label for="seo_title" class="block text-sm mb-1">SEO titulok</label>
        <input id="seo_title" type="text" name="seo_title" value="{{ old('seo_title', $category?->seo_title) }}"
            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
    </div>

    <div>
        <label for="seo_description" class="block text-sm mb-1">SEO popis</label>
        <input id="seo_description" type="text" name="seo_description" value="{{ old('seo_description', $category?->seo_description) }}"
            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
    </div>
</div>

<div class="flex items-center gap-2 mt-4">
    <input id="is_active" type="checkbox" name="is_active" value="1" @checked(old('is_active', $category?->is_active ?? true))>
    <label for="is_active" class="text-sm">Kategória je aktívna</label>
</div>
