<x-layout.public>
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 py-8 space-y-6">
        
        <h2 class="font-bold text-2xl text-gray-900 leading-tight mb-6">
            Selesaikan Pemesanan Anda
        </h2>

        <!-- Daftar Item Keranjang -->
        <div class="bg-white border border-gray-200 rounded-sm shadow-sm p-6 mb-8">
            <h3 class="text-xl font-bold text-gray-900 mb-4 border-b border-gray-200 pb-2">Keranjang Sewa ({{ $cart->items->count() }} Kostum)</h3>
            
            <div class="space-y-4">
                @foreach($cart->items as $item)
                    <div class="flex items-start gap-4 p-4 border border-gray-200 rounded-sm bg-gray-50 relative">
                        <!-- Hapus Button -->
                        <form action="{{ route('cart.destroy', $item->id) }}" method="POST" class="absolute top-4 right-4">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 p-1" title="Hapus dari keranjang">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </form>

                        @if($item->costume->image_path)
                            <img src="{{ asset('storage/' . $item->costume->image_path) }}" alt="{{ $item->costume->name }}" class="w-24 h-24 object-cover rounded-sm border border-gray-200">
                        @else
                            <div class="w-24 h-24 bg-gray-200 border border-gray-300 rounded-sm flex items-center justify-center text-gray-400 font-semibold text-xs">
                                No Image
                            </div>
                        @endif
                        <div class="flex-grow">
                            <h4 class="text-lg font-bold text-gray-900">{{ $item->costume->name }}</h4>
                            <p class="text-gray-500 font-medium mb-1 text-sm">{{ $item->costume->series }} | Ukuran: {{ $item->costume->size }}</p>
                            <p class="text-xs font-semibold text-gray-600 mb-2">Tanggal: {{ $item->start_date->format('d M Y') }} - {{ $item->end_date->format('d M Y') }} ({{ $item->days }} Hari)</p>
                            
                            <div class="flex justify-between items-center mt-2">
                                <p class="text-sm font-semibold text-gray-600">Deposit: Rp {{ number_format($item->costume->deposit_price, 0, ',', '.') }}</p>
                                <p class="text-primary font-bold text-base">Sewa: Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <form action="{{ route('checkout.store') }}" method="POST" x-data="checkoutForm()" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @csrf
            
            <!-- Kiri: Pemilihan Alamat -->
            <div class="space-y-6">
                <div class="bg-white border border-gray-200 rounded-sm shadow-sm p-6">
                    <div class="flex justify-between items-center mb-4 border-b border-gray-200 pb-2">
                        <h3 class="text-lg font-bold text-gray-900">Alamat Pengiriman</h3>
                        <a href="{{ route('address.index') }}" target="_blank" class="text-sm font-semibold text-primary hover:text-[#E5A5B0] transition-colors">Kelola Alamat</a>
                    </div>
                    
                    @if($addresses->isEmpty())
                        <div class="bg-red-50 border border-red-200 rounded-sm p-4 mb-4">
                            <p class="text-sm text-red-600 font-semibold">Anda belum memiliki alamat tersimpan!</p>
                            <a href="{{ route('address.index') }}" class="inline-block mt-2 text-xs bg-white text-red-600 border border-red-200 px-3 py-1.5 rounded-sm font-bold hover:bg-red-50 transition-colors">Tambah Alamat Sekarang</a>
                        </div>
                        <input type="hidden" name="address_id" value="" required>
                    @else
                        <div class="space-y-3">
                            @foreach($addresses as $addr)
                                <label class="flex items-start gap-3 p-3 rounded-sm border cursor-pointer transition-all duration-200"
                                       :class="selectedAddress == '{{ $addr->id }}' ? 'border-primary bg-primary bg-opacity-10 shadow-sm' : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50'">
                                    <input type="radio" name="address_id" value="{{ $addr->id }}" x-model="selectedAddress" class="mt-1 text-primary focus:ring-primary border-gray-300">
                                    <div class="flex-grow">
                                        <p class="text-sm font-medium text-gray-800">
                                            {{ $addr->address_line }}
                                        </p>
                                        @if($addr->is_primary)
                                            <span class="inline-block mt-1.5 text-[10px] bg-primary text-gray-900 px-2 py-0.5 rounded-sm font-bold uppercase tracking-wider">Utama</span>
                                        @endif
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @endif
                    @error('address_id')<p class="text-red-600 font-semibold mt-1 text-sm">{{ $message }}</p>@enderror
                </div>

                <!-- Opsi Pengiriman -->
                <div class="bg-white border border-gray-200 rounded-sm shadow-sm p-6" x-show="selectedAddress" x-cloak>
                    <h3 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-200 pb-2">Opsi Pengiriman</h3>
                    
                    <div x-show="isShippingLoading" class="text-gray-500 font-medium text-sm animate-pulse">
                        Menghitung ongkos kirim...
                    </div>
                    
                    <div x-show="shippingError" class="bg-yellow-50 border border-yellow-200 p-4 rounded-sm text-sm text-yellow-800 font-medium">
                        <p x-text="shippingError"></p>
                        <p class="mt-2 text-xs opacity-80">Anda tetap bisa melanjutkan pesanan, namun status akan menjadi "Menunggu Hitung Ongkir".</p>
                    </div>

                    <div x-show="shippingOptions.length > 0" class="space-y-3">
                        <template x-for="(option, index) in shippingOptions" :key="index">
                            <label class="flex items-start gap-3 p-3 rounded-sm border cursor-pointer transition-all duration-200"
                                   :class="shippingCourier == option.courier_code ? 'border-primary bg-primary bg-opacity-10 shadow-sm' : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50'">
                                <input type="radio" name="shipping_courier" :value="option.courier_code" x-model="shippingCourier" @change="shippingFee = option.price" class="mt-1 text-primary focus:ring-primary border-gray-300">
                                <div class="flex-grow flex justify-between items-center">
                                    <div>
                                        <p class="text-sm font-bold text-gray-800" x-text="option.courier_name"></p>
                                        <p class="text-[10px] font-medium text-gray-500 mt-0.5" x-text="'Estimasi: ' + (option.estimation ? option.estimation : '-')"></p>
                                    </div>
                                    <span class="font-extrabold text-primary" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(option.price)"></span>
                                </div>
                            </label>
                        </template>
                    </div>
                    <input type="hidden" name="shipping_fee" :value="shippingFee">
                </div>
            </div>

            <!-- Kanan: Bukti Pembayaran & Rincian Harga -->
            <div class="space-y-6">
                
                <div class="bg-white border border-gray-200 rounded-sm shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-200 pb-2">Bukti Pembayaran</h3>
                    <p class="text-sm text-gray-600 mb-4 font-medium leading-relaxed">Silakan transfer sesuai Total Biaya ke Rekening <strong>BCA 1234567890</strong> a.n. Cosplay Rental, lalu unggah bukti pembayarannya.</p>
                    
                    <input type="file" name="payment_proof" accept="image/png, image/jpeg, image/jpg" :required="shippingFee > 0" class="w-full border border-gray-300 rounded-sm p-2 focus:ring-0 text-sm font-medium bg-gray-50 text-gray-700 file:mr-4 file:py-1.5 file:px-4 file:rounded-sm file:border-0 file:text-xs file:font-bold file:bg-primary file:text-gray-900 hover:file:bg-[#E5A5B0]">
                    <p class="text-xs text-gray-400 mt-2 font-medium">Format: JPG/PNG, Maksimal: 2MB.</p>
                    <p class="text-xs text-yellow-600 mt-1 font-bold" x-show="shippingFee === 0 && !isShippingLoading">Abaikan unggah bukti bayar, admin akan mengonfirmasi via sistem.</p>
                    @error('payment_proof')<p class="text-red-600 font-semibold mt-1 text-sm">{{ $message }}</p>@enderror
                </div>

                <div class="bg-white border border-gray-200 rounded-sm shadow-sm p-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-200 pb-2">Rincian Total Biaya</h3>
                        
                        <div class="space-y-3 mb-6 text-sm">
                            <div class="flex justify-between text-gray-600 font-medium">
                                <span>Total Sewa ({{ $cart->items->count() }} item)</span>
                                <span class="font-semibold text-gray-900">Rp {{ number_format($totalSewa, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-gray-700 font-medium pt-1 border-b border-gray-200 pb-3">
                                <span>Total Uang Jaminan (Deposit) <br><span class="text-xs text-gray-400 font-normal">Dikembalikan setelah QC Return selesai</span></span>
                                <span class="font-semibold text-gray-900">Rp {{ number_format($totalDeposit, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600 font-medium pt-3" x-show="shippingFee > 0">
                                <span>Ongkos Kirim</span>
                                <span class="font-semibold text-gray-900" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(shippingFee)"></span>
                            </div>
                            <div class="flex justify-between items-center pt-4 mt-2">
                                <span class="text-base font-bold text-gray-900">Total Pembayaran</span>
                                <span class="text-xl font-extrabold text-primary" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format({{ $totalPrice }} + shippingFee)"></span>
                            </div>
                        </div>

                    </div>

                    <div class="mt-6">
                        <label class="flex items-start gap-3 p-3 rounded-sm border border-primary bg-primary bg-opacity-5 mb-6 cursor-pointer">
                            <input type="checkbox" name="terms" required class="mt-0.5 text-primary focus:ring-primary border-gray-300 rounded-sm">
                            <span class="text-xs font-semibold text-gray-700 leading-relaxed">
                                Saya setuju dengan Syarat & Ketentuan, termasuk denda apabila terjadi keterlambatan pengembalian atau kerusakan komponen yang akan dipotong dari Uang Jaminan.
                            </span>
                        </label>

                        <button type="submit" class="w-full bg-light-primary text-gray-900 font-bold text-base py-3.5 rounded-sm shadow-sm hover:bg-[#E5A5B0] transition-colors" :disabled="!selectedAddress" :class="{'opacity-50 cursor-not-allowed': !selectedAddress}">
                            Kirim & Konfirmasi Penyewaan Semua Item
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function checkoutForm() {
            return {
                selectedAddress: '{{ $addresses->where('is_primary', true)->first()?->id ?? '' }}',
                shippingOptions: [],
                shippingCourier: '',
                shippingFee: 0,
                isShippingLoading: false,
                shippingError: '',
                
                init() {
                    if (this.selectedAddress) {
                        this.fetchShipping();
                    }
                    this.$watch('selectedAddress', value => {
                        this.fetchShipping();
                    });
                },
                
                fetchShipping() {
                    if (!this.selectedAddress) return;
                    
                    this.isShippingLoading = true;
                    this.shippingError = '';
                    this.shippingOptions = [];
                    this.shippingCourier = '';
                    this.shippingFee = 0;

                    fetch(`{{ route('checkout.shipping-options') }}?address_id=${this.selectedAddress}`)
                        .then(response => response.json())
                        .then(data => {
                            this.isShippingLoading = false;
                            if (data.error) {
                                this.shippingError = data.error;
                            } else if (data.options) {
                                this.shippingOptions = data.options;
                                if (this.shippingOptions.length > 0) {
                                    this.shippingCourier = this.shippingOptions[0].courier_code;
                                    this.shippingFee = this.shippingOptions[0].price;
                                } else {
                                    this.shippingError = 'Tidak ada kurir yang tersedia untuk rute ini.';
                                }
                            }
                        })
                        .catch(error => {
                            this.isShippingLoading = false;
                            this.shippingError = 'Gagal menghubungi server ongkir.';
                        });
                }
            }
        }
    </script>
</x-layout.public>
