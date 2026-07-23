<x-layouts.app title="Kupóny – Commerce Engine">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Kup<span class="text-[#d7e600]">óny</span></h1>

        <a href="{{ route('coupons.create') }}" class="rounded bg-[#d7e600] text-[#1c1c1c] font-medium px-4 py-2 hover:bg-[#c3d000] transition-colors">
            + Nový kupón
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
                    <th class="px-4 py-3">Kód</th>
                    <th class="px-4 py-3">Typ</th>
                    <th class="px-4 py-3">Hodnota</th>
                    <th class="px-4 py-3">Použitie</th>
                    <th class="px-4 py-3">Platnosť</th>
                    <th class="px-4 py-3">Stav</th>
                    <th class="px-4 py-3 text-right">Akcie</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($coupons as $coupon)
                    <tr class="border-t border-[#2e2e2e]">
                        <td class="px-4 py-3 font-medium">{{ $coupon->code }}</td>
                        <td class="px-4 py-3">{{ $coupon->type === \App\Enums\DiscountType::Percentage ? 'Percentuálna' : 'Fixná' }}</td>
                        <td class="px-4 py-3">
                            {{ number_format((float) $coupon->value, 2) }}{{ $coupon->type === \App\Enums\DiscountType::Percentage ? ' %' : ' €' }}
                        </td>
                        <td class="px-4 py-3 opacity-70">{{ $coupon->used_count }} / {{ $coupon->usage_limit ?? '∞' }}</td>
                        <td class="px-4 py-3 opacity-70">
                            @if ($coupon->starts_at || $coupon->ends_at)
                                {{ $coupon->starts_at?->format('d.m.Y') ?? '—' }} – {{ $coupon->ends_at?->format('d.m.Y') ?? '—' }}
                            @else
                                Bez obmedzenia
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if ($coupon->is_active)
                                <span class="text-[#d7e600]">Aktívny</span>
                            @else
                                <span class="opacity-50">Neaktívny</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('coupons.edit', $coupon) }}" class="text-[#d7e600] hover:underline">Upraviť</a>

                            <form method="POST" action="{{ route('coupons.destroy', $coupon) }}" class="inline" onsubmit="return confirm('Naozaj chcete odstrániť tento kupón?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-3 text-red-400 hover:underline">Zmazať</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center opacity-70">Zatiaľ neboli vytvorené žiadne kupóny.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $coupons->links() }}
    </div>
</x-layouts.app>
