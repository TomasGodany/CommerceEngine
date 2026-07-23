<x-layouts.app title="Dashboard – Commerce Engine">
    <h1 class="text-2xl font-semibold mb-6">Dash<span class="text-[#d7e600]">board</span></h1>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('products.index') }}" class="p-4 rounded-lg bg-[#1c1c1c] border border-[#2e2e2e] text-[#EDEDEC] border-l-4 border-l-[#d7e600] hover:bg-[#242424] transition-colors">
            <p class="text-sm opacity-70">Produkty</p>
            <p class="text-2xl font-semibold">{{ $productsCount }}</p>
        </a>

        <a href="{{ route('categories.index') }}" class="p-4 rounded-lg bg-[#1c1c1c] border border-[#2e2e2e] text-[#EDEDEC] border-l-4 border-l-[#d7e600] hover:bg-[#242424] transition-colors">
            <p class="text-sm opacity-70">Kategórie</p>
            <p class="text-2xl font-semibold">{{ $categoriesCount }}</p>
        </a>

        <a href="{{ route('brands.index') }}" class="p-4 rounded-lg bg-[#1c1c1c] border border-[#2e2e2e] text-[#EDEDEC] border-l-4 border-l-[#d7e600] hover:bg-[#242424] transition-colors">
            <p class="text-sm opacity-70">Značky</p>
            <p class="text-2xl font-semibold">{{ $brandsCount }}</p>
        </a>

        @if (auth()->user()->isAdmin())
            <a href="{{ route('users.index') }}" class="p-4 rounded-lg bg-[#1c1c1c] border border-[#2e2e2e] text-[#EDEDEC] border-l-4 border-l-[#d7e600] hover:bg-[#242424] transition-colors">
                <p class="text-sm opacity-70">Používatelia</p>
                <p class="text-2xl font-semibold">{{ $usersCount }}</p>
            </a>
        @else
            <div class="p-4 rounded-lg bg-[#1c1c1c] border border-[#2e2e2e] text-[#EDEDEC] border-l-4 border-l-[#d7e600]">
                <p class="text-sm opacity-70">Používatelia</p>
                <p class="text-2xl font-semibold">{{ $usersCount }}</p>
            </div>
        @endif

        <a href="{{ route('warehouses.index') }}" class="p-4 rounded-lg bg-[#1c1c1c] border border-[#2e2e2e] text-[#EDEDEC] border-l-4 border-l-[#d7e600] hover:bg-[#242424] transition-colors">
            <p class="text-sm opacity-70">Sklady</p>
            <p class="text-2xl font-semibold">{{ $warehousesCount }}</p>
        </a>

        <a href="{{ route('orders.index') }}" class="p-4 rounded-lg bg-[#1c1c1c] border border-[#2e2e2e] text-[#EDEDEC] border-l-4 border-l-[#d7e600] hover:bg-[#242424] transition-colors">
            <p class="text-sm opacity-70">Objednávky</p>
            <p class="text-2xl font-semibold">{{ $ordersCount }}</p>
        </a>

        <a href="{{ route('customers.index') }}" class="p-4 rounded-lg bg-[#1c1c1c] border border-[#2e2e2e] text-[#EDEDEC] border-l-4 border-l-[#d7e600] hover:bg-[#242424] transition-colors">
            <p class="text-sm opacity-70">Zákazníci</p>
            <p class="text-2xl font-semibold">{{ $customersCount }}</p>
        </a>

        <a href="{{ route('coupons.index') }}" class="p-4 rounded-lg bg-[#1c1c1c] border border-[#2e2e2e] text-[#EDEDEC] border-l-4 border-l-[#d7e600] hover:bg-[#242424] transition-colors">
            <p class="text-sm opacity-70">Kupóny</p>
            <p class="text-2xl font-semibold">{{ $couponsCount }}</p>
        </a>
    </div>
</x-layouts.app>
