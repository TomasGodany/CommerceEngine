<x-layouts.app title="Produkty – Commerce Engine">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Pro<span class="text-[#d7e600]">dukty</span></h1>

        <a href="{{ route('products.create') }}" class="rounded bg-[#d7e600] text-[#1c1c1c] font-medium px-4 py-2 hover:bg-[#c3d000] transition-colors">
            + Nový produkt
        </a>
    </div>

    @if (session('status'))
        <div class="mb-4 text-sm text-[#d7e600] bg-[#1c1c1c] border border-[#2e2e2e] rounded px-3 py-2">
            {{ session('status') }}
        </div>
    @endif

    <div class="rounded-lg border border-[#2e2e2e] bg-[#1c1c1c] overflow-hidden">
        <table class="w-full text-sm text-left text-[#EDEDEC]">
            <thead class="bg-[#141414] text-xs uppercase opacity-70">
                <tr>
                    <th class="px-4 py-3">Obrázok</th>
                    <th class="px-4 py-3">Názov</th>
                    <th class="px-4 py-3">SKU</th>
                    <th class="px-4 py-3">Kategória</th>
                    <th class="px-4 py-3">Značka</th>
                    <th class="px-4 py-3">Cena</th>
                    <th class="px-4 py-3">Stav</th>
                    <th class="px-4 py-3 text-right">Akcie</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr class="border-t border-[#2e2e2e]">
                        <td class="px-4 py-3">
                            @if ($product->image_path)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded border border-[#2e2e2e]">
                            @else
                                <div class="w-12 h-12 rounded border border-[#2e2e2e] bg-[#141414] flex items-center justify-center text-xs opacity-40">—</div>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-medium">{{ $product->name }}</td>
                        <td class="px-4 py-3 opacity-70">{{ $product->sku }}</td>
                        <td class="px-4 py-3">{{ $product->category?->name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $product->brand?->name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ number_format((float) $product->price, 2) }} €</td>
                        <td class="px-4 py-3">
                            @if ($product->is_active)
                                <span class="text-[#d7e600]">Aktívny</span>
                            @else
                                <span class="opacity-50">Neaktívny</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('products.edit', $product) }}" class="text-[#d7e600] hover:underline">Upraviť</a>
                            <a href="{{ route('products.label', $product) }}" target="_blank" class="ml-3 text-[#EDEDEC] opacity-70 hover:opacity-100 hover:underline">Štítok</a>

                            <form method="POST" action="{{ route('products.destroy', $product) }}" class="inline" onsubmit="return confirm('Naozaj chcete odstrániť tento produkt?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-3 text-red-400 hover:underline">Zmazať</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center opacity-70">Zatiaľ neboli vytvorené žiadne produkty.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
</x-layouts.app>
