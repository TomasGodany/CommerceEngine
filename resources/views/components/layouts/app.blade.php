<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $title ?? config('app.name', 'Commerce Engine') }}</title>

        <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
        <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

        @fonts

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#f4f4f2] dark:bg-[#141414] text-[#1b1b18] dark:text-[#EDEDEC] min-h-screen">
        @auth
            <nav class="flex items-center justify-between px-6 py-4 bg-[#1c1c1c] border-b border-[#2e2e2e]">
                <a href="{{ route('dashboard') }}" class="font-semibold text-[#EDEDEC]">
                    Commerce <span class="text-[#d7e600]">Engine</span>
                </a>

                <div class="flex items-center gap-4 text-sm text-[#EDEDEC] flex-wrap">
                    <a href="{{ route('dashboard') }}" class="hover:text-[#d7e600] transition-colors">Dashboard</a>
                    <a href="{{ route('products.index') }}" class="hover:text-[#d7e600] transition-colors">Produkty</a>
                    <a href="{{ route('categories.index') }}" class="hover:text-[#d7e600] transition-colors">Kategórie</a>
                    <a href="{{ route('brands.index') }}" class="hover:text-[#d7e600] transition-colors">Značky</a>
                    <a href="{{ route('warehouses.index') }}" class="hover:text-[#d7e600] transition-colors">Sklady</a>
                    <a href="{{ route('stock-movements.index') }}" class="hover:text-[#d7e600] transition-colors">Pohyby</a>
                    <a href="{{ route('orders.index') }}" class="hover:text-[#d7e600] transition-colors">Objednávky</a>
                    <a href="{{ route('customers.index') }}" class="hover:text-[#d7e600] transition-colors">Zákazníci</a>
                    <a href="{{ route('price-lists.index') }}" class="hover:text-[#d7e600] transition-colors">Cenníky</a>
                    <a href="{{ route('discounts.index') }}" class="hover:text-[#d7e600] transition-colors">Zľavy</a>
                    <a href="{{ route('coupons.index') }}" class="hover:text-[#d7e600] transition-colors">Kupóny</a>
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('users.index') }}" class="hover:text-[#d7e600] transition-colors">Používatelia</a>
                    @endif

                    <span>{{ auth()->user()->name }} <span class="text-[#d7e600]">({{ auth()->user()->role->value }})</span></span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded bg-[#d7e600] text-[#1c1c1c] font-medium px-3 py-1.5 hover:bg-[#c3d000] transition-colors">
                            Odhlásiť sa
                        </button>
                    </form>
                </div>
            </nav>
        @endauth

        <main class="p-6">
            {{ $slot }}
        </main>
    </body>
</html>
