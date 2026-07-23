<x-layouts.app title="Skladové zásoby – Commerce Engine">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Skladové z<span class="text-[#d7e600]">ásoby</span></h1>

        <a href="{{ route('stock-movements.create') }}" class="rounded bg-[#d7e600] text-[#1c1c1c] font-medium px-4 py-2 hover:bg-[#c3d000] transition-colors">
            + Nový pohyb
        </a>
    </div>

    @if (session('status'))
        <div class="mb-4 text-sm text-[#d7e600] bg-[#1c1c1c] border border-[#2e2e2e] rounded px-3 py-2">
            {{ session('status') }}
        </div>
    @endif

    <form method="GET" action="{{ route('stock-movements.index') }}" class="mb-6 flex flex-wrap items-end gap-4 bg-[#1c1c1c] border border-[#2e2e2e] rounded-lg p-4">
        <div>
            <label for="product_id" class="block text-sm mb-1">Produkt</label>
            <select id="product_id" name="product_id"
                class="rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
                <option value="">— všetky —</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" @selected(request('product_id') == $product->id)>
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="rounded bg-[#d7e600] text-[#1c1c1c] font-medium px-4 py-2 hover:bg-[#c3d000] transition-colors">
                Filtrovať
            </button>
            <a href="{{ route('stock-movements.index') }}" class="text-sm text-[#EDEDEC] opacity-70 hover:opacity-100">Zrušiť filter</a>
        </div>
    </form>

    <div class="rounded-lg border border-[#2e2e2e] bg-[#1c1c1c] overflow-hidden">
        <table class="w-full text-sm text-left text-[#EDEDEC]">
            <thead class="bg-[#141414] text-xs uppercase opacity-70">
                <tr>
                    <th class="px-4 py-3">Dátum</th>
                    <th class="px-4 py-3">Produkt</th>
                    <th class="px-4 py-3">Variant</th>
                    <th class="px-4 py-3">Typ</th>
                    <th class="px-4 py-3">Množstvo</th>
                    <th class="px-4 py-3">Používateľ</th>
                    <th class="px-4 py-3">Poznámka</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($movements as $movement)
                    <tr class="border-t border-[#2e2e2e]">
                        <td class="px-4 py-3 opacity-70">{{ $movement->created_at->format('d.m.Y H:i') }}</td>
                        <td class="px-4 py-3 font-medium">{{ $movement->product?->name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $movement->productVariant?->name ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="text-[#d7e600]">{{ $movement->type }}</span>
                        </td>
                        <td class="px-4 py-3">{{ $movement->quantity }}</td>
                        <td class="px-4 py-3">{{ $movement->user?->name ?? '—' }}</td>
                        <td class="px-4 py-3 opacity-70">{{ $movement->note ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center opacity-70">Zatiaľ neboli zaznamenané žiadne skladové zásoby.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $movements->links() }}
    </div>

    <h2 class="text-xl font-semibold mt-10 mb-4">Aktuálne st<span class="text-[#d7e600]">avy zásob</span></h2>

    <div class="rounded-lg border border-[#2e2e2e] bg-[#1c1c1c] overflow-hidden">
        <table class="w-full text-sm text-left text-[#EDEDEC]">
            <thead class="bg-[#141414] text-xs uppercase opacity-70">
                <tr>
                    <th class="px-4 py-3">Produkt</th>
                    <th class="px-4 py-3">Variant</th>
                    <th class="px-4 py-3">Množstvo</th>
                    <th class="px-4 py-3">Rezervované</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($stockItems as $stockItem)
                    <tr class="border-t border-[#2e2e2e]">
                        <td class="px-4 py-3 font-medium">{{ $stockItem->product?->name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $stockItem->productVariant?->name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $stockItem->quantity }}</td>
                        <td class="px-4 py-3 opacity-70">{{ $stockItem->reserved_quantity }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center opacity-70">Zatiaľ nie sú evidované žiadne skladové zásoby.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>
