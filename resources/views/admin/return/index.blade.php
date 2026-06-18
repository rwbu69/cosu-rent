<x-layout.admin>
    <x-slot name="title">Quality Control Pengembalian (QC Return)</x-slot>

    <div class="space-y-6">
        
        <!-- Pilih Booking -->
        <div class="bg-white border border-gray-200 rounded-sm shadow-sm p-6">
            <h3 class="text-lg font-bold mb-4">Pilih Transaksi untuk Diperiksa</h3>
            <form action="{{ route('admin.return.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <select name="booking_id" required class="flex-grow border border-gray-300 rounded-sm p-2 focus:ring-0 focus:border-primary">
                    <option value="">-- Pilih Transaksi --</option>
                    @foreach($bookings as $booking)
                        <option value="{{ $booking->id }}" {{ request('booking_id') == $booking->id ? 'selected' : '' }}>
                            #{{ $booking->id }} - {{ $booking->user->name }} - {{ $booking->costume->name }} ({{ $booking->status }})
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="bg-slate-900 text-white font-semibold rounded-sm px-6 py-2 hover:bg-slate-800 transition-colors">
                    Pilih
                </button>
            </form>
        </div>

        @if($selectedBooking)
        <!-- QC Checklist -->
        <div class="bg-white border border-gray-200 rounded-sm shadow-sm p-6" x-data="qcReturnTracker()">
            <div class="border-b border-gray-200 pb-4 mb-6 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Mengecek: {{ $selectedBooking->costume->name }}</h3>
                    <p class="text-gray-600 font-medium">Penyewa: {{ $selectedBooking->user->name }}</p>
                </div>
                <div class="text-left md:text-right">
                    <p class="text-sm font-medium text-gray-500">Arahkan kursor di luar kolom input, lalu Scan QR Code.</p>
                    <p class="text-sm font-bold text-primary mt-1" x-show="lastScanned">Terakhir Scan: <span x-text="lastScanned"></span></p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 mb-8">
                <template x-for="comp in components" :key="comp.id">
                    <div class="p-4 border rounded-sm flex justify-between items-center transition-colors"
                         :class="comp.status === 'scanned' ? 'border-primary bg-primary bg-opacity-10' : (comp.status === 'missing' ? 'border-red-300 bg-red-50' : 'border-gray-200 bg-white')">
                        
                        <div>
                            <p class="font-bold text-gray-900" x-text="comp.name"></p>
                            <p class="text-xs text-gray-500 font-medium" x-text="comp.barcode"></p>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <span x-show="comp.status === 'scanned'" class="text-primary font-bold uppercase text-sm">✁ETer-Scan</span>
                            <span x-show="comp.status === 'missing'" class="text-red-600 font-bold uppercase text-sm">⚠ Hilang/Rusak</span>
                            <span x-show="comp.status === 'pending'" class="text-gray-400 font-semibold uppercase text-sm">Menunggu Scan...</span>
                            
                            <button type="button" x-show="comp.status !== 'missing'" @click="markMissing(comp.id)" class="text-xs bg-white text-red-600 font-semibold rounded-sm px-3 py-1.5 border border-red-200 hover:bg-red-50 transition-colors">Tandai Hilang</button>
                            <button type="button" x-show="comp.status === 'missing'" @click="resetStatus(comp.id)" class="text-xs bg-white text-gray-700 font-semibold rounded-sm px-3 py-1.5 border border-gray-300 hover:bg-gray-50 transition-colors">Batal Hilang</button>
                        </div>
                    </div>
                </template>
            </div>

            <form action="{{ route('admin.return.complete', $selectedBooking->id) }}" method="POST" class="mt-8 border-t border-gray-200 pt-6">
                @csrf
                <!-- Hidden inputs for missing components -->
                <template x-for="comp in components.filter(c => c.status === 'missing')">
                    <input type="hidden" name="missing_components[]" :value="comp.id">
                </template>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-gray-50 border border-gray-200 rounded-sm p-6">
                        <h4 class="font-bold text-gray-900 mb-4 border-b border-gray-200 pb-2">Informasi Keterlambatan & Denda</h4>
                        
                        <div class="space-y-2 mb-4 text-sm font-medium text-gray-700">
                            <p class="flex justify-between"><span>Batas Pengembalian:</span> <span>{{ \Carbon\Carbon::parse($selectedBooking->end_date)->format('d M Y') }}</span></p>
                            <p class="flex justify-between"><span>Terlambat:</span> <span class="{{ $selectedBooking->late_days > 0 ? 'text-red-600 font-bold' : 'text-green-600' }}">{{ $selectedBooking->late_days }} Hari</span></p>
                            <p class="flex justify-between border-t border-gray-200 pt-2"><span>Saran Denda Keterlambatan:</span> <span class="text-gray-900 font-bold">Rp {{ number_format($selectedBooking->suggested_late_fee, 0, ',', '.') }}</span></p>
                            <p class="flex justify-between text-gray-900 mt-2"><span>Uang Jaminan (Deposit) Pelanggan:</span> <span class="font-bold">Rp {{ number_format($selectedBooking->costume->deposit_price, 0, ',', '.') }}</span></p>
                        </div>

                        <label class="block font-semibold mb-2 mt-4 text-gray-900 text-sm">Finalisasi Denda (Rp)</label>
                        <p class="text-xs text-gray-500 mb-2">Masukkan total denda dari keterlambatan + barang hilang/rusak.</p>
                        <input type="number" name="penalty_fee" x-model="penaltyFee" min="0" required class="w-full border-gray-300 rounded-sm p-2 focus:ring-0 focus:border-primary font-bold text-red-600">
                    </div>

                    <div class="bg-primary bg-opacity-10 border border-primary rounded-sm p-6 flex flex-col justify-center text-center">
                        <h4 class="font-bold text-gray-900 mb-2">Kalkulasi Pengembalian Jaminan</h4>
                        <p class="text-xs font-medium text-gray-600 mb-4">Deposit - Total Denda = Refund</p>
                        
                        <div>
                            <span class="block text-sm font-semibold text-gray-700 mb-1" x-text="refundAmount >= 0 ? 'Jumlah yang harus di-refund ke Pelanggan:' : 'Pelanggan masih berhutang:'"></span>
                            <span class="text-3xl font-extrabold" :class="refundAmount >= 0 ? 'text-gray-900' : 'text-red-600'" x-text="'Rp ' + formatNumber(Math.abs(refundAmount))"></span>
                        </div>
                        
                        <div class="mt-4 pt-4 border-t border-primary border-opacity-20 text-left">
                            <p class="text-sm font-semibold text-gray-900 mb-1">Informasi Rekening Pengguna:</p>
                            @if($selectedBooking->user->bank_name && $selectedBooking->user->bank_account_number)
                                <p class="text-sm font-bold text-gray-800">{{ $selectedBooking->user->bank_name }} - {{ $selectedBooking->user->bank_account_number }}</p>
                                <p class="text-xs text-gray-600">a.n. {{ $selectedBooking->user->name }}</p>
                            @else
                                <p class="text-sm text-red-600 font-semibold italic">Belum mengisi data rekening</p>
                            @endif
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full font-bold text-base py-3 rounded-sm transition-colors shadow-sm"
                        :class="isReadyToSubmit ? 'bg-light-primary text-gray-900 hover:bg-[#E5A5B0] cursor-pointer' : 'bg-gray-100 text-gray-400 border border-gray-200 cursor-not-allowed'"
                        :disabled="!isReadyToSubmit"
                        @click="if(!isReadyToSubmit) { $dispatch('toast', { message: 'Selesaikan QC terlebih dahulu! Pastikan semua discan atau ditandai hilang.', type: 'error' }); event.preventDefault(); }">
                    Selesaikan Pengembalian (Selesai)
                </button>
            </form>
        </div>
        @endif
    </div>

    @if($selectedBooking)
    <script>
        function qcReturnTracker() {
            return {
                @php
                    $mappedComponents = $selectedBooking->costume->components->map(function($c) {
                        return [
                            'id' => $c->id,
                            'name' => $c->name,
                            'barcode' => $c->barcode_string ?? $c->barcode,
                            'status' => 'pending'
                        ];
                    })->values()->all();
                @endphp
                components: @json($mappedComponents),
                buffer: '',
                lastScanned: '',
                penaltyFee: {{ $selectedBooking->suggested_late_fee ?? 0 }},
                depositPrice: {{ $selectedBooking->costume->deposit_price ?? 0 }},

                get refundAmount() {
                    return this.depositPrice - this.penaltyFee;
                },
                formatNumber(num) {
                    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                },
                
                init() {
                    window.addEventListener('keydown', (e) => {
                        // Ignore if typing in an input
                        if (['INPUT', 'TEXTAREA', 'SELECT'].includes(e.target.tagName)) return;
                        
                        if (e.key === 'Enter') {
                            this.processScan(this.buffer);
                            this.lastScanned = this.buffer;
                            this.buffer = '';
                        } else if (e.key.length === 1) {
                            this.buffer += e.key;
                        }
                    });
                },
                
                processScan(scannedBarcode) {
                    let found = false;
                    this.components = this.components.map(comp => {
                        if (comp.barcode === scannedBarcode) {
                            comp.status = 'scanned';
                            found = true;
                        }
                        return comp;
                    });
                    
                    if (found) {
                        window.dispatchEvent(new CustomEvent('toast', { detail: { message: "Komponen ditemukan: " + scannedBarcode, type: 'success' }}));
                    } else {
                        window.dispatchEvent(new CustomEvent('toast', { detail: { message: "QR Code tidak cocok: " + scannedBarcode, type: 'error' }}));
                    }
                },
                
                markMissing(id) {
                    this.components = this.components.map(c => c.id === id ? { ...c, status: 'missing' } : c);
                },
                
                resetStatus(id) {
                    this.components = this.components.map(c => c.id === id ? { ...c, status: 'pending' } : c);
                },
                
                get isReadyToSubmit() {
                    // Ready if there are NO pending components
                    return this.components.filter(c => c.status === 'pending').length === 0;
                }
            }
        }
    </script>
    @endif
</x-layout.admin>
