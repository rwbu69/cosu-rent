<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="robots" content="noindex, nofollow">
        <title>{{ $title ?? 'Admin Dashboard' }} - {{ config('app.name', 'CosuRent') }}</title>
        <link rel="icon" href="{{ asset('icon.svg') }}" type="image/svg+xml">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900 bg-white flex h-screen overflow-hidden selection:bg-primary selection:text-white">
        
        <!-- Sidebar -->
        <aside class="w-64 bg-white flex-shrink-0 flex flex-col h-full z-20 border-r border-gray-100">
            <!-- Logo Area -->
            <div class="h-16 flex items-center px-6 border-b border-gray-100">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <img src="{{ asset('icon.svg') }}" alt="Logo" class="h-8 w-auto">
                    <span class="text-gray-900 font-light text-xl tracking-widest uppercase">Admin</span>
                </a>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                <p class="px-4 text-xs font-light text-gray-400 uppercase tracking-widest mb-6">Menu Utama</p>
                
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 font-light transition-colors {{ request()->routeIs('admin.dashboard') ? 'text-primary bg-primary/5 rounded-md' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50 rounded-md' }} uppercase tracking-widest text-sm">
                    Dasbor Utama
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="block px-4 py-3 font-light transition-colors {{ request()->routeIs('admin.bookings.index') ? 'text-primary bg-primary/5 rounded-md' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50 rounded-md' }} uppercase tracking-widest text-sm">
                    Pesanan Aktif
                </a>
                <a href="{{ route('admin.bookings.history') }}" class="block px-4 py-3 font-light transition-colors {{ request()->routeIs('admin.bookings.history') ? 'text-primary bg-primary/5 rounded-md' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50 rounded-md' }} uppercase tracking-widest text-sm">
                    Riwayat Pesanan
                </a>
                <a href="{{ route('admin.return.index') }}" class="block px-4 py-3 font-light transition-colors {{ request()->routeIs('admin.return.*') ? 'text-primary bg-primary/5 rounded-md' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50 rounded-md' }} uppercase tracking-widest text-sm">
                    Pengembalian & QC
                </a>
                <a href="{{ route('admin.katalog.index') }}" class="block px-4 py-3 font-light transition-colors {{ request()->routeIs('admin.katalog.*') ? 'text-primary bg-primary/5 rounded-md' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50 rounded-md' }} uppercase tracking-widest text-sm">
                    Manajemen Katalog
                </a>
                <a href="{{ route('admin.kiosk.index') }}" class="block px-4 py-3 font-light transition-colors {{ request()->routeIs('admin.kiosk.*') ? 'text-primary bg-primary/5 rounded-md' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50 rounded-md' }} uppercase tracking-widest text-sm">
                    Kiosk RFID
                </a>
                <a href="{{ route('admin.users.index') }}" class="block px-4 py-3 font-light transition-colors {{ request()->routeIs('admin.users.*') ? 'text-primary bg-primary/5 rounded-md' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50 rounded-md' }} uppercase tracking-widest text-sm">
                    Data Pelanggan
                </a>
                <a href="{{ route('admin.report.index') }}" class="block px-4 py-3 font-light transition-colors {{ request()->routeIs('admin.report.*') ? 'text-primary bg-primary/5 rounded-md' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50 rounded-md' }} uppercase tracking-widest text-sm">
                    Laporan Keuangan
                </a>
            </nav>

            <!-- Bottom Area: Logout -->
            <div class="p-4 border-t border-gray-100">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-center block px-4 py-3 font-light transition-colors text-red-500 hover:text-red-700 hover:bg-red-50 rounded-md uppercase tracking-widest text-sm">
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col h-full overflow-hidden bg-gray-50">
            
            <!-- Topbar -->
            <header class="h-16 bg-white border-b border-gray-100 flex items-center justify-between px-6 z-10 flex-shrink-0">
                <!-- Breadcrumbs/Title -->
                <div class="font-light text-gray-900 text-xl tracking-widest">
                    {{ $title ?? 'Manajemen Sistem' }}
                </div>

                <!-- Right Side (Profile & Scanner Status) -->
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-2 text-xs font-light tracking-widest text-primary px-3 py-1.5 border border-primary/20 rounded-full bg-primary/5">
                        <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        Scanner Aktif
                    </div>
                </div>
            </header>

            <!-- Scrollable Content Area -->
            <main class="flex-1 overflow-y-auto p-6 md:p-10 bg-white relative">
                <!-- Dot Pattern Background Overlay for style -->
                <div class="relative">
                    {{ $slot }}
                </div>
            </main>
        </div>

        <x-modal-confirm />
        <x-toast />
    </body>
</html>
