@php
    $product = $product ?? null;
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
        <input id="name" type="text" name="name" value="{{ old('name', $product?->name) }}" required
            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
    </div>

    <div>
        <label for="slug" class="block text-sm mb-1">Slug</label>
        <input id="slug" type="text" name="slug" value="{{ old('slug', $product?->slug) }}"
            placeholder="ponechajte prázdne pre automatické vygenerovanie"
            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
    </div>

    <div>
        <label for="sku" class="block text-sm mb-1">SKU</label>
        <input id="sku" type="text" name="sku" value="{{ old('sku', $product?->sku) }}" required
            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
    </div>

    <div>
        <label for="category_id" class="block text-sm mb-1">Kategória</label>
        <select id="category_id" name="category_id"
            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
            <option value="">— žiadna —</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $product?->category_id) == $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="brand_id" class="block text-sm mb-1">Značka</label>
        <select id="brand_id" name="brand_id"
            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
            <option value="">— žiadna —</option>
            @foreach ($brands as $brand)
                <option value="{{ $brand->id }}" @selected(old('brand_id', $product?->brand_id) == $brand->id)>
                    {{ $brand->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="price" class="block text-sm mb-1">Cena (€)</label>
        <input id="price" type="number" step="0.01" min="0" name="price" value="{{ old('price', $product?->price) }}" required
            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
    </div>

    <div>
        <label for="sale_price" class="block text-sm mb-1">Akciová cena (€)</label>
        <input id="sale_price" type="number" step="0.01" min="0" name="sale_price" value="{{ old('sale_price', $product?->sale_price) }}"
            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
    </div>
</div>

<div class="mt-4">
    <label for="image" class="block text-sm mb-1">Obrázok produktu</label>

    @if ($product?->image_path)
        <div class="mb-2 flex items-center gap-3">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-20 h-20 object-cover rounded border border-[#2e2e2e]">
            <label class="flex items-center gap-2 text-sm opacity-80">
                <input type="checkbox" name="remove_image" value="1">
                Odstrániť aktuálny obrázok
            </label>
        </div>
    @endif

    <input id="image" type="file" name="image" accept="image/*"
        class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 file:mr-3 file:rounded file:border-0 file:bg-[#d7e600] file:text-[#1c1c1c] file:px-3 file:py-1.5 file:font-medium focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
</div>

<div class="mt-4">
    <label for="short_description" class="block text-sm mb-1">Krátky popis</label>
    <textarea id="short_description" name="short_description" rows="2"
        class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">{{ old('short_description', $product?->short_description) }}</textarea>
</div>

<div class="mt-4">
    <label for="description" class="block text-sm mb-1">Popis</label>
    <textarea id="description" name="description" rows="5"
        class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">{{ old('description', $product?->description) }}</textarea>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
    <div>
        <label for="seo_title" class="block text-sm mb-1">SEO titulok</label>
        <input id="seo_title" type="text" name="seo_title" value="{{ old('seo_title', $product?->seo_title) }}"
            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
    </div>

    <div>
        <label for="seo_description" class="block text-sm mb-1">SEO popis</label>
        <input id="seo_description" type="text" name="seo_description" value="{{ old('seo_description', $product?->seo_description) }}"
            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
    </div>
</div>

<div class="flex items-center gap-2 mt-4">
    <input id="is_active" type="checkbox" name="is_active" value="1" @checked(old('is_active', $product?->is_active ?? true))>
    <label for="is_active" class="text-sm">Produkt je aktívny</label>
</div>
