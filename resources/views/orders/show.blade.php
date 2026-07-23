<x-layouts.app title="Objednávka {{ $order->order_number }} – Commerce Engine">
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
        <h1 class="text-2xl font-semibold">Objedn<span class="text-[#d7e600]">ávka</span> {{ $order->order_number }}</h1>

        <div class="flex items-center gap-3">
            <a href="{{ route('orders.edit', $order) }}" class="rounded border border-[#3a3a3a] text-[#EDEDEC] px-4 py-2 hover:bg-[#2a2a2a] transition-colors">
                Upraviť
            </a>
            <a href="{{ route('orders.index') }}" class="text-sm text-[#EDEDEC] opacity-70 hover:opacity-100">Späť na zoznam</a>
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
                            <th class="px-4 py-3">Množstvo</th>
                            <th class="px-4 py-3">Jedn. cena</th>
                            <th class="px-4 py-3">Spolu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr class="border-t border-[#2e2e2e]">
                                <td class="px-4 py-3 font-medium">{{ $item->product_name }}</td>
                                <td class="px-4 py-3">{{ $item->quantity }}</td>
                                <td class="px-4 py-3">{{ number_format((float) $item->unit_price, 2) }} {{ $order->currency }}</td>
                                <td class="px-4 py-3">{{ number_format((float) $item->total_price, 2) }} {{ $order->currency }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="border-t border-[#2e2e2e]">
                            <td class="px-4 py-3 font-semibold" colspan="3">Celková suma</td>
                            <td class="px-4 py-3 font-semibold text-[#d7e600]">{{ number_format((float) $order->total_amount, 2) }} {{ $order->currency }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="rounded-lg border border-[#2e2e2e] bg-[#1c1c1c] p-4">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-lg font-semibold">Dokumenty</h2>

                    <div class="flex items-center gap-2">
                        <form method="POST" action="{{ route('orders.documents.store', $order) }}">
                            @csrf
                            <input type="hidden" name="type" value="invoice">
                            <button type="submit" class="rounded border border-[#3a3a3a] text-[#EDEDEC] px-3 py-1.5 text-sm hover:bg-[#2a2a2a] transition-colors">
                                Vytvoriť faktúru
                            </button>
                        </form>

                        <form method="POST" action="{{ route('orders.documents.store', $order) }}">
                            @csrf
                            <input type="hidden" name="type" value="delivery_note">
                            <button type="submit" class="rounded border border-[#3a3a3a] text-[#EDEDEC] px-3 py-1.5 text-sm hover:bg-[#2a2a2a] transition-colors">
                                Vytvoriť dodací list
                            </button>
                        </form>

                        <form method="POST" action="{{ route('orders.documents.store', $order) }}">
                            @csrf
                            <input type="hidden" name="type" value="quote">
                            <button type="submit" class="rounded border border-[#3a3a3a] text-[#EDEDEC] px-3 py-1.5 text-sm hover:bg-[#2a2a2a] transition-colors">
                                Vytvoriť cenovú ponuku
                            </button>
                        </form>
                    </div>
                </div>

                <table class="w-full text-sm text-left text-[#EDEDEC]">
                    <thead class="bg-[#141414] text-xs uppercase opacity-70">
                        <tr>
                            <th class="px-4 py-3">Číslo dokumentu</th>
                            <th class="px-4 py-3">Typ</th>
                            <th class="px-4 py-3">Dátum</th>
                            <th class="px-4 py-3 text-right">Akcie</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($order->documents as $document)
                            <tr class="border-t border-[#2e2e2e]">
                                <td class="px-4 py-3 font-medium">{{ $document->document_number }}</td>
                                <td class="px-4 py-3">{{ $document->type->label() }}</td>
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
                                <td colspan="4" class="px-4 py-6 text-center opacity-70">Zatiaľ žiadne dokumenty.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="rounded-lg border border-[#2e2e2e] bg-[#1c1c1c] overflow-hidden">
                <h2 class="text-lg font-semibold px-4 pt-4">História stavov</h2>

                <table class="w-full text-sm text-left text-[#EDEDEC] mt-2">
                    <thead class="bg-[#141414] text-xs uppercase opacity-70">
                        <tr>
                            <th class="px-4 py-3">Stav</th>
                            <th class="px-4 py-3">Poznámka</th>
                            <th class="px-4 py-3">Používateľ</th>
                            <th class="px-4 py-3">Dátum</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($order->statusHistories as $history)
                            <tr class="border-t border-[#2e2e2e]">
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded text-xs font-medium {{ $statusClasses[$history->status->value] }}">
                                        {{ $statusLabels[$history->status->value] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 opacity-70">{{ $history->note ?? '—' }}</td>
                                <td class="px-4 py-3">{{ $history->user?->name ?? '—' }}</td>
                                <td class="px-4 py-3 opacity-70">{{ $history->created_at->format('d.m.Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center opacity-70">Zatiaľ žiadna história stavov.</td>
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
                        <dt class="opacity-70">Zákazník</dt>
                        <dd>{{ $order->customer?->full_name ?? '—' }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="opacity-70">Stav</dt>
                        <dd>
                            <span class="px-2 py-1 rounded text-xs font-medium {{ $statusClasses[$order->status->value] }}">
                                {{ $statusLabels[$order->status->value] }}
                            </span>
                        </dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="opacity-70">Platba</dt>
                        <dd>
                            <span class="px-2 py-1 rounded text-xs font-medium {{ $paymentStatusClasses[$order->payment_status->value] }}">
                                {{ $paymentStatusLabels[$order->payment_status->value] }}
                            </span>
                        </dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="opacity-70">Vytvorené</dt>
                        <dd>{{ $order->created_at->format('d.m.Y H:i') }}</dd>
                    </div>
                    @if ($order->notes)
                        <div>
                            <dt class="opacity-70 mb-1">Poznámka</dt>
                            <dd>{{ $order->notes }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            <div class="rounded-lg border border-[#2e2e2e] bg-[#1c1c1c] p-4">
                <h2 class="text-lg font-semibold mb-3">Zmeniť stav</h2>

                <form method="POST" action="{{ route('orders.status.update', $order) }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label for="status" class="block text-sm mb-1">Nový stav</label>
                        <select id="status" name="status" required
                            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
                            @foreach ($statuses as $status)
                                <option value="{{ $status->value }}" @selected(old('status', $order->status->value) === $status->value)>
                                    {{ $statusLabels[$status->value] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="note" class="block text-sm mb-1">Poznámka</label>
                        <textarea id="note" name="note" rows="2"
                            class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">{{ old('note') }}</textarea>
                    </div>

                    <button type="submit" class="w-full rounded bg-[#d7e600] text-[#1c1c1c] font-medium px-4 py-2 hover:bg-[#c3d000] transition-colors">
                        Zmeniť stav
                    </button>
                </form>
            </div>

            <form method="POST" action="{{ route('orders.destroy', $order) }}" onsubmit="return confirm('Naozaj chcete odstrániť túto objednávku?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full rounded border border-red-400/40 text-red-400 px-4 py-2 hover:bg-red-400/10 transition-colors">
                    Zmazať objednávku
                </button>
            </form>
        </div>
    </div>
</x-layouts.app>
