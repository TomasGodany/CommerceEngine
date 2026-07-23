<x-layouts.app title="Dokumenty – Commerce Engine">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Doku<span class="text-[#d7e600]">menty</span></h1>
    </div>

    @if (session('status'))
        <div class="mb-4 text-sm text-[#d7e600] bg-[#1c1c1c] border border-[#2e2e2e] rounded px-3 py-2">
            {{ session('status') }}
        </div>
    @endif

    <form method="GET" action="{{ route('documents.index') }}" class="mb-4 flex flex-wrap items-end gap-3">
        <div>
            <label for="type" class="block text-sm mb-1">Typ</label>
            <select id="type" name="type"
                class="rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
                <option value="">Všetky</option>
                @foreach ($types as $type)
                    <option value="{{ $type->value }}" @selected($selectedType === $type->value)>{{ $type->label() }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="search" class="block text-sm mb-1">Hľadať (číslo dokumentu/objednávky)</label>
            <input type="text" id="search" name="search" value="{{ $search }}"
                class="rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
        </div>

        <button type="submit" class="rounded bg-[#d7e600] text-[#1c1c1c] font-medium px-4 py-2 hover:bg-[#c3d000] transition-colors">
            Filtrovať
        </button>
    </form>

    <div class="rounded-lg border border-[#2e2e2e] bg-[#1c1c1c] overflow-hidden">
        <table class="w-full text-sm text-left text-[#EDEDEC]">
            <thead class="bg-[#141414] text-xs uppercase opacity-70">
                <tr>
                    <th class="px-4 py-3">Číslo dokumentu</th>
                    <th class="px-4 py-3">Typ</th>
                    <th class="px-4 py-3">Objednávka</th>
                    <th class="px-4 py-3">Dátum vystavenia</th>
                    <th class="px-4 py-3 text-right">Akcie</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($documents as $document)
                    <tr class="border-t border-[#2e2e2e]">
                        <td class="px-4 py-3 font-medium">{{ $document->document_number }}</td>
                        <td class="px-4 py-3">{{ $document->type->label() }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('orders.show', $document->order_id) }}" class="text-[#d7e600] hover:underline">
                                {{ $document->order->order_number }}
                            </a>
                        </td>
                        <td class="px-4 py-3 opacity-70">{{ $document->issued_at->format('d.m.Y') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('documents.show', $document) }}" target="_blank" class="text-[#d7e600] hover:underline">Stiahnuť PDF</a>

                            <form method="POST" action="{{ route('documents.destroy', $document) }}" class="inline" onsubmit="return confirm('Naozaj chcete odstrániť tento dokument?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-3 text-red-400 hover:underline">Zmazať</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center opacity-70">Zatiaľ neboli vytvorené žiadne dokumenty.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $documents->links() }}
    </div>
</x-layouts.app>
