<x-layouts.app title="Objednávky – Commerce Engine">
    @php
        $statusLabels = [
            'new' => 'Nová',
            'processing' => 'Vybavuje sa',
            'paid' => 'Zaplatená',
            'shipped' => 'Odoslaná',
            'completed' => 'Dokončená',
            'cancelled' => 'Zrušená',
            'returned' => 'Vrátená',
        ];

        $statusClasses = [
            'new' => 'bg-[#2e2e2e] text-[#EDEDEC]',
            'processing' => 'bg-blue-900/40 text-blue-300',
            'paid' => 'bg-emerald-900/40 text-emerald-300',
            'shipped' => 'bg-indigo-900/40 text-indigo-300',
            'completed' => 'bg-[#d7e600]/20 text-[#d7e600]',
            'cancelled' => 'bg-red-900/40 text-red-300',
            'returned' => 'bg-orange-900/40 text-orange-300',
        ];

        $paymentStatusLabels = [
            'unpaid' => 'Nezaplatené',
            'paid' => 'Zaplatené',
            'refunded' => 'Vrátené',
        ];

        $paymentStatusClasses = [
            'unpaid' => 'bg-red-900/40 text-red-300',
            'paid' => 'bg-emerald-900/40 text-emerald-300',
            'refunded' => 'bg-orange-900/40 text-orange-300',
        ];
    @endphp

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Objedn<span class="text-[#d7e600]">ávky</span></h1>

        <a href="{{ route('orders.create') }}" class="rounded bg-[#d7e600] text-[#1c1c1c] font-medium px-4 py-2 hover:bg-[#c3d000] transition-colors">
            + Nová objednávka
        </a>
    </div>

    @if (session('status'))
        <div class="mb-4 text-sm text-[#d7e600] bg-[#1c1c1c] border border-[#2e2e2e] rounded px-3 py-2">
            {{ session('status') }}
        </div>
    @endif

    <form method="GET" action="{{ route('orders.index') }}" class="mb-4 flex items-center gap-3">
        <label for="status" class="text-sm opacity-70">Stav:</label>
        <select id="status" name="status" onchange="this.form.submit()"
            class="rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
            <option value="">— všetky —</option>
            @foreach ($statuses as $status)
                <option value="{{ $status->value }}" @selected($selectedStatus === $status->value)>
                    {{ $statusLabels[$status->value] }}
                </option>
            @endforeach
        </select>
    </form>

    <div class="rounded-lg border border-[#2e2e2e] bg-[#1c1c1c] overflow-hidden">
        <table class="w-full text-sm text-left text-[#EDEDEC]">
            <thead class="bg-[#141414] text-xs uppercase opacity-70">
                <tr>
                    <th class="px-4 py-3">Číslo objednávky</th>
                    <th class="px-4 py-3">Zákazník</th>
                    <th class="px-4 py-3">Suma</th>
                    <th class="px-4 py-3">Stav</th>
                    <th class="px-4 py-3">Platba</th>
                    <th class="px-4 py-3">Vytvorené</th>
                    <th class="px-4 py-3 text-right">Akcie</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr class="border-t border-[#2e2e2e]">
                        <td class="px-4 py-3 font-medium">
                            <a href="{{ route('orders.show', $order) }}" class="hover:underline">{{ $order->order_number }}</a>
                        </td>
                        <td class="px-4 py-3">{{ $order->customer?->full_name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ number_format((float) $order->total_amount, 2) }} {{ $order->currency }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs font-medium {{ $statusClasses[$order->status->value] }}">
                                {{ $statusLabels[$order->status->value] }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs font-medium {{ $paymentStatusClasses[$order->payment_status->value] }}">
                                {{ $paymentStatusLabels[$order->payment_status->value] }}
                            </span>
                        </td>
                        <td class="px-4 py-3 opacity-70">{{ $order->created_at->format('d.m.Y H:i') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('orders.show', $order) }}" class="text-[#d7e600] hover:underline">Detail</a>
                            <a href="{{ route('orders.edit', $order) }}" class="ml-3 text-[#d7e600] hover:underline">Upraviť</a>

                            <form method="POST" action="{{ route('orders.destroy', $order) }}" class="inline" onsubmit="return confirm('Naozaj chcete odstrániť túto objednávku?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-3 text-red-400 hover:underline">Zmazať</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center opacity-70">Zatiaľ neboli vytvorené žiadne objednávky.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $orders->links() }}
    </div>
</x-layouts.app>
