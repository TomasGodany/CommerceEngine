<x-layouts.app title="Používatelia – Commerce Engine">
    <h1 class="text-2xl font-semibold mb-6">Použí<span class="text-[#d7e600]">vatelia</span></h1>

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
                    <th class="px-4 py-3">E-mail</th>
                    <th class="px-4 py-3">Rola</th>
                    <th class="px-4 py-3">Stav</th>
                    <th class="px-4 py-3 text-right">Akcie</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr class="border-t border-[#2e2e2e]">
                        <td class="px-4 py-3 font-medium">{{ $user->name }}</td>
                        <td class="px-4 py-3 opacity-70">{{ $user->email }}</td>
                        <td class="px-4 py-3">{{ $user->role->value }}</td>
                        <td class="px-4 py-3">
                            @if ($user->is_active)
                                <span class="text-[#d7e600]">Aktívny</span>
                            @else
                                <span class="opacity-50">Zablokovaný</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            @if ($user->id === auth()->id())
                                <span class="opacity-40">Váš účet</span>
                            @else
                                <a href="{{ route('users.edit', $user) }}" class="text-[#d7e600] hover:underline">Upraviť</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center opacity-70">Zatiaľ neboli vytvorení žiadni používatelia.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</x-layouts.app>
