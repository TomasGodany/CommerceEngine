<x-layouts.app title="Zákazníci – Commerce Engine">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Zá<span class="text-[#d7e600]">kazníci</span></h1>

        <a href="{{ route('customers.create') }}" class="rounded bg-[#d7e600] text-[#1c1c1c] font-medium px-4 py-2 hover:bg-[#c3d000] transition-colors">
            + Nový zákazník
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
                    <th class="px-4 py-3">Meno</th>
                    <th class="px-4 py-3">Firma</th>
                    <th class="px-4 py-3">E-mail</th>
                    <th class="px-4 py-3">Telefón</th>
                    <th class="px-4 py-3">Typ</th>
                    <th class="px-4 py-3 text-right">Akcie</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($customers as $customer)
                    <tr class="border-t border-[#2e2e2e]">
                        <td class="px-4 py-3 font-medium">
                            <a href="{{ route('customers.show', $customer) }}" class="hover:text-[#d7e600]">{{ $customer->full_name }}</a>
                        </td>
                        <td class="px-4 py-3 opacity-70">{{ $customer->company_name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $customer->email }}</td>
                        <td class="px-4 py-3">{{ $customer->phone ?? '—' }}</td>
                        <td class="px-4 py-3">
                            @if ($customer->is_company)
                                <span class="text-[#d7e600]">Firma</span>
                            @else
                                <span class="opacity-50">Fyzická osoba</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('customers.show', $customer) }}" class="text-[#d7e600] hover:underline">Detail</a>
                            <a href="{{ route('customers.edit', $customer) }}" class="ml-3 text-[#d7e600] hover:underline">Upraviť</a>

                            <form method="POST" action="{{ route('customers.destroy', $customer) }}" class="inline" onsubmit="return confirm('Naozaj chcete odstrániť tohto zákazníka?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-3 text-red-400 hover:underline">Zmazať</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center opacity-70">Zatiaľ neboli vytvorení žiadni zákazníci.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $customers->links() }}
    </div>
</x-layouts.app>
