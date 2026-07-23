<x-layouts.app title="Nastavenia systému – Commerce Engine">
    <h1 class="text-2xl font-semibold mb-6">Nastavenia sys<span class="text-[#d7e600]">tému</span></h1>

    <div class="flex flex-wrap gap-4 mb-6 text-sm">
        <a href="{{ route('currencies.index') }}" class="text-[#d7e600] hover:underline">Meny</a>
        <a href="{{ route('languages.index') }}" class="text-[#d7e600] hover:underline">Jazyky</a>
        <a href="{{ route('tax-rates.index') }}" class="text-[#d7e600] hover:underline">Daňové sadzby</a>
        <a href="{{ route('email-templates.index') }}" class="text-[#d7e600] hover:underline">E-mailové šablóny</a>
    </div>

    @if (session('status'))
        <div class="mb-4 text-sm text-[#d7e600] bg-[#1c1c1c] border border-[#2e2e2e] rounded px-3 py-2">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('settings.update') }}" class="max-w-3xl bg-[#1c1c1c] border border-[#2e2e2e] text-[#EDEDEC] rounded-lg p-6">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="mb-4 text-sm text-red-400 bg-[#141414] border border-[#2e2e2e] rounded px-3 py-2">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h2 class="text-lg font-semibold mb-4">Firemné údaje</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="company_name" class="block text-sm mb-1">Názov spoločnosti</label>
                <input id="company_name" type="text" name="company_name" value="{{ old('company_name', $setting->company_name) }}" required
                    class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
            </div>

            <div>
                <label for="company_street" class="block text-sm mb-1">Ulica</label>
                <input id="company_street" type="text" name="company_street" value="{{ old('company_street', $setting->company_street) }}" required
                    class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
            </div>

            <div>
                <label for="company_city" class="block text-sm mb-1">Mesto</label>
                <input id="company_city" type="text" name="company_city" value="{{ old('company_city', $setting->company_city) }}" required
                    class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
            </div>

            <div>
                <label for="company_country" class="block text-sm mb-1">Krajina</label>
                <input id="company_country" type="text" name="company_country" value="{{ old('company_country', $setting->company_country) }}" required
                    class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
            </div>

            <div>
                <label for="ico" class="block text-sm mb-1">IČO</label>
                <input id="ico" type="text" name="ico" value="{{ old('ico', $setting->ico) }}" required
                    class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
            </div>

            <div>
                <label for="dic" class="block text-sm mb-1">DIČ</label>
                <input id="dic" type="text" name="dic" value="{{ old('dic', $setting->dic) }}" required
                    class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
            </div>

            <div>
                <label for="ic_dph" class="block text-sm mb-1">IČ DPH</label>
                <input id="ic_dph" type="text" name="ic_dph" value="{{ old('ic_dph', $setting->ic_dph) }}"
                    class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
            </div>

            <div>
                <label for="default_currency_code" class="block text-sm mb-1">Predvolená mena</label>
                <input id="default_currency_code" type="text" name="default_currency_code" value="{{ old('default_currency_code', $setting->default_currency_code) }}"
                    class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
            </div>
        </div>

        <h2 class="text-lg font-semibold mt-6 mb-4">Platobné a kontaktné údaje</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="iban" class="block text-sm mb-1">IBAN</label>
                <input id="iban" type="text" name="iban" value="{{ old('iban', $setting->iban) }}" required
                    class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
            </div>

            <div>
                <label for="bic" class="block text-sm mb-1">BIC</label>
                <input id="bic" type="text" name="bic" value="{{ old('bic', $setting->bic) }}" required
                    class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
            </div>

            <div>
                <label for="email" class="block text-sm mb-1">E-mail</label>
                <input id="email" type="email" name="email" value="{{ old('email', $setting->email) }}" required
                    class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
            </div>

            <div>
                <label for="phone" class="block text-sm mb-1">Telefón</label>
                <input id="phone" type="text" name="phone" value="{{ old('phone', $setting->phone) }}" required
                    class="w-full rounded border border-[#3a3a3a] bg-[#141414] text-[#EDEDEC] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#d7e600]">
            </div>
        </div>

        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="rounded bg-[#d7e600] text-[#1c1c1c] font-medium px-4 py-2 hover:bg-[#c3d000] transition-colors">
                Uložiť nastavenia
            </button>
        </div>
    </form>
</x-layouts.app>
