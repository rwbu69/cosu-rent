<x-layout.public>
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 py-8 space-y-8">
        
        <h2 class="font-bold text-2xl text-gray-900 leading-tight mb-6">
            Manajemen Alamat Pengiriman
        </h2>

        <!-- Tambah Alamat Form -->
        <div class="bg-white p-8 border border-gray-200 rounded-sm shadow-sm">
            <h3 class="text-xl font-bold mb-4 text-gray-900">Tambah Alamat Baru</h3>
            <form action="{{ route('address.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="address_line" class="block font-medium mb-2 text-gray-700">Alamat Lengkap (termasuk Kota & Kode Pos)</label>
                    <textarea name="address_line" id="address_line" rows="3" required class="w-full border-gray-300 rounded-sm p-3 focus:ring-0 focus:border-primary shadow-sm" placeholder="Contoh: Jl. Sudirman No. 1, Jakarta Pusat, 10110">{{ old('address_line') }}</textarea>
                    @error('address_line')
                        <p class="text-red-600 font-medium mt-2 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="bg-primary text-gray-900 font-bold px-6 py-2.5 rounded-sm shadow-sm hover:bg-[#E5A5B0] transition-colors">
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
</x-layout.public>
