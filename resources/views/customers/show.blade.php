<x-layouts.app title="{{ $customer->full_name }} – Commerce Engine">
    @php
        $addressTypeLabels = [
            'billing' => 'Fakturačná',
            'shipping' => 'Doručovacia',
        ];
    @endphp

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">{{ $customer->full_name }}</h1>

        <div class="flex items-center gap-3">
            <a href="{{ route('customers.edit', $customer) }}" class="rounded border border-[#3a3a3a] text-[#EDEDEC] px-4 py-2 hover:bg-[#2a2a2a] transition-colors">
                Upraviť
            </a>
            <a href="{{ route('customers.index') }}" class="text-sm text-[#EDEDEC] opacity-70 hover:opacity-100">Späť na zoznam</a>
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
                <h2 class="text-lg font-semibold px-4 pt-4">Adresy</h2>

                <table class="w-full text-sm text-left text-[#EDEDEC] mt-2">
                    <thead class="bg-[#141414] text-xs uppercase opacity-70">
                        <tr>
                            <th class="px-4 py-3">Typ</th>
                            <th class="px-4 py-3">Ulica</th>
                            <th class="px-4 py-3">Mesto</th>
                            <th class="px-4 py-3">PSČ</th>
                            <th class="px-4 py-3">Krajina</th>
                            <th class="px-4 py-3">Predvolená</th>
                            <th class="px-4 py-3 text-right">Akcie</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customer->addresses as $address)
                            <tr class="border-t border-[#2e2e2e]">
                                <td class="px-4 py-3">{{ $addressTypeLabels[$address->type] ?? $address->type }}</td>
                                <td class="px-4 py-3">{{ $address->street }}</td>
                                <td class="px-4 py-3">{{ $address->city }}</td>
                                <td class="px-4 py-3">{{ $address->zip }}</td>
                                <td class="px-4 py-3">{{ $address->country }}</td>
                                <td class="px-4 py-3">
                                    @if ($address->is_default)
                                        <span class="text-[#d7e600]">Áno</span>
                                    @else
                                        <span class="opacity-50">Nie</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <form method="POST" action="{{ route('customer-addresses.destroy', $address) }}" class="inline" onsubmit="return confirm('Naozaj chcete odstrániť túto adresu?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:underline">Zmazať</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-6 text-center opacity-70">Zatiaľ neboli pridané žiadne adresy.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="rounded-lg border border-[#2e2e2e] bg-[#1c1c1c] p-4">
                <h2 class="text-lg font-semibold mb-3">Pridať adresu</h2>

                <form method="POST" action="{{ route('customers.addresses.store', $customer) }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="type" class="block text-sm mb-1">Typ adresy</label>
                            <select id="type" name="type" required
                                class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
                                <option value="shipping" @selected(old('type') === 'shipping')>Doručovacia</option>
                                <option value="billing" @selected(old('type') === 'billing')>Fakturačná</option>
                            </select>
                        </div>

                        <div>
                            <label for="street" class="block text-sm mb-1">Ulica</label>
                            <input id="street" type="text" name="street" value="{{ old('street') }}" required
                                class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
                        </div>

                        <div>
                            <label for="city" class="block text-sm mb-1">Mesto</label>
                            <input id="city" type="text" name="city" value="{{ old('city') }}" required
                                class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
                        </div>

                        <div>
                            <label for="zip" class="block text-sm mb-1">PSČ</label>
                            <input id="zip" type="text" name="zip" value="{{ old('zip') }}" required
                                class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
                        </div>

                        <div>
                            <label for="country" class="block text-sm mb-1">Krajina</label>
                            <input id="country" type="text" name="country" value="{{ old('country', 'SK') }}"
                                class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
                        </div>
                    </div>

                    <div class="flex items-center gap-2 mt-4">
                        <input id="is_default" type="checkbox" name="is_default" value="1" @checked(old('is_default'))>
                        <label for="is_default" class="text-sm">Predvolená adresa</label>
                    </div>

                    <button type="submit" class="mt-4 rounded bg-[#d7e600] text-[#1c1c1c] font-medium px-4 py-2 hover:bg-[#c3d000] transition-colors">
                        Pridať adresu
                    </button>
                </form>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-lg border border-[#2e2e2e] bg-[#1c1c1c] p-4">
                <h2 class="text-lg font-semibold mb-3">Informácie</h2>

                <dl class="space-y-2 text-sm">
                    <div class="flex items-center justify-between">
                        <dt class="opacity-70">Typ</dt>
                        <dd>
                            @if ($customer->is_company)
                                <span class="text-[#d7e600]">Firma</span>
                            @else
                                <span class="opacity-50">Fyzická osoba</span>
                            @endif
                        </dd>
                    </div>
                    @if ($customer->company_name)
                        <div class="flex items-center justify-between">
                            <dt class="opacity-70">Firma</dt>
                            <dd>{{ $customer->company_name }}</dd>
                        </div>
                    @endif
                    @if ($customer->ico)
                        <div class="flex items-center justify-between">
                            <dt class="opacity-70">IČO</dt>
                            <dd>{{ $customer->ico }}</dd>
                        </div>
                    @endif
                    @if ($customer->dic)
                        <div class="flex items-center justify-between">
                            <dt class="opacity-70">DIČ</dt>
                            <dd>{{ $customer->dic }}</dd>
                        </div>
                    @endif
                    <div class="flex items-center justify-between">
                        <dt class="opacity-70">E-mail</dt>
                        <dd>{{ $customer->email }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="opacity-70">Telefón</dt>
                        <dd>{{ $customer->phone ?? '—' }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="opacity-70">Vytvorený</dt>
                        <dd>{{ $customer->created_at->format('d.m.Y H:i') }}</dd>
                    </div>
                    @if ($customer->notes)
                        <div>
                            <dt class="opacity-70 mb-1">Poznámky</dt>
                            <dd>{{ $customer->notes }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            <form method="POST" action="{{ route('customers.destroy', $customer) }}" onsubmit="return confirm('Naozaj chcete odstrániť tohto zákazníka?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full rounded border border-red-400/40 text-red-400 px-4 py-2 hover:bg-red-400/10 transition-colors">
                    Zmazať zákazníka
                </button>
            </form>
        </div>
    </div>
</x-layouts.app>
