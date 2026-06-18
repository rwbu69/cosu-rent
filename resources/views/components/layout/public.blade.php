<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" href="{{ asset('icon.svg') }}" type="image/svg+xml">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="flex flex-col min-h-screen font-sans antialiased text-gray-800 bg-white">

        <!-- Sticky Navbar -->
        <nav x-data="{ open: false }" class="sticky top-0 z-50 bg-white border-b border-gray-100">
            <div class="px-6 mx-auto max-w-7xl sm:px-8 lg:px-10">
                <div class="flex justify-between h-16 relative">
                    <!-- Left: Logo & Brand -->
                    <div class="flex items-center">
                        <a href="{{ route('catalog.index') }}" class="flex items-center gap-3">
                            <img src="{{ asset('icon.svg') }}" alt="Logo" class="w-auto h-8">
                            <span class="hidden text-xl font-light tracking-widest text-gray-900 sm:block uppercase">CosuRent</span>
                        </a>
                    </div>

                    <!-- Center: Navigation Links -->
                    <div class="hidden md:flex absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2 space-x-8">
                        <a href="{{ route('catalog.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('catalog.*') || request()->is('/') ? 'border-primary text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Katalog
                        </a>
                        @auth
                            @if(Auth::user()->role !== 'admin')
                                <a href="{{ route('orders.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('orders.*') ? 'border-primary text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                    Pesanan Saya
                                </a>
                            @endif
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-1 pt-1 font-bold leading-5 text-gray-500 transition duration-150 ease-in-out border-b-2 border-transparent hover:text-gray-900">
                                    Area Admin
                                </a>
                            @endif
                        @endauth
                    </div>

                    <!-- Right Navigation -->
                    <div class="items-center hidden space-x-6 md:flex">
                        @auth
                            @php
                                $cartCount = \App\Models\Cart::where('user_id', Auth::id())->withCount('items')->first()?->items_count ?? 0;
                            @endphp
                            <!-- Cart Icon -->
                            <a href="{{ route('checkout.index') }}" class="relative text-gray-500 transition-colors hover:text-primary" title="Keranjang">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                @if($cartCount > 0)
                                    <span class="absolute -top-1 -right-2 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $cartCount }}</span>
                                @endif
                            </a>

                            <!-- Orders Icon -->
                            <a href="{{ route('orders.index') }}" class="relative text-gray-900 hover:text-primary transition-colors" title="Pesanan Saya">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            </a>

                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="flex items-center gap-2 font-medium text-gray-600 transition-colors hover:text-gray-900 focus:outline-none">
                                        <span>{{ Auth::user()->name }}</span>
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link :href="route('profile.edit')">
                                        {{ __('Profil Saya') }}
                                    </x-dropdown-link>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-red-600">
                                            {{ __('Keluar') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-light tracking-widest text-gray-500 uppercase hover:text-primary transition-colors">Masuk</a>
                            <a href="{{ route('register') }}" class="px-6 py-2 text-sm font-light tracking-widest text-gray-500 uppercase transition-colors border border-gray-300 hover:border-primary hover:text-primary">
                                Daftar
                            </a>
                        @endauth
                    </div>

                    <!-- Mobile Hamburger -->
                    <div class="flex items-center md:hidden">
                        <button @click="open = ! open" class="p-2 text-gray-500 hover:text-gray-700 focus:outline-none">
                            <svg class="w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div :class="{'block': open, 'hidden': ! open}" class="hidden border-t border-gray-200 md:hidden">
                <div class="pt-2 pb-3 space-y-1">
                    <a href="{{ route('catalog.index') }}" class="block px-6 py-2 text-base font-medium {{ request()->routeIs('catalog.*') ? 'text-primary bg-primary/10' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">Katalog</a>
                    @auth
                        @if(Auth::user()->role !== 'admin')
                            <a href="{{ route('orders.index') }}" class="block px-6 py-2 text-base font-medium {{ request()->routeIs('orders.*') ? 'text-primary bg-primary/10' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">Pesanan Saya</a>
                        @else
                            <a href="{{ route('admin.dashboard') }}" class="block px-6 py-2 text-base font-medium text-gray-600 hover:text-primary hover:bg-gray-50">Ke Area Admin</a>
                        @endif
                    @endauth
                </div>
                <div class="pt-4 pb-4 border-t border-gray-200">
                    @auth
                        <div class="px-6 mb-3">
                            <div class="text-base font-medium text-gray-800">{{ Auth::user()->name }}</div>
                            <div class="text-sm font-medium text-gray-500">{{ Auth::user()->email }}</div>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="block px-6 py-2 text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50">Profil Saya</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full px-6 py-2 text-base font-medium text-left text-red-600 hover:bg-gray-50">Keluar</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block px-6 py-3 text-base font-bold text-gray-900 hover:bg-gray-50">Masuk</a>
                        <a href="{{ route('register') }}" class="block px-6 py-3 text-base font-bold bg-gray-900 text-white hover:bg-primary hover:text-gray-900 transition-colors">Daftar</a>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Main Content (Generous padding: p-6 to p-10 applied via wrapper in views, or here) -->
        <main class="flex-grow">
            {{ $slot }}
        </main>



        <x-modal-confirm />
        <x-toast />
    </body>
</html>
