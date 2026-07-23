<x-layouts.app title="{{ $priceList->name }} – Commerce Engine">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Ce<span class="text-[#d7e600]">nník</span> {{ $priceList->name }}</h1>

        <div class="flex items-center gap-3">
            <a href="{{ route('price-lists.edit', $priceList) }}" class="rounded border border-[#3a3a3a] text-[#EDEDEC] px-4 py-2 hover:bg-[#2a2a2a] transition-colors">
                Upraviť
            </a>
            <a href="{{ route('price-lists.index') }}" class="text-sm text-[#EDEDEC] opacity-70 hover:opacity-100">Späť na zoznam</a>
        </div>
    </div>

    @if (session('status'))
        <div class="mb-4 text-sm text-[#d7e600] bg-[#1c1c1c] border border-[#2e2e2e] rounded px-3 py-2">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 text-sm text-red-400 bg-[#1c1c1c] border border-[#2e2e2e] rounded px-3 py-2">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-lg border border-[#2e2e2e] bg-[#1c1c1c] overflow-hidden">
                <table class="w-full text-sm text-left text-[#EDEDEC]">
                    <thead class="bg-[#141414] text-xs uppercase opacity-70">
                        <tr>
                            <th class="px-4 py-3">Produkt</th>
                            <th class="px-4 py-3">SKU</th>
                            <th class="px-4 py-3">Cena</th>
                            <th class="px-4 py-3 text-right">Akcie</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($priceList->items as $item)
                            <tr class="border-t border-[#2e2e2e]">
                                <td class="px-4 py-3 font-medium">{{ $item->product?->name ?? '—' }}</td>
                                <td class="px-4 py-3 opacity-70">{{ $item->product?->sku ?? '—' }}</td>
                                <td class="px-4 py-3">{{ number_format((float) $item->price, 2) }} €</td>
                                <td class="px-4 py-3 text-right">
                                    <form method="POST" action="{{ route('price-list-items.destroy', $item) }}" onsubmit="return confirm('Naozaj chcete odstrániť túto položku?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:underline">Zmazať</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center opacity-70">Cenník zatiaľ neobsahuje žiadne položky.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-lg border border-[#2e2e2e] bg-[#1c1c1c] p-4">
                <h2 class="text-lg font-semibold mb-3">Informácie</h2>

                <dl class="space-y-2 text-sm">
                    <div class="flex items-center justify-between">
                        <dt class="opacity-70">Kód</dt>
                        <dd>{{ $priceList->code }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="opacity-70">Predvolený</dt>
                        <dd>{{ $priceList->is_default ? 'Áno' : 'Nie' }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="opacity-70">Stav</dt>
                        <dd>{{ $priceList->is_active ? 'Aktívny' : 'Neaktívny' }}</dd>
                    </div>
                    @if ($priceList->description)
                        <div>
                            <dt class="opacity-70 mb-1">Popis</dt>
                            <dd>{{ $priceList->description }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            <div class="rounded-lg border border-[#2e2e2e] bg-[#1c1c1c] p-4">
                <h2 class="text-lg font-semibold mb-3">Pridať položku</h2>

                <form method="POST" action="{{ route('price-lists.items.store', $priceList) }}">
                    @csrf

                    <div class="mb-3">
                        <label for="product_id" class="block text-sm mb-1">Produkt</label>
                        <select id="product_id" name="product_id" required
                            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
                            <option value="">— vyberte produkt —</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>
                                    {{ $product->name }} ({{ $product->sku }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="block text-sm mb-1">Cena (€)</label>
                        <input id="price" type="number" step="0.01" min="0" name="price" value="{{ old('price') }}" required
                            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
                    </div>

                    <button type="submit" class="w-full rounded bg-[#d7e600] text-[#1c1c1c] font-medium px-4 py-2 hover:bg-[#c3d000] transition-colors">
                        Pridať položku
                    </button>
                </form>
            </div>

            <form method="POST" action="{{ route('price-lists.destroy', $priceList) }}" onsubmit="return confirm('Naozaj chcete odstrániť tento cenník?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full rounded border border-red-400/40 text-red-400 px-4 py-2 hover:bg-red-400/10 transition-colors">
                    Zmazať cenník
                </button>
            </form>
        </div>
    </div>
</x-layouts.app>
