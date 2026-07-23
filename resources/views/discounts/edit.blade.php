<x-layouts.app title="Upraviť zľavu – Commerce Engine">
    <h1 class="text-2xl font-semibold mb-6">Upraviť zľ<span class="text-[#d7e600]">avu</span></h1>

    <form method="POST" action="{{ route('discounts.update', $discount) }}" class="max-w-3xl bg-[#1c1c1c] border border-[#2e2e2e] text-[#EDEDEC] rounded-lg p-6">
        @csrf
        @method('PUT')

        @include('discounts._form')

        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="rounded bg-[#d7e600] text-[#1c1c1c] font-medium px-4 py-2 hover:bg-[#c3d000] transition-colors">
                Uložiť zmeny
            </button>
            <a href="{{ route('discounts.index') }}" class="text-sm text-[#EDEDEC] opacity-70 hover:opacity-100">Zrušiť</a>
        </div>
    </form>
</x-layouts.app>
