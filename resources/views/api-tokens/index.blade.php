<x-layouts.app title="API kľúče – Commerce Engine">
    <h1 class="text-2xl font-semibold mb-6">API k<span class="text-[#d7e600]">ľúče</span></h1>

    @if (session('status'))
        <div class="mb-4 text-sm text-[#d7e600] bg-[#1c1c1c] border border-[#2e2e2e] rounded px-3 py-2">
            {{ session('status') }}
        </div>
    @endif

    @if (session('plain_text_token'))
        <div class="mb-6 text-sm text-[#1c1c1c] bg-[#d7e600] border border-[#c3d000] rounded px-4 py-3">
            <p class="font-medium mb-1">Nový API kľúč bol vygenerovaný. Skopírujte si ho, zobrazí sa len raz:</p>
            <code class="block break-all bg-[#1c1c1c] text-[#d7e600] rounded px-3 py-2 mt-1">{{ session('plain_text_token') }}</code>
        </div>
    @endif

    <form method="POST" action="{{ route('api-tokens.store') }}" class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4 bg-[#1c1c1c] border border-[#2e2e2e] rounded-lg p-4">
        @csrf

        @if ($errors->any())
            <div class="md:col-span-3 text-sm text-red-400 bg-[#141414] border border-[#2e2e2e] rounded px-3 py-2">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div>
            <label for="user_id" class="block text-sm mb-1">Používateľ</label>
            <select id="user_id" name="user_id" required
                class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
                <option value="">— vyberte —</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="name" class="block text-sm mb-1">Názov kľúča</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required
                class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
        </div>

        <div class="flex items-end">
            <button type="submit" class="rounded bg-[#d7e600] text-[#1c1c1c] font-medium px-4 py-2 hover:bg-[#c3d000] transition-colors">
                Vygenerovať kľúč
            </button>
        </div>
    </form>

    <div class="rounded-lg border border-[#2e2e2e] bg-[#1c1c1c] overflow-hidden">
        <table class="w-full text-sm text-left text-[#EDEDEC]">
            <thead class="bg-[#141414] text-xs uppercase opacity-70">
                <tr>
                    <th class="px-4 py-3">Názov</th>
                    <th class="px-4 py-3">Používateľ</th>
                    <th class="px-4 py-3">Posledné použitie</th>
                    <th class="px-4 py-3">Vytvorené</th>
                    <th class="px-4 py-3 text-right">Akcie</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tokens as $token)
                    <tr class="border-t border-[#2e2e2e]">
                        <td class="px-4 py-3 font-medium">{{ $token->name }}</td>
                        <td class="px-4 py-3 opacity-70">{{ $token->tokenable?->name ?? '—' }}</td>
                        <td class="px-4 py-3 opacity-70">{{ $token->last_used_at?->format('d.m.Y H:i') ?? 'nikdy' }}</td>
                        <td class="px-4 py-3 opacity-70">{{ $token->created_at?->format('d.m.Y H:i') }}</td>
                        <td class="px-4 py-3 text-right">
                            <form method="POST" action="{{ route('api-tokens.destroy', $token->id) }}" class="inline" onsubmit="return confirm('Naozaj chcete zrušiť tento API kľúč?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:underline">Zrušiť</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center opacity-70">Zatiaľ neboli vygenerované žiadne API kľúče.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $tokens->links() }}
    </div>
</x-layouts.app>
