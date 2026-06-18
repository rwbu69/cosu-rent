<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel Admin') }}</title>
        <link rel="icon" href="{{ asset('icon.svg') }}" type="image/svg+xml">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900 bg-white flex h-screen overflow-hidden selection:bg-primary selection:text-white">
        
        <!-- Sidebar -->
        <aside class="w-64 bg-slate-900 flex-shrink-0 flex flex-col h-full z-20 shadow-xl border-r border-slate-800">
            <!-- Logo Area -->
            <div class="h-16 flex items-center px-6 border-b-2 border-slate-800 bg-slate-900">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <img src="{{ asset('icon.svg') }}" alt="Logo" class="h-8 w-auto">
                    <span class="text-white font-black text-xl tracking-widest uppercase">Admin</span>
                </a>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-3 overflow-y-auto">
                <p class="px-2 text-xs font-black text-slate-500 uppercase tracking-widest mb-6">Menu Utama</p>
                
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 font-bold transition-all border-2 {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-gray-900 border-primary shadow-[4px_4px_0_0_rgba(255,46,99,0.5)] -translate-y-0.5' : 'border-slate-700 text-slate-300 hover:border-slate-500 hover:text-white hover:bg-slate-800' }} uppercase tracking-wider text-sm">
                    Dasbor Utama
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="block px-4 py-3 font-bold transition-all border-2 {{ request()->routeIs('admin.bookings.*') ? 'bg-primary text-gray-900 border-primary shadow-[4px_4px_0_0_rgba(255,46,99,0.5)] -translate-y-0.5' : 'border-slate-700 text-slate-300 hover:border-slate-500 hover:text-white hover:bg-slate-800' }} uppercase tracking-wider text-sm">
                    Pesanan Masuk
                </a>
                <a href="{{ route('admin.return.index') }}" class="block px-4 py-3 font-bold transition-all border-2 {{ request()->routeIs('admin.return.*') ? 'bg-primary text-gray-900 border-primary shadow-[4px_4px_0_0_rgba(255,46,99,0.5)] -translate-y-0.5' : 'border-slate-700 text-slate-300 hover:border-slate-500 hover:text-white hover:bg-slate-800' }} uppercase tracking-wider text-sm">
                    Pengembalian & QC
                </a>
                <a href="{{ route('admin.katalog.index') }}" class="block px-4 py-3 font-bold transition-all border-2 {{ request()->routeIs('admin.katalog.*') ? 'bg-primary text-gray-900 border-primary shadow-[4px_4px_0_0_rgba(255,46,99,0.5)] -translate-y-0.5' : 'border-slate-700 text-slate-300 hover:border-slate-500 hover:text-white hover:bg-slate-800' }} uppercase tracking-wider text-sm">
                    Manajemen Katalog
                </a>
                <a href="{{ route('admin.users.index') }}" class="block px-4 py-3 font-bold transition-all border-2 {{ request()->routeIs('admin.users.*') ? 'bg-primary text-gray-900 border-primary shadow-[4px_4px_0_0_rgba(255,46,99,0.5)] -translate-y-0.5' : 'border-slate-700 text-slate-300 hover:border-slate-500 hover:text-white hover:bg-slate-800' }} uppercase tracking-wider text-sm">
                    Data Pelanggan
                </a>
                <a href="{{ route('admin.report.index') }}" class="block px-4 py-3 font-bold transition-all border-2 {{ request()->routeIs('admin.report.*') ? 'bg-primary text-gray-900 border-primary shadow-[4px_4px_0_0_rgba(255,46,99,0.5)] -translate-y-0.5' : 'border-slate-700 text-slate-300 hover:border-slate-500 hover:text-white hover:bg-slate-800' }} uppercase tracking-wider text-sm">
                    Laporan Keuangan
                </a>
            </nav>

            <!-- Bottom Area: Logout -->
            <div class="p-4 border-t border-slate-800">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-center block px-4 py-3 font-bold transition-all border-2 border-slate-700 text-red-400 hover:border-red-500 hover:text-white hover:bg-red-600 uppercase tracking-wider text-sm">
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col h-full overflow-hidden bg-gray-50">
            
            <!-- Topbar -->
            <header class="h-16 bg-white border-b-2 border-gray-900 flex items-center justify-between px-6 z-10 flex-shrink-0">
                <!-- Breadcrumbs/Title -->
                <div class="font-black text-gray-900 text-xl uppercase tracking-widest">
                    {{ $title ?? 'Manajemen Sistem' }}
                </div>

                <!-- Right Side (Profile & Scanner Status) -->
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-2 text-xs font-black uppercase tracking-widest text-gray-900 bg-white px-3 py-1.5 border-2 border-gray-900 shadow-[2px_2px_0_0_rgba(17,24,39,1)]">
                        <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="square" stroke-linejoin="miter" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        Scanner Aktif
                    </div>
                </div>
            </header>

            <!-- Scrollable Content Area -->
            <main class="flex-1 overflow-y-auto p-6 md:p-10 bg-white relative">
                <!-- Dot Pattern Background Overlay for style -->
                <div class="absolute inset-0 z-0 opacity-20 pointer-events-none" style="background-image: radial-gradient(#111827 1px, transparent 1px); background-size: 20px 20px;"></div>
                <div class="relative z-10">
                    {{ $slot }}
                </div>
            </main>
        </div>

        <x-modal-confirm />
        <x-toast />
    </body>
</html>
