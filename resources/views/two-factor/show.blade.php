<x-layouts.app title="Dvojfaktorové overenie – Commerce Engine">
    <h1 class="text-2xl font-semibold mb-6">Dvojfaktorové <span class="text-[#d7e600]">overenie</span></h1>

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

    <div class="max-w-xl bg-[#1c1c1c] border border-[#2e2e2e] text-[#EDEDEC] rounded-lg p-6">
        @if ($user->hasTwoFactorEnabled())
            <p class="mb-4">Dvojfaktorové overenie je pre váš účet <span class="text-[#d7e600]">zapnuté</span>.</p>
            <p class="mb-6 text-sm opacity-70">Pre vypnutie zadajte aktuálny overovací kód z vašej autentifikačnej aplikácie.</p>

            <form method="POST" action="{{ route('two-factor.disable') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="code" class="block text-sm mb-1">Overovací kód</label>
                    <input id="code" type="text" name="code" inputmode="numeric" autocomplete="one-time-code" required autofocus
                        class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
                </div>

                <button type="submit" class="rounded bg-[#d7e600] text-[#1c1c1c] font-medium px-4 py-2 hover:bg-[#c3d000] transition-colors">
                    Vypnúť dvojfaktorové overenie
                </button>
            </form>
        @else
            <p class="mb-4">Dvojfaktorové overenie je pre váš účet <span class="text-[#d7e600]">vypnuté</span>.</p>
            <p class="mb-6 text-sm opacity-70">Naskenujte QR kód pomocou autentifikačnej aplikácie (napr. Google Authenticator) a zadajte vygenerovaný kód pre potvrdenie.</p>

            <div class="flex justify-center mb-6">
                {!! $qrCodeUrl ? QrCode::size(200)->generate($qrCodeUrl) : '' !!}
            </div>

            <form method="POST" action="{{ route('two-factor.enable') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="code" class="block text-sm mb-1">Overovací kód</label>
                    <input id="code" type="text" name="code" inputmode="numeric" autocomplete="one-time-code" required autofocus
                        class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
                </div>

                <button type="submit" class="rounded bg-[#d7e600] text-[#1c1c1c] font-medium px-4 py-2 hover:bg-[#c3d000] transition-colors">
                    Zapnúť dvojfaktorové overenie
                </button>
            </form>
        @endif
    </div>
</x-layouts.app>
