@props(['booking'])

<div class="bg-white border border-gray-200 rounded-md shadow-sm p-6 lg:p-8 grid grid-cols-1 lg:grid-cols-12 gap-8 transition-transform hover:-translate-y-1 hover:shadow-sm items-stretch">
    <!-- Info Kostum -->
    <div class="lg:col-span-8 flex flex-col sm:flex-row gap-6">
        <!-- Foto Kostum -->
        <div class="w-full sm:w-1/3 xl:w-1/4 shrink-0">
            @if($booking->costume->image_path)
                <img src="{{ Storage::url($booking->costume->image_path) }}" alt="{{ $booking->costume->name }}" class="w-full aspect-[3/4] object-cover rounded-md border border-gray-200 shadow-sm">
            @else
                <div class="w-full aspect-[3/4] bg-gray-50 rounded-md border border-gray-200 flex flex-col items-center justify-center text-gray-400">
                    <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span class="text-xs font-bold uppercase">No Image</span>
                </div>
            @endif
        </div>
        
        <!-- Detail Pesanan -->
        <div class="flex-grow">
            <div class="flex flex-wrap items-center gap-4 mb-4">
                <h3 class="text-2xl font-medium text-gray-900 uppercase">{{ $booking->costume->name }}</h3>
                <!-- Status Badge -->
                @php
                    $statusColor = match($booking->status) {
                        'Menunggu Konfirmasi' => 'bg-yellow-300 border-gray-900 text-gray-900',
                        'Diproses' => 'bg-blue-300 border-gray-900 text-gray-900',
                        'Sedang Dikirim' => 'bg-orange-300 border-gray-900 text-gray-900',
                        'Sedang Dirental' => 'bg-primary border-gray-900 text-gray-900',
                        'Dikirim Kembali' => 'bg-purple-300 border-gray-900 text-gray-900',
                        'Returned' => 'bg-secondary border-gray-900 text-gray-900',
                        default => 'bg-gray-200 border-gray-900 text-gray-900',
                    };
                @endphp
                <span class="text-xs font-medium px-3 py-1.5 border-2 {{ $statusColor }} uppercase tracking-widest shadow-sm">
                    {{ $booking->status === 'Returned' ? 'Selesai' : $booking->status }}
                </span>
            </div>
            <p class="text-gray-600 font-bold mb-6 text-sm tracking-wide uppercase">{{ $booking->costume->series }}</p>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-6">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-widest mb-2">Tanggal Sewa</p>
                    <p class="font-bold text-gray-900 text-lg">{{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-widest mb-2">Total Biaya</p>
                    <p class="font-medium text-gray-900 text-xl bg-primary px-3 py-1 inline-block border border-gray-200 rounded-md shadow-sm">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="mb-6">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-widest mb-2">Alamat Pengiriman</p>
                <div class="font-medium bg-gray-50 p-4 border border-gray-200 rounded-md text-sm text-gray-900 min-h-[5.5rem] flex items-center">
                    <p class="line-clamp-3">{{ $booking->shipping_address ?? 'Diambil via RFID Kiosk' }}</p>
                </div>
            </div>

            @if($booking->shipping_receipt || $booking->shipping_courier)
                <div class="mb-6 p-4 border border-blue-100 bg-blue-50 rounded-md">
                    <p class="text-xs font-bold text-blue-900 uppercase tracking-widest mb-3">Informasi Pengiriman</p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        @if($booking->shipping_image_path)
                            <div class="w-full sm:w-24 shrink-0">
                                <a href="{{ Storage::url($booking->shipping_image_path) }}" target="_blank">
                                    <img src="{{ Storage::url($booking->shipping_image_path) }}" alt="Foto Paket" class="w-full h-24 object-cover rounded-md border border-blue-200 shadow-sm hover:opacity-80 transition-opacity">
                                </a>
                            </div>
                        @endif
                        <div class="flex-grow">
                            <p class="text-sm font-medium text-gray-700 mb-1">Kurir: <span class="font-bold text-gray-900 uppercase">{{ str_replace('_', ' - ', $booking->shipping_courier ?? $booking->shipping_method) }}</span></p>
                            <p class="text-sm font-medium text-gray-700">Nomor Resi: <span class="font-bold text-gray-900">{{ $booking->shipping_receipt ?? '-' }}</span></p>
                            @if($booking->shipping_image_path)
                                <p class="text-xs text-blue-700 mt-2 italic">Klik gambar paket untuk memperbesar.</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if($booking->return_shipping_receipt || $booking->return_shipping_courier)
                <div class="mb-6 p-4 border border-purple-100 bg-purple-50 rounded-md">
                    <p class="text-xs font-bold text-purple-900 uppercase tracking-widest mb-3">Informasi Pengembalian</p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        @if($booking->return_shipping_image_path)
                            <div class="w-full sm:w-24 shrink-0">
                                <a href="{{ Storage::url($booking->return_shipping_image_path) }}" target="_blank">
                                    <img src="{{ Storage::url($booking->return_shipping_image_path) }}" alt="Foto Paket Retur" class="w-full h-24 object-cover rounded-md border border-purple-200 shadow-sm hover:opacity-80 transition-opacity">
                                </a>
                            </div>
                        @endif
                        <div class="flex-grow">
                            <p class="text-sm font-medium text-gray-700 mb-1">Kurir: <span class="font-bold text-gray-900 uppercase">{{ strtoupper(str_replace('_', ' - ', $booking->return_shipping_courier ?? '-')) }}</span></p>
                            <p class="text-sm font-medium text-gray-700">Nomor Resi: <span class="font-bold text-gray-900">{{ $booking->return_shipping_receipt ?? '-' }}</span></p>
                            @if($booking->return_shipping_image_path)
                                <p class="text-xs text-purple-700 mt-2 italic">Klik gambar paket untuk memperbesar.</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if($booking->status === 'Sedang Dikirim')
                <form action="{{ route('orders.received', $booking->id) }}" method="POST" x-data @submit.prevent="$dispatch('open-confirm', { title: 'Konfirmasi Pesanan Diterima', message: 'Apakah Anda yakin pesanan sudah sampai?', form: $el })">
                    @csrf
                    <button type="submit" class="bg-secondary text-gray-900 font-medium px-6 py-3 border border-gray-200 rounded-md hover:bg-white transition-colors shadow-sm text-sm uppercase tracking-wider w-full sm:w-auto">
                        Pesanan Diterima (Sampai)
                    </button>
                </form>
            @endif

            @if($booking->status === 'Sedang Dirental')
                <form action="{{ route('orders.returnShipping', $booking->id) }}" method="POST" enctype="multipart/form-data" class="mt-6 border-t-2 border-gray-900 pt-6" x-data @submit.prevent="$dispatch('open-confirm', { title: 'Konfirmasi Pengembalian', message: 'Anda yakin ingin menyimpan data pengembalian?', form: $el })">
                    @csrf
                    <div class="mb-4">
                        <label for="return_shipping_courier" class="block text-xs font-medium text-gray-900 uppercase tracking-widest mb-2">Ekspedisi (Kurir)</label>
                        <input type="text" name="return_shipping_courier" id="return_shipping_courier" class="w-full bg-white border border-gray-200 rounded-md px-4 py-3 text-sm focus:ring-0 focus:outline-none focus:border-primary focus:shadow-sm transition-shadow" placeholder="Contoh: JNE, J&T, Sicepat" required>
                    </div>
                    <div class="mb-4">
                        <label for="return_shipping_receipt" class="block text-xs font-medium text-gray-900 uppercase tracking-widest mb-2">Nomor Resi Pengembalian</label>
                        <input type="text" name="return_shipping_receipt" id="return_shipping_receipt" class="w-full bg-white border border-gray-200 rounded-md px-4 py-3 text-sm focus:ring-0 focus:outline-none focus:border-primary focus:shadow-sm transition-shadow" placeholder="Masukkan nomor resi ekspedisi" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs font-medium text-gray-900 uppercase tracking-widest mb-2">Foto Paket</label>
                        <input type="file" name="return_shipping_image" required accept="image/png, image/jpeg, image/jpg" class="w-full bg-white border border-gray-200 rounded-md px-4 py-2 text-sm file:mr-3 file:py-1 file:px-3 file:rounded-sm file:border-0 file:text-xs file:font-bold file:bg-gray-200 file:text-gray-800 hover:file:bg-gray-300">
                    </div>
                    <button type="submit" class="bg-gray-900 text-white font-medium px-6 py-3 border border-gray-200 rounded-md hover:bg-light-primary hover:text-gray-900 transition-colors shadow-sm text-sm uppercase tracking-wider w-full sm:w-auto">
                        Kirim Kembali Pesanan
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Info Komponen -->
    <div class="lg:col-span-4 border-t-2 lg:border-t-0 lg:border-l-2 border-gray-900 pt-6 lg:pt-0 lg:pl-8 flex flex-col h-full -mx-6 lg:mx-0 px-6 lg:px-0">
        <p class="font-medium text-gray-900 text-sm mb-4 uppercase tracking-widest">Komponen Kostum</p>
        <ul class="space-y-3 text-gray-900 font-medium h-full overflow-hidden">
            @foreach($booking->costume->components as $comp)
                <li x-data="{ open: false }" class="bg-white border border-gray-200 rounded-md shadow-sm">
                    <button type="button" @click="open = !open" class="flex items-center gap-3 w-full p-3 hover:bg-gray-50 hover:text-primary transition-colors focus:outline-none text-left">
                        <svg class="w-5 h-5 shrink-0 transition-transform duration-200 text-primary" :class="{'rotate-90': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                        <span class="flex-grow text-sm font-bold">{{ $comp->name }}</span>
                    </button>
                    <div x-show="open" x-transition class="p-3 pt-0 border-t border-gray-100 bg-gray-50" style="display: none;">
                        @if($comp->image_path)
                            <img src="{{ Storage::url($comp->image_path) }}" alt="{{ $comp->name }}" class="w-full h-32 object-contain rounded-md border border-gray-200 bg-white mt-3">
                        @else
                            <span class="text-xs text-gray-500 italic block text-center py-4 mt-2">Gambar tidak tersedia</span>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
