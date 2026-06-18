<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Favicon -->
        <link rel="icon" href="{{ asset('icon.svg') }}" type="image/svg+xml">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-800">
        <div class="min-h-screen bg-white flex flex-col">
            @include('layouts.navigation')

            <div class="flex-grow flex flex-col md:flex-row">
                
                <!-- Sidebar for Admin Pages -->
                @if(Auth::check() && Auth::user()->role === 'admin' && request()->routeIs('admin.*'))
                    <aside class="w-full md:w-64 bg-white border-b-4 md:border-r-4 md:border-b-0 border-gray-900 flex-shrink-0 z-10">
                        <nav class="flex flex-col p-6 space-y-4 font-bold text-lg">
                            <p class="text-xs font-extrabold text-gray-400 uppercase tracking-wider mb-2">Admin Menu</p>
                            
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 border-2 border-transparent hover:border-gray-900 hover:bg-white transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-gray-900 border-gray-900 shadow-[4px_4px_0px_0px_rgba(17,24,39,1)]' : 'text-gray-600' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('admin.bookings.index') }}" class="block px-4 py-2 border-2 border-transparent hover:border-gray-900 hover:bg-white transition-colors {{ request()->routeIs('admin.bookings.*') ? 'bg-primary text-gray-900 border-gray-900 shadow-[4px_4px_0px_0px_rgba(17,24,39,1)]' : 'text-gray-600' }}">
                                Pesanan Masuk
                            </a>
                            <a href="{{ route('admin.return.index') }}" class="block px-4 py-2 border-2 border-transparent hover:border-gray-900 hover:bg-white transition-colors {{ request()->routeIs('admin.return.*') ? 'bg-primary text-gray-900 border-gray-900 shadow-[4px_4px_0px_0px_rgba(17,24,39,1)]' : 'text-gray-600' }}">
                                QC Return
                            </a>
                            <a href="{{ route('admin.katalog.index') }}" class="block px-4 py-2 border-2 border-transparent hover:border-gray-900 hover:bg-white transition-colors {{ request()->routeIs('admin.katalog.*') ? 'bg-primary text-gray-900 border-gray-900 shadow-[4px_4px_0px_0px_rgba(17,24,39,1)]' : 'text-gray-600' }}">
                                Katalog Kostum
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 border-2 border-transparent hover:border-gray-900 hover:bg-white transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-primary text-gray-900 border-gray-900 shadow-[4px_4px_0px_0px_rgba(17,24,39,1)]' : 'text-gray-600' }}">
                                Data Pelanggan
                            </a>
                            <a href="{{ route('admin.report.index') }}" class="block px-4 py-2 border-2 border-transparent hover:border-gray-900 hover:bg-white transition-colors {{ request()->routeIs('admin.report.*') ? 'bg-primary text-gray-900 border-gray-900 shadow-[4px_4px_0px_0px_rgba(17,24,39,1)]' : 'text-gray-600' }}">
                                Laporan Keuangan
                            </a>
                        </nav>
                    </aside>
                @endif

                <!-- Main Content Area -->
                <div class="flex-grow flex flex-col w-full overflow-x-hidden">
                    <!-- Page Heading -->
                    @isset($header)
                        <header class="bg-white border-b-2 border-gray-900">
                            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <!-- Page Content -->
                    <main class="flex-grow">
                        {{ $slot }}
                    </main>
                </div>
                
            </div>
        </div>

        <!-- Global Toast Notification -->
        <x-toast />
    </body>
</html>
