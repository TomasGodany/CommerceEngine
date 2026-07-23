<x-layouts.app title="Upraviť používateľa – Commerce Engine">
    <h1 class="text-2xl font-semibold mb-6">Upraviť použí<span class="text-[#d7e600]">vateľa</span></h1>

    <div class="max-w-xl bg-[#1c1c1c] border border-[#2e2e2e] text-[#EDEDEC] rounded-lg p-6">
        <p class="mb-4"><span class="opacity-70">Meno:</span> {{ $user->name }}</p>
        <p class="mb-6"><span class="opacity-70">E-mail:</span> {{ $user->email }}</p>

        @if ($errors->any())
            <div class="mb-4 text-sm text-red-400 bg-[#141414] border border-[#2e2e2e] rounded px-3 py-2">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('users.update', $user) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="role" class="block text-sm mb-1">Rola</label>
                <select id="role" name="role"
                    class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
                    @foreach (\App\Enums\UserRole::cases() as $role)
                        <option value="{{ $role->value }}" @selected(old('role', $user->role->value) === $role->value)>
                            {{ $role->value }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2 mb-6">
                <input id="is_active" type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->is_active))>
                <label for="is_active" class="text-sm">Účet je aktívny</label>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="rounded bg-[#d7e600] text-[#1c1c1c] font-medium px-4 py-2 hover:bg-[#c3d000] transition-colors">
                    Uložiť zmeny
                </button>
                <a href="{{ route('users.index') }}" class="text-sm text-[#EDEDEC] opacity-70 hover:opacity-100">Zrušiť</a>
            </div>
        </form>
    </div>
</x-layouts.app>
