<!-- Form Tambah Alamat -->
<div class="mb-10">
    <h3 class="text-xl font-bold mb-4 text-gray-900 uppercase">Tambah Alamat Baru</h3>
    <form action="{{ route('address.store') }}" method="POST" x-data="addressForm()">
        @csrf
        
        <input type="hidden" name="address_line" :value="getFullAddress()">
        
        <!-- Field 1: Pencarian Area (API) -->
        <div class="mb-6 p-4 border border-blue-100 bg-blue-50 rounded-md">
            <label for="search_query" class="block font-bold mb-2 text-blue-900 uppercase text-sm">1. Pilih Area / Kecamatan / Kota</label>
            <input type="text" id="search_query" x-model="searchQuery" @input.debounce.1000ms="searchVillage" autocomplete="off" class="w-full border border-blue-200 rounded-md p-3 focus:ring-0 focus:border-blue-500 font-medium" placeholder="Ketik nama kecamatan atau kota Anda...">
            <p class="text-xs text-blue-700 mt-1">Wajib dipilih dari daftar agar ongkos kirim bisa dihitung.</p>
            
            <!-- Search Results -->
            <div x-show="isSearching" class="text-sm text-blue-600 font-medium mt-2 animate-pulse">Sedang mencari area...</div>
            <div x-show="searchError" class="text-sm text-red-600 font-medium mt-2" x-text="searchError"></div>
            
            <div x-show="searchResults.length > 0" class="border border-gray-200 rounded-sm max-h-60 overflow-y-auto mt-2 bg-white shadow-sm relative z-10">
                <template x-for="result in searchResults" :key="result.village_code">
                    <div class="p-3 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition-colors" @click="selectVillage(result)">
                        <p class="text-sm font-bold text-gray-800" x-text="result.village"></p>
                    </div>
                </template>
            </div>

            <div x-show="searchResults.length === 0 && !isSearching && searchQuery.length > 3 && !villageCode" class="text-xs text-gray-500 italic mt-2">
                Tidak ditemukan area yang cocok. Coba kata kunci yang lebih spesifik.
            </div>
            
            <input type="hidden" name="village_code" required x-model="villageCode">
            @error('village_code')
                <p class="text-red-600 font-bold mt-2 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <!-- Field 2: Detail Alamat Manual -->
        <div class="mb-4">
            <label for="address_detail" class="block font-bold mb-2 text-gray-900 uppercase text-sm">2. Detail Alamat Rumah / Gedung</label>
            <textarea id="address_detail" rows="3" required x-model="addressDetail" class="w-full border border-gray-200 rounded-md p-3 focus:ring-0 focus:border-primary font-medium" placeholder="Contoh: Jl. Mawar Blok A No. 12, RT 01/RW 02, pagar warna hitam."></textarea>
            <p class="text-xs text-gray-500 mt-1">Detail nama jalan dan patokan agar kurir mudah menemukan alamat Anda.</p>
            @error('address_line')
                <p class="text-red-600 font-bold mt-2 text-sm">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Field 3: Kode Pos -->
        <div class="mb-6">
            <label for="postal_code" class="block font-bold mb-2 text-gray-900 uppercase text-sm">Kode Pos</label>
            <input type="text" name="postal_code" id="postal_code" required x-model="postalCode" class="w-full border border-gray-200 rounded-md p-3 focus:ring-0 focus:border-primary font-medium" placeholder="Contoh: 12210">
            @error('postal_code')
                <p class="text-red-600 font-bold mt-2 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="bg-gray-900 text-white font-bold px-6 py-3 border border-gray-200 rounded-md hover:bg-light-primary hover:text-gray-900 transition-colors uppercase">
            Simpan Alamat
        </button>
    </form>
</div>

<!-- Daftar Alamat Tersimpan -->
<div>
    <h3 class="text-xl font-bold mb-6 text-gray-900 uppercase">Daftar Alamat Tersimpan</h3>
    
    @if($addresses->isEmpty())
        <div class="p-6 border border-gray-200 rounded-md bg-white text-center">
            <p class="text-gray-900 font-bold">Belum ada alamat yang disimpan.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($addresses as $address)
                <div class="p-6 border-2 {{ $address->is_primary ? 'border-primary bg-white' : 'border-gray-900 bg-white' }} flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 transition-colors">
                    <div class="flex-grow">
                        @if($address->is_primary)
                            <span class="inline-block bg-primary text-gray-900 font-bold text-[10px] px-2 py-1 uppercase tracking-wider mb-2 border border-gray-200 rounded-md">Alamat Utama</span>
                        @endif
                        <p class="text-gray-900 font-medium leading-relaxed">{{ $address->address_line }}</p>
                        <p class="text-xs text-gray-500 mt-1">ID Tujuan: {{ $address->village_code }} | Kode Pos: {{ $address->postal_code }}</p>
                    </div>
                    
                    <div class="flex items-center space-x-2 shrink-0 mt-3 sm:mt-0">
                        @if(!$address->is_primary)
                            <form action="{{ route('address.primary', $address->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-white text-gray-900 font-bold px-4 py-2 border border-gray-200 rounded-md hover:bg-gray-100 text-xs transition-colors uppercase">
                                    Jadikan Utama
                                </button>
                            </form>
                        @endif
                        
                        <form action="{{ route('address.destroy', $address->id) }}" method="POST" x-data @submit.prevent="$dispatch('open-confirm', { title: 'Hapus Alamat', message: 'Anda yakin ingin menghapus alamat ini secara permanen?', form: $el })">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white font-bold px-4 py-2 border border-gray-200 rounded-md hover:bg-red-700 text-xs transition-colors uppercase">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
    function addressForm() {
        return {
            searchQuery: '',
            selectedAreaName: '',
            addressDetail: '',
            
            villageCode: '{{ old('village_code') }}',
            postalCode: '{{ old('postal_code') }}',
            
            searchResults: [],
            isSearching: false,
            searchError: '',

            getFullAddress() {
                if (this.selectedAreaName) {
                    return this.addressDetail ? this.addressDetail + ', ' + this.selectedAreaName : this.selectedAreaName;
                }
                return this.addressDetail;
            },

            searchVillage() {
                if (this.searchQuery.length < 4) {
                    this.searchResults = [];
                    return;
                }

                // If user modifies the search query after selecting, clear the selection
                if (this.searchQuery !== this.selectedAreaName) {
                    this.villageCode = '';
                    this.selectedAreaName = '';
                }

                this.isSearching = true;
                this.searchError = '';

                fetch(`{{ route('address.search-village') }}?q=${encodeURIComponent(this.searchQuery)}`)
                    .then(response => response.json())
                    .then(data => {
                        this.isSearching = false;
                        if (data.error) {
                            this.searchError = data.error;
                        } else if (data.results) {
                            this.searchResults = data.results;
                        }
                    })
                    .catch(error => {
                        this.isSearching = false;
                        this.searchError = 'Gagal terhubung ke server pencarian.';
                    });
            },

            selectVillage(result) {
                this.villageCode = result.village_code;
                this.postalCode = result.postal_code;
                this.searchQuery = result.village;
                this.selectedAreaName = result.village;
                this.searchResults = [];
            }
        }
    }
</script>
