<x-layouts.app title="Upraviť produkt – Commerce Engine">
    <h1 class="text-2xl font-semibold mb-6">Upraviť pro<span class="text-[#d7e600]">dukt</span></h1>

    <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data" class="max-w-3xl bg-[#1c1c1c] border border-[#2e2e2e] text-[#EDEDEC] rounded-lg p-6">
        @csrf
        @method('PUT')

        @include('products._form')

        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="rounded bg-[#d7e600] text-[#1c1c1c] font-medium px-4 py-2 hover:bg-[#c3d000] transition-colors">
                Uložiť zmeny
            </button>
            <a href="{{ route('products.index') }}" class="text-sm text-[#EDEDEC] opacity-70 hover:opacity-100">Zrušiť</a>
            <a href="{{ route('products.label', $product) }}" target="_blank" class="ml-auto rounded border border-[#3a3a3a] text-[#EDEDEC] px-4 py-2 hover:bg-[#2a2a2a] transition-colors">
                Vytlačiť štítok
            </a>
        </div>
    </form>
</x-layouts.app>
