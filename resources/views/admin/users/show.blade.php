<x-layout.admin>
    <div class="max-w-5xl mx-auto space-y-6">
        
        <h2 class="font-bold text-2xl text-gray-900 leading-tight mb-6">
            Detail Profil Pengguna
        </h2>

        <!-- Profil Singkat -->
        <div class="bg-white p-8 border border-gray-200 rounded-sm shadow-sm grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <h3 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h3>
                <p class="text-gray-500 font-medium mb-4">{{ '@' . $user->username }}</p>
                
                <div class="space-y-2 text-sm text-gray-700">
                    <p><span class="font-semibold text-gray-900">Email:</span> {{ $user->email ?? '-' }}</p>
                    <p><span class="font-semibold text-gray-900">Member Sejak:</span> {{ $user->created_at->format('d M Y') }}</p>
                    <p class="flex items-center gap-2">
                        <span class="font-semibold text-gray-900">RFID UID:</span> 
                        @if($user->rfid_uid)
                            <span class="bg-green-100 text-green-800 px-2 py-0.5 rounded-sm font-semibold text-xs tracking-wider">{{ $user->rfid_uid }}</span>
                        @else
                            <span class="text-gray-400 font-medium text-xs bg-gray-100 px-2 py-0.5 rounded-sm">Belum Terdaftar</span>
                        @endif
                    </p>
                </div>
            </div>
            
            <div class="border-t md:border-t-0 md:border-l border-gray-200 pt-4 md:pt-0 md:pl-8">
                <h4 class="font-bold text-lg text-gray-900 mb-3">Alamat Utama</h4>
                @if($user->primaryAddress)
                    <div class="bg-gray-50 border border-gray-200 rounded-sm p-4">
                        <p class="text-sm font-medium text-gray-700 leading-relaxed">{{ $user->primaryAddress->address_line }}</p>
                    </div>
                @else
                    <p class="text-gray-500 font-medium text-sm">Pengguna ini belum mengatur alamat pengiriman utama.</p>
                @endif
            </div>
        </div>

        <!-- Riwayat Transaksi -->
        <div class="bg-white border border-gray-200 rounded-sm shadow-sm">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h3 class="font-bold text-gray-900 text-lg">Riwayat Transaksi ({{ $user->bookings->count() }})</h3>
            </div>
            
            <div class="p-6">
                @if($user->bookings->isEmpty())
                    <p class="text-gray-500 font-medium text-center py-8 bg-gray-50 rounded-sm border border-gray-100">Belum ada riwayat transaksi penyewaan.</p>
                @else
                    <div class="space-y-4">
                        @foreach($user->bookings as $booking)
                            <div class="border border-gray-200 rounded-sm p-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 hover:border-gray-300 transition-colors">
                                <div>
                                    <p class="font-bold text-gray-900">{{ $booking->costume->name }}</p>
                                    <p class="text-xs text-gray-500 mt-1 uppercase tracking-wider font-semibold">{{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}</p>
                                    <p class="text-sm font-bold mt-2 text-primary">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    @php
                                        $statusColor = match($booking->status) {
                                            'Pending' => 'bg-yellow-100 text-yellow-800',
                                            'Dikirim ke Customer' => 'bg-blue-100 text-blue-800',
                                            'Sedang Dirental' => 'bg-primary text-gray-900',
                                            'Dikirim Kembali' => 'bg-purple-100 text-purple-800',
                                            'Returned' => 'bg-green-100 text-green-800',
                                            default => 'bg-gray-100 text-gray-800',
                                        };
                                    @endphp
                                    <span class="text-[10px] font-bold px-2.5 py-1 rounded-sm {{ $statusColor }} uppercase tracking-wider block mb-2">
                                        {{ $booking->status === 'Returned' ? 'Selesai' : $booking->status }}
                                    </span>
                                    <p class="text-xs text-gray-400 font-semibold">ID: #{{ $booking->id }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="text-right">
            <a href="{{ route('admin.users.index') }}" class="inline-block bg-white text-gray-700 border border-gray-300 rounded-sm px-6 py-2 font-semibold hover:bg-gray-50 hover:text-gray-900 transition-colors shadow-sm">
                &larr; Kembali ke Daftar
            </a>
        </div>

    </div>
</x-layout.admin>
