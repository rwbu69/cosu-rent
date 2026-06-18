<x-layout.admin>
    <x-slot name="title">Dasbor Utama</x-slot>

    <div class="space-y-8">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Stat Cards -->
            <div class="bg-white p-6 border border-gray-200 rounded-sm shadow-sm">
                <h3 class="text-gray-500 font-bold text-sm uppercase">Sedang Dirental</h3>
                <p class="text-4xl font-extrabold text-primary mt-2">{{ $activeBookings->count() }}</p>
            </div>
            <div class="bg-white p-6 border border-gray-200 rounded-sm shadow-sm">
                <h3 class="text-gray-500 font-bold text-sm uppercase">Dikirim ke Pelanggan</h3>
                <p class="text-4xl font-extrabold text-blue-600 mt-2">{{ $shippingOut->count() }}</p>
            </div>
            <div class="bg-white p-6 border border-gray-200 rounded-sm shadow-sm">
                <h3 class="text-gray-500 font-bold text-sm uppercase">Dikirim Kembali</h3>
                <p class="text-4xl font-extrabold text-purple-600 mt-2">{{ $shippingReturn->count() }}</p>
            </div>
            <div class="bg-white p-6 border border-gray-200 rounded-sm shadow-sm">
                <h3 class="text-gray-500 font-bold text-sm uppercase">Tersedia (Siap)</h3>
                <p class="text-4xl font-extrabold text-green-600 mt-2">{{ $availableCostumes->count() }}</p>
            </div>
        </div>

        <!-- Detailed Lists -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- Sedang Dirental -->
            <div class="bg-white border border-gray-200 rounded-sm shadow-sm">
                <div class="bg-white px-6 py-4 border-b border-gray-200">
                    <h3 class="font-extrabold text-gray-900 text-lg">Kostum Sedang Dirental</h3>
                </div>
                <div class="p-6">
                    @if($activeBookings->isEmpty())
                        <p class="text-gray-500 italic">Tidak ada kostum yang sedang dirental.</p>
                    @else
                        <div class="space-y-4" x-data="{ expandedId: null }">
                            @foreach($activeBookings as $booking)
                                <div class="border border-gray-200 rounded-sm p-4">
                                    <div class="flex justify-between items-center cursor-pointer" @click="expandedId = expandedId === {{ $booking->id }} ? null : {{ $booking->id }}">
                                        <div>
                                            <p class="font-bold text-gray-900">{{ $booking->user->name }}</p>
                                            <p class="text-sm text-gray-600">Menyewa: {{ $booking->costume->name }}</p>
                                        </div>
                                        <svg class="w-5 h-5 transition-transform" :class="{'rotate-180': expandedId === {{ $booking->id }}}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                    <div x-show="expandedId === {{ $booking->id }}" class="mt-4 pt-4 border-t border-gray-200" style="display: none;">
                                        <p class="text-sm font-bold text-gray-900 mb-2">Komponen dibawa:</p>
                                        <ul class="list-disc list-inside text-sm text-gray-700">
                                            @foreach($booking->costume->components as $comp)
                                                <li>{{ $comp->name }} ({{ $comp->barcode }})</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Pengiriman & Pengembalian -->
            <div class="space-y-8">
                
                <!-- Dikirim ke Customer -->
                <div class="bg-white border border-gray-200 rounded-sm shadow-sm">
                    <div class="bg-white px-6 py-3 border-b border-gray-200">
                        <h3 class="font-extrabold text-gray-900">Sedang Dikirim ke Pelanggan</h3>
                    </div>
                    <ul class="divide-y divide-gray-200">
                        @forelse($shippingOut as $booking)
                            <li class="p-4 flex justify-between items-center hover:bg-gray-50 transition-colors">
                                <div>
                                    <p class="font-bold text-gray-900">{{ $booking->user->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $booking->costume->name }}</p>
                                </div>
                            </li>
                        @empty
                            <li class="p-4 text-gray-500 italic">Kosong.</li>
                        @endforelse
                    </ul>
                </div>

                <!-- Dikirim Kembali -->
                <div class="bg-white border border-gray-200 rounded-sm shadow-sm">
                    <div class="bg-white px-6 py-3 border-b border-gray-200">
                        <h3 class="font-extrabold text-gray-900">Sedang Dikirim Kembali (Menunggu QC)</h3>
                    </div>
                    <ul class="divide-y divide-gray-200">
                        @forelse($shippingReturn as $booking)
                            <li class="p-4 flex justify-between items-center hover:bg-gray-50 transition-colors">
                                <div>
                                    <p class="font-bold text-gray-900">{{ $booking->user->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $booking->costume->name }}</p>
                                </div>
                                <a href="{{ route('admin.return.index') }}" class="text-xs font-bold bg-white border border-gray-300 text-gray-700 rounded-sm px-3 py-1.5 hover:border-primary hover:text-primary transition-colors">Buka QC</a>
                            </li>
                        @empty
                            <li class="p-4 text-gray-500 italic">Kosong.</li>
                        @endforelse
                    </ul>
                </div>

            </div>
        </div>

        <!-- Kostum Tersedia -->
        <div class="bg-white border border-gray-200 rounded-sm shadow-sm">
            <div class="bg-white px-6 py-4 border-b border-gray-200">
                <h3 class="font-extrabold text-gray-900 text-lg">Kostum Tersedia (Siap Hari Ini)</h3>
            </div>
            <div class="p-6">
                <div class="flex flex-wrap gap-3">
                    @forelse($availableCostumes as $costume)
                        <span class="inline-block bg-white border border-gray-200 rounded-sm shadow-sm px-3 py-1 font-medium text-sm text-gray-700">
                            {{ $costume->name }}
                        </span>
                    @empty
                        <span class="text-gray-500 italic">Tidak ada kostum yang siap hari ini.</span>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</x-layout.admin>
