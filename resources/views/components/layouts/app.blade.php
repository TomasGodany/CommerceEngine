<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $title ?? config('app.name', 'Commerce Engine') }}</title>

        @fonts

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#f4f4f2] dark:bg-[#141414] text-[#1b1b18] dark:text-[#EDEDEC] min-h-screen">
        @auth
            <nav class="flex items-center justify-between px-6 py-4 bg-[#1c1c1c] border-b border-[#2e2e2e]">
                <a href="{{ route('dashboard') }}" class="font-semibold text-[#EDEDEC]">
                    Commerce <span class="text-[#d7e600]">Engine</span>
                </a>

                <div class="flex items-center gap-4 text-sm text-[#EDEDEC]">
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
