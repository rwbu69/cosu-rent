<x-layout.public>
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 py-8 space-y-8">
        
        <h2 class="font-bold text-2xl text-gray-900 leading-tight mb-6">
            Manajemen Alamat Pengiriman
        </h2>

        <!-- Tambah Alamat Form -->
        <div class="bg-white p-8 border border-gray-200 rounded-sm shadow-sm">
            <h3 class="text-xl font-bold mb-4 text-gray-900">Tambah Alamat Baru</h3>
            <form action="{{ route('address.store') }}" method="POST" x-data="addressForm()">
                @csrf
                <div class="mb-4">
                    <label for="address_line" class="block font-medium mb-2 text-gray-700">Alamat Lengkap</label>
                    <textarea name="address_line" id="address_line" rows="3" required x-model="addressLine" @input.debounce.1000ms="searchVillage" class="w-full border-gray-300 rounded-sm p-3 focus:ring-0 focus:border-primary shadow-sm" placeholder="Contoh: Jl. Sudirman No. 1, Jakarta Pusat">{{ old('address_line') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Sistem akan otomatis mencari Kode Desa setelah Anda selesai mengetik.</p>
                    @error('address_line')
                        <p class="text-red-600 font-medium mt-2 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="village_code" class="block font-medium mb-2 text-gray-700">Kode Desa (10 Digit)</label>
                    <div class="flex gap-2 mb-2">
                        <input type="text" name="village_code" id="village_code" required x-model="villageCode" class="w-full border-gray-300 rounded-sm p-3 focus:ring-0 focus:border-primary shadow-sm bg-gray-50" readonly placeholder="Otomatis terisi dari pencarian">
                    </div>
                    
                    <!-- Search Results -->
                    <div x-show="isSearching" class="text-sm text-primary font-medium mb-2 animate-pulse">Sedang mencari data desa...</div>
                    <div x-show="searchError" class="text-sm text-red-600 font-medium mb-2" x-text="searchError"></div>
                    
                    <div x-show="searchResults.length > 0" class="border border-gray-200 rounded-sm max-h-60 overflow-y-auto mb-2 bg-white shadow-sm">
                        <template x-for="result in searchResults" :key="result.village_code">
                            <div class="p-3 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition-colors" @click="selectVillage(result)">
                                <p class="text-sm font-bold text-gray-800" x-text="result.village"></p>
                                <p class="text-xs text-gray-500" x-text="result.district + ', ' + result.city + ', ' + result.province + ' (' + result.postal_code + ')'"></p>
                            </div>
                        </template>
                    </div>

                    <div x-show="searchResults.length === 0 && !isSearching && addressLine.length > 5 && !villageCode" class="text-xs text-gray-500 italic mb-2">
                        Tidak ditemukan desa yang cocok. Coba perjelas nama desa/kecamatan pada alamat.
                    </div>
                    
                    @error('village_code')
                        <p class="text-red-600 font-medium mt-2 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-6">
                    <label for="postal_code" class="block font-medium mb-2 text-gray-700">Kode Pos</label>
                    <input type="text" name="postal_code" id="postal_code" required class="w-full border-gray-300 rounded-sm p-3 focus:ring-0 focus:border-primary shadow-sm" placeholder="Contoh: 12210" value="{{ old('postal_code') }}">
                    @error('postal_code')
                        <p class="text-red-600 font-medium mt-2 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="bg-light-primary text-gray-900 font-bold px-6 py-2.5 rounded-sm shadow-sm hover:bg-[#E5A5B0] transition-colors">
                    Simpan Alamat
                </button>
            </form>
        </div>

        <!-- Daftar Alamat -->
        <div class="bg-white p-8 border border-gray-200 rounded-sm shadow-sm">
            <h3 class="text-xl font-bold mb-6 text-gray-900">Daftar Alamat Tersimpan</h3>
            
            @if($addresses->isEmpty())
                <p class="text-gray-500 font-medium">Belum ada alamat yang disimpan.</p>
            @else
                <div class="space-y-4">
                    @foreach($addresses as $address)
                        <div class="p-4 border {{ $address->is_primary ? 'border-primary bg-primary bg-opacity-5' : 'border-gray-200 bg-gray-50' }} rounded-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 transition-colors hover:border-gray-300">
                            <div class="flex-grow">
                                @if($address->is_primary)
                                    <span class="inline-block bg-primary text-gray-900 font-bold text-[10px] px-2 py-0.5 rounded-sm uppercase tracking-wider mb-2">Alamat Utama</span>
                                @endif
                                <p class="text-gray-800 font-medium text-sm leading-relaxed">{{ $address->address_line }}</p>
                            </div>
                            
                            <div class="flex items-center space-x-2 shrink-0 mt-3 sm:mt-0">
                                @if(!$address->is_primary)
                                    <form action="{{ route('address.primary', $address->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-white text-gray-700 font-semibold px-3 py-1.5 border border-gray-300 rounded-sm hover:bg-gray-50 text-xs shadow-sm transition-colors">
                                            Jadikan Utama
                                        </button>
                                    </form>
                                @endif
                                
                                <form action="{{ route('address.destroy', $address->id) }}" method="POST" x-data @submit.prevent="$dispatch('open-confirm', { title: 'Hapus Alamat', message: 'Anda yakin ingin menghapus alamat ini secara permanen?', form: $el })">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-white text-red-600 font-semibold px-3 py-1.5 border border-red-200 rounded-sm hover:bg-red-50 text-xs shadow-sm transition-colors">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

    <script>
        function addressForm() {
            return {
                addressLine: '{{ old('address_line') }}',
                villageCode: '{{ old('village_code') }}',
                searchResults: [],
                isSearching: false,
                searchError: '',

                searchVillage() {
                    if (this.addressLine.length < 5) {
                        this.searchResults = [];
                        return;
                    }

                    this.isSearching = true;
                    this.searchError = '';

                    fetch(`{{ route('address.search-village') }}?q=${encodeURIComponent(this.addressLine)}`)
                        .then(response => response.json())
                        .then(data => {
                            this.isSearching = false;
                            if (data.error) {
                                this.searchError = data.error;
                            } else if (data.results) {
                                this.searchResults = data.results;
                                if (data.results.length === 1) {
                                    this.selectVillage(data.results[0]);
                                }
                            }
                        })
                        .catch(error => {
                            this.isSearching = false;
                            this.searchError = 'Gagal terhubung ke server pencarian.';
                        });
                },

                selectVillage(result) {
                    this.villageCode = result.village_code;
                    // Do not auto-fill postal code, user must input it manually
                    this.searchResults = [];
                }
            }
        }
    </script>
</x-layout.public>
