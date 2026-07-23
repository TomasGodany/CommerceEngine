<x-layouts.app title="Sklady – Commerce Engine">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Skla<span class="text-[#d7e600]">dy</span></h1>

        <a href="{{ route('warehouses.create') }}" class="rounded bg-[#d7e600] text-[#1c1c1c] font-medium px-4 py-2 hover:bg-[#c3d000] transition-colors">
            + Nový sklad
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
                    <th class="px-4 py-3">Názov</th>
                    <th class="px-4 py-3">Kód</th>
                    <th class="px-4 py-3">Adresa</th>
                    <th class="px-4 py-3">Stav</th>
                    <th class="px-4 py-3 text-right">Akcie</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($warehouses as $warehouse)
                    <tr class="border-t border-[#2e2e2e]">
                        <td class="px-4 py-3 font-medium">{{ $warehouse->name }}</td>
                        <td class="px-4 py-3 opacity-70">{{ $warehouse->code }}</td>
                        <td class="px-4 py-3">{{ $warehouse->address ?? '—' }}</td>
                        <td class="px-4 py-3">
                            @if ($warehouse->is_active)
                                <span class="text-[#d7e600]">Aktívny</span>
                            @else
                                <span class="opacity-50">Neaktívny</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('warehouses.edit', $warehouse) }}" class="text-[#d7e600] hover:underline">Upraviť</a>

                            <form method="POST" action="{{ route('warehouses.destroy', $warehouse) }}" class="inline" onsubmit="return confirm('Naozaj chcete odstrániť tento sklad?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-3 text-red-400 hover:underline">Zmazať</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center opacity-70">Zatiaľ neboli vytvorené žiadne sklady.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $warehouses->links() }}
    </div>
</x-layouts.app>
