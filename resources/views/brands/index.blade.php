<x-layouts.app title="Značky – Commerce Engine">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Zna<span class="text-[#d7e600]">čky</span></h1>

        <a href="{{ route('brands.create') }}" class="rounded bg-[#d7e600] text-[#1c1c1c] font-medium px-4 py-2 hover:bg-[#c3d000] transition-colors">
            + Nová značka
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
                    <th class="px-4 py-3">Logo</th>
                    <th class="px-4 py-3">Názov</th>
                    <th class="px-4 py-3">Stav</th>
                    <th class="px-4 py-3 text-right">Akcie</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($brands as $brand)
                    <tr class="border-t border-[#2e2e2e]">
                        <td class="px-4 py-3">
                            @if ($brand->logo_path)
                                <img src="{{ Illuminate\Support\Facades\Storage::disk('public')->url($brand->logo_path) }}" alt="{{ $brand->name }}" class="w-12 h-12 object-cover rounded border border-[#2e2e2e]">
                            @else
                                <div class="w-12 h-12 rounded border border-[#2e2e2e] bg-[#141414] flex items-center justify-center text-xs opacity-40">—</div>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-medium">{{ $brand->name }}</td>
                        <td class="px-4 py-3">
                            @if ($brand->is_active)
                                <span class="text-[#d7e600]">Aktívna</span>
                            @else
                                <span class="opacity-50">Neaktívna</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('brands.edit', $brand) }}" class="text-[#d7e600] hover:underline">Upraviť</a>

                            <form method="POST" action="{{ route('brands.destroy', $brand) }}" class="inline" onsubmit="return confirm('Naozaj chcete odstrániť túto značku?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-3 text-red-400 hover:underline">Zmazať</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center opacity-70">Zatiaľ neboli vytvorené žiadne značky.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $brands->links() }}
    </div>
</x-layouts.app>
