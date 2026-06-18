<nav x-data="{ open: false }" class="bg-white border-b-4 border-gray-900 sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('catalog.index') }}">
                        <img src="{{ asset('icon.svg') }}" alt="Logo" class="block h-10 w-auto">
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('catalog.index')" :active="request()->routeIs('catalog.*')">
                        {{ __('Katalog Kostum') }}
                    </x-nav-link>

                    @auth
                        @if(Auth::user()->role === 'admin')
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                                {{ __('Admin Dashboard') }}
                            </x-nav-link>
                        @else
                            <x-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')">
                                {{ __('Pesanan Saya') }}
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Right Side Settings & Auth Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-4 py-2 border border-gray-200 rounded-md text-sm leading-4 font-bold text-gray-900 bg-white hover:bg-white focus:outline-none transition ease-in-out duration-150 shadow-sm">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')" class="font-bold">
                                {{ __('Profil & Alamat') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();" class="font-bold text-red-600">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="space-x-4">
                        <a href="{{ route('login') }}" class="font-bold text-gray-900 hover:underline">Masuk</a>
                        <a href="{{ route('register') }}" class="inline-block bg-light-primary text-gray-900 font-extrabold px-4 py-2 border border-gray-200 rounded-md shadow-sm hover:-translate-y-1 hover:shadow-sm transition-all">Daftar</a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-900 transition duration-150 ease-in-out border-2 border-transparent">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t-2 border-gray-900">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('catalog.index')" :active="request()->routeIs('catalog.*')" class="font-bold">
                {{ __('Katalog Kostum') }}
            </x-responsive-nav-link>
            
            @auth
                @if(Auth::user()->role === 'admin')
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')" class="font-bold text-primary">
                        {{ __('Admin Dashboard') }}
                    </x-responsive-nav-link>
                @else
                    <x-responsive-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')" class="font-bold text-primary">
                        {{ __('Pesanan Saya') }}
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t-2 border-gray-900">
            @auth
                <div class="px-4">
                    <div class="font-extrabold text-base text-gray-900">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-600">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')" class="font-bold">
                        {{ __('Profil & Alamat') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();" class="font-bold text-red-600">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="mt-3 space-y-1 pb-3">
                    <x-responsive-nav-link :href="route('login')" class="font-bold">
                        {{ __('Masuk') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('register')" class="font-bold text-primary">
                        {{ __('Daftar Sekarang') }}
                    </x-responsive-nav-link>
                </div>
            @endauth
        </div>
    </div>
</nav>
