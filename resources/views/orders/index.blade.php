<x-layout.public>
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 py-8 space-y-6">
        
        <h2 class="font-bold text-2xl text-gray-900 leading-tight mb-6">
            Riwayat Pesanan
        </h2>

        @if($bookings->isEmpty())
            <div class="bg-white p-12 border border-gray-200 rounded-sm shadow-sm text-center">
                <p class="text-lg font-medium text-gray-500 mb-6">Anda belum memiliki riwayat pesanan.</p>
                <a href="{{ route('catalog.index') }}" class="inline-block bg-primary text-gray-900 font-bold px-8 py-3 rounded-sm shadow-sm hover:bg-[#E5A5B0] transition-colors">
                    Mulai Menyewa
                </a>
            </div>
        @else
            <div class="space-y-8">
                @foreach($bookings as $booking)
                    <div class="bg-white border border-gray-200 rounded-md shadow-sm p-6 md:p-8 flex flex-col md:flex-row gap-8 transition-transform hover:-translate-y-1 hover:shadow-sm">
                        <!-- Info Kostum -->
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

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
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
                                    <p class="font-medium bg-gray-50 p-4 border border-gray-200 rounded-md text-sm text-gray-900">
                                        {{ $booking->shipping_address ?? 'Diambil via RFID Kiosk' }}
                                    </p>
                                </div>

                                @if($booking->status === 'Sedang Dikirim')
                                    <form action="{{ route('orders.received', $booking->id) }}" method="POST" x-data @submit.prevent="$dispatch('open-confirm', { title: 'Konfirmasi Pesanan Diterima', message: 'Apakah Anda yakin pesanan sudah sampai?', form: $el })">
                                        @csrf
                                        <button type="submit" class="bg-secondary text-gray-900 font-medium px-6 py-3 border border-gray-200 rounded-md hover:bg-white transition-colors shadow-sm text-sm uppercase tracking-wider">
                                            Pesanan Diterima (Sampai)
                                        </button>
                                    </form>
                                @endif

                                @if($booking->status === 'Sedang Dirental')
                                    <form action="{{ route('orders.returnShipping', $booking->id) }}" method="POST" class="mt-6 border-t-2 border-gray-900 pt-6" x-data @submit.prevent="$dispatch('open-confirm', { title: 'Konfirmasi Pengembalian', message: 'Anda yakin ingin menyimpan data pengembalian?', form: $el })">
                                        @csrf
                                        <div class="mb-4">
                                            <label for="return_shipping_receipt" class="block text-xs font-medium text-gray-900 uppercase tracking-widest mb-2">Nomor Resi Pengembalian</label>
                                            <input type="text" name="return_shipping_receipt" id="return_shipping_receipt" class="w-full bg-white border border-gray-200 rounded-md px-4 py-3 text-sm focus:ring-0 focus:outline-none focus:border-primary focus:shadow-sm transition-shadow" placeholder="Masukkan nomor resi ekspedisi" required>
                                        </div>
                                        <button type="submit" class="bg-gray-900 text-white font-medium px-6 py-3 border border-gray-200 rounded-md hover:bg-primary hover:text-gray-900 transition-colors shadow-sm text-sm uppercase tracking-wider w-full sm:w-auto">
                                            Kirim Kembali Pesanan
                                        </button>
                                    </form>
                                @endif
                            </div>

                        <!-- Info Komponen -->
                        <div class="md:w-1/3 border-t-2 md:border-t-0 md:border-l-2 border-gray-900 pt-6 md:pt-0 md:pl-8 bg-gray-50 md:bg-transparent -mx-6 -mb-6 md:m-0 p-6 md:p-0">
                            <p class="font-medium text-gray-900 text-sm mb-4 uppercase tracking-widest">Komponen Kostum</p>
                            <ul class="space-y-2 text-gray-900 font-medium">
                                @foreach($booking->costume->components as $comp)
                                    <li class="flex items-start gap-2">
                                        <svg class="w-5 h-5 text-primary shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                                        <span>{{ $comp->name }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</x-layout.public>
