<x-layout.admin>
    <x-slot name="title">Dasbor Utama</x-slot>

    <div class="space-y-6">
        
        <!-- Stat Cards -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            <div class="bg-white p-4 border border-gray-200 rounded-sm shadow-sm flex flex-col justify-center">
                <h3 class="text-gray-500 font-bold text-[10px] sm:text-xs uppercase tracking-wider">Pesanan Masuk</h3>
                <p class="text-2xl sm:text-3xl font-extrabold text-yellow-500 mt-1">{{ $incomingBookings->count() }}</p>
            </div>
            <div class="bg-white p-4 border border-gray-200 rounded-sm shadow-sm flex flex-col justify-center">
                <h3 class="text-gray-500 font-bold text-[10px] sm:text-xs uppercase tracking-wider">Sedang Dirental</h3>
                <p class="text-2xl sm:text-3xl font-extrabold text-primary mt-1">{{ $activeBookings->count() }}</p>
            </div>
            <div class="bg-white p-4 border border-gray-200 rounded-sm shadow-sm flex flex-col justify-center">
                <h3 class="text-gray-500 font-bold text-[10px] sm:text-xs uppercase tracking-wider">Dikirim ke Pelanggan</h3>
                <p class="text-2xl sm:text-3xl font-extrabold text-blue-600 mt-1">{{ $shippingOut->count() }}</p>
            </div>
            <div class="bg-white p-4 border border-gray-200 rounded-sm shadow-sm flex flex-col justify-center">
                <h3 class="text-gray-500 font-bold text-[10px] sm:text-xs uppercase tracking-wider">Dikirim Kembali</h3>
                <p class="text-2xl sm:text-3xl font-extrabold text-purple-600 mt-1">{{ $shippingReturn->count() }}</p>
            </div>
            <div class="bg-white p-4 border border-gray-200 rounded-sm shadow-sm flex flex-col justify-center col-span-2 lg:col-span-1">
                <h3 class="text-gray-500 font-bold text-[10px] sm:text-xs uppercase tracking-wider">Tersedia (Siap)</h3>
                <p class="text-2xl sm:text-3xl font-extrabold text-green-600 mt-1">{{ $availableCostumes->count() }}</p>
            </div>
        </div>

        <!-- Detailed Lists (3 Columns on Large Screens) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Column 1: Pesanan Baru -->
            <div class="bg-white border border-gray-200 rounded-sm shadow-sm flex flex-col h-80">
                <div class="bg-white px-4 py-3 border-b border-gray-200 flex justify-between items-center shrink-0">
                    <h3 class="font-extrabold text-gray-900 text-sm">Pesanan Baru</h3>
                    <a href="{{ route('admin.bookings.index') }}" class="text-[10px] font-bold text-primary hover:text-[#E5A5B0]">Lihat Semua</a>
                </div>
                <div class="overflow-y-auto flex-1 p-2">
                    <ul class="divide-y divide-gray-100">
                        @forelse($incomingBookings as $booking)
                            <li class="p-2 hover:bg-gray-50 transition-colors rounded-sm">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-bold text-gray-900 text-sm">{{ $booking->user->name }}</p>
                                        <p class="text-[11px] text-gray-500">{{ $booking->costume->name }}</p>
                                    </div>
                                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-sm {{ $booking->status === 'Menunggu Konfirmasi' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800' }}">{{ $booking->status }}</span>
                                </div>
                            </li>
                        @empty
                            <li class="p-4 text-gray-400 italic text-center text-xs">Kosong</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Column 2: Sedang Dirental -->
            <div class="bg-white border border-gray-200 rounded-sm shadow-sm flex flex-col h-80">
                <div class="bg-white px-4 py-3 border-b border-gray-200 shrink-0">
                    <h3 class="font-extrabold text-gray-900 text-sm">Sedang Dirental</h3>
                </div>
                <div class="overflow-y-auto flex-1 p-2" x-data="{ expandedId: null }">
                    @if($activeBookings->isEmpty())
                        <p class="p-4 text-gray-400 italic text-center text-xs">Kosong</p>
                    @else
                        <div class="space-y-2">
                            @foreach($activeBookings as $booking)
                                <div class="border border-gray-100 rounded-sm p-2 bg-gray-50">
                                    <div class="flex justify-between items-center cursor-pointer" @click="expandedId = expandedId === {{ $booking->id }} ? null : {{ $booking->id }}">
                                        <div>
                                            <p class="font-bold text-gray-900 text-sm">{{ $booking->user->name }}</p>
                                            <p class="text-[11px] text-gray-500">{{ $booking->costume->name }}</p>
                                        </div>
                                        <svg class="w-4 h-4 text-gray-400 transition-transform" :class="{'rotate-180': expandedId === {{ $booking->id }}}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                    <div x-show="expandedId === {{ $booking->id }}" class="mt-2 pt-2 border-t border-gray-200" style="display: none;">
                                        <p class="text-[10px] font-bold text-gray-700 mb-1">Komponen dibawa:</p>
                                        <ul class="list-disc list-inside text-[11px] text-gray-600">
                                            @foreach($booking->costume->components as $comp)
                                                <li>{{ $comp->name }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Column 3: Pengiriman & QC -->
            <div class="flex flex-col gap-6 h-80">
                
                <!-- Dikirim ke Customer -->
                <div class="bg-white border border-gray-200 rounded-sm shadow-sm flex flex-col flex-1 min-h-0">
                    <div class="bg-white px-4 py-2 border-b border-gray-200 shrink-0">
                        <h3 class="font-extrabold text-gray-900 text-xs">Sedang Dikirim</h3>
                    </div>
                    <div class="overflow-y-auto flex-1 p-1">
                        <ul class="divide-y divide-gray-100">
                            @forelse($shippingOut as $booking)
                                <li class="p-2 flex justify-between items-center">
                                    <div>
                                        <p class="font-bold text-gray-900 text-[11px]">{{ $booking->user->name }}</p>
                                        <p class="text-[10px] text-gray-500">{{ $booking->costume->name }}</p>
                                    </div>
                                </li>
                            @empty
                                <li class="p-2 text-gray-400 italic text-center text-[10px]">Kosong</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <!-- Dikirim Kembali (QC) -->
                <div class="bg-white border border-gray-200 rounded-sm shadow-sm flex flex-col flex-1 min-h-0">
                    <div class="bg-white px-4 py-2 border-b border-gray-200 shrink-0">
                        <h3 class="font-extrabold text-gray-900 text-xs">Menunggu QC</h3>
                    </div>
                    <div class="overflow-y-auto flex-1 p-1">
                        <ul class="divide-y divide-gray-100">
                            @forelse($shippingReturn as $booking)
                                <li class="p-2 flex justify-between items-center">
                                    <div>
                                        <p class="font-bold text-gray-900 text-[11px]">{{ $booking->user->name }}</p>
                                    </div>
                                    <a href="{{ route('admin.return.index') }}" class="text-[9px] font-bold bg-white border border-gray-300 text-gray-700 rounded-sm px-2 py-0.5 hover:border-primary hover:text-primary transition-colors">Buka</a>
                                </li>
                            @empty
                                <li class="p-2 text-gray-400 italic text-center text-[10px]">Kosong</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

            </div>
        </div>

        <!-- Kostum Tersedia -->
        <div class="bg-white border border-gray-200 rounded-sm shadow-sm">
            <div class="bg-white px-4 py-3 border-b border-gray-200 flex justify-between items-center cursor-pointer" x-data="{ open: true }" @click="open = !open">
                <h3 class="font-extrabold text-gray-900 text-sm">Kostum Tersedia (Siap Hari Ini)</h3>
                <svg class="w-4 h-4 text-gray-400 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>
            <div class="p-4" x-show="open" style="display: none;" x-data="{ open: true }">
                <div class="flex flex-wrap gap-2 max-h-40 overflow-y-auto pr-2">
                    @forelse($availableCostumes as $costume)
                        <span class="inline-block bg-white border border-gray-200 rounded-sm shadow-sm px-2 py-1 font-medium text-[11px] text-gray-700">
                            {{ $costume->name }}
                        </span>
                    @empty
                        <span class="text-gray-400 italic text-xs">Tidak ada kostum yang siap hari ini.</span>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</x-layout.admin>
