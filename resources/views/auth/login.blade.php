<x-layouts.app title="Prihlásenie – Commerce Engine">
    <div class="max-w-sm mx-auto mt-16 p-6 rounded-lg bg-[#1c1c1c] border border-[#2e2e2e] text-[#EDEDEC]">
        <h1 class="text-xl font-semibold mb-6">Prihlásenie do <span class="text-[#d7e600]">administrácie</span></h1>

        @if ($errors->any())
            <div class="mb-4 text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm mb-1">E-mail</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
            </div>

            <div>
                <label for="password" class="block text-sm mb-1">Heslo</label>
                <input id="password" type="password" name="password" required
                    class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
            </div>

            <div class="flex items-center gap-2 text-sm">
                <input id="remember" type="checkbox" name="remember">
                <label for="remember">Zapamätať si ma</label>
            </div>

            <button type="submit" class="w-full rounded bg-[#d7e600] text-[#1c1c1c] font-medium px-3 py-2 hover:bg-[#c3d000] transition-colors">
                Prihlásiť sa
            </button>
        </form>

        <p class="mt-4 text-sm">
            Nemáte účet? <a href="{{ route('register') }}" class="text-[#d7e600] underline">Zaregistrujte sa</a>
        </p>
    </div>
</x-layouts.app>
