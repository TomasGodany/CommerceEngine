<x-layouts.app title="Overenie – Commerce Engine">
    <div class="max-w-sm mx-auto mt-16 p-6 rounded-lg bg-[#1c1c1c] border border-[#2e2e2e] text-[#EDEDEC]">
        <h1 class="text-xl font-semibold mb-6">Dvojfaktorové <span class="text-[#d7e600]">overenie</span></h1>

        <p class="text-sm mb-4">Zadajte overovací kód z vašej autentifikačnej aplikácie.</p>

        @if ($errors->any())
            <div class="mb-4 text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('two-factor.challenge.store') }}" class="space-y-4">
            @csrf

            <div>
                <label for="code" class="block text-sm mb-1">Overovací kód</label>
                <input id="code" type="text" name="code" inputmode="numeric" autocomplete="one-time-code" required autofocus
                    class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
            </div>

            <button type="submit" class="w-full rounded bg-[#d7e600] text-[#1c1c1c] font-medium px-3 py-2 hover:bg-[#c3d000] transition-colors">
                Overiť
            </button>
        </form>
    </div>
</x-layouts.app>
