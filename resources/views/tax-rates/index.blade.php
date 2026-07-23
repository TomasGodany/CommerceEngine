<x-layouts.app title="Daňové sadzby – Commerce Engine">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Daňové sa<span class="text-[#d7e600]">dzby</span></h1>

        <a href="{{ route('tax-rates.create') }}" class="rounded bg-[#d7e600] text-[#1c1c1c] font-medium px-4 py-2 hover:bg-[#c3d000] transition-colors">
            + Nová sadzba
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
                    <th class="px-4 py-3">Sadzba</th>
                    <th class="px-4 py-3">Predvolená</th>
                    <th class="px-4 py-3 text-right">Akcie</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($taxRates as $taxRate)
                    <tr class="border-t border-[#2e2e2e]">
                        <td class="px-4 py-3 font-medium">{{ $taxRate->name }}</td>
                        <td class="px-4 py-3">{{ $taxRate->rate }} %</td>
                        <td class="px-4 py-3">
                            @if ($taxRate->is_default)
                                <span class="text-[#d7e600]">Áno</span>
                            @else
                                <span class="opacity-50">Nie</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('tax-rates.edit', $taxRate) }}" class="text-[#d7e600] hover:underline">Upraviť</a>

                            <form method="POST" action="{{ route('tax-rates.destroy', $taxRate) }}" class="inline" onsubmit="return confirm('Naozaj chcete odstrániť túto sadzbu?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-3 text-red-400 hover:underline">Zmazať</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center opacity-70">Zatiaľ neboli vytvorené žiadne daňové sadzby.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $taxRates->links() }}
    </div>
</x-layouts.app>
