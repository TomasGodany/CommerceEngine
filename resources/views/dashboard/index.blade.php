<x-layouts.app title="Dashboard – Commerce Engine">
    <h1 class="text-2xl font-semibold mb-6">Dash<span class="text-[#d7e600]">board</span></h1>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="p-4 rounded-lg bg-[#1c1c1c] border border-[#2e2e2e] text-[#EDEDEC] border-l-4 border-l-[#d7e600]">
            <p class="text-sm opacity-70">Produkty</p>
            <p class="text-2xl font-semibold">{{ $productsCount }}</p>
        </div>

        <div class="p-4 rounded-lg bg-[#1c1c1c] border border-[#2e2e2e] text-[#EDEDEC] border-l-4 border-l-[#d7e600]">
            <p class="text-sm opacity-70">Kategórie</p>
            <p class="text-2xl font-semibold">{{ $categoriesCount }}</p>
        </div>

        <div class="p-4 rounded-lg bg-[#1c1c1c] border border-[#2e2e2e] text-[#EDEDEC] border-l-4 border-l-[#d7e600]">
            <p class="text-sm opacity-70">Značky</p>
            <p class="text-2xl font-semibold">{{ $brandsCount }}</p>
        </div>

        <div class="p-4 rounded-lg bg-[#1c1c1c] border border-[#2e2e2e] text-[#EDEDEC] border-l-4 border-l-[#d7e600]">
            <p class="text-sm opacity-70">Používatelia</p>
            <p class="text-2xl font-semibold">{{ $usersCount }}</p>
        </div>
    </div>
</x-layouts.app>
