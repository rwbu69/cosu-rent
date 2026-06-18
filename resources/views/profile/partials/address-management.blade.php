<!-- Form Tambah Alamat -->
<div class="mb-10">
    <h3 class="text-xl font-bold mb-4 text-gray-900 uppercase">Tambah Alamat Baru</h3>
    <form action="{{ route('address.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="address_line" class="block font-bold mb-2 text-gray-900 uppercase text-sm">Alamat Lengkap (Termasuk Kota & Kode Pos)</label>
            <textarea name="address_line" id="address_line" rows="3" required class="w-full border-2 border-gray-900 p-3 focus:ring-0 focus:border-primary font-medium" placeholder="Contoh: Jl. Sudirman No. 1, Jakarta Pusat, 10110">{{ old('address_line') }}</textarea>
            @error('address_line')
                <p class="text-red-600 font-bold mt-2 text-sm">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit" class="bg-gray-900 text-white font-bold px-6 py-3 border-2 border-gray-900 hover:bg-primary hover:text-gray-900 transition-colors uppercase">
            Simpan Alamat
        </button>
    </form>
</div>

<!-- Daftar Alamat Tersimpan -->
<div>
    <h3 class="text-xl font-bold mb-6 text-gray-900 uppercase">Daftar Alamat Tersimpan</h3>
    
    @if($addresses->isEmpty())
        <div class="p-6 border-2 border-gray-900 bg-white text-center">
            <p class="text-gray-900 font-bold">Belum ada alamat yang disimpan.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($addresses as $address)
                <div class="p-6 border-2 {{ $address->is_primary ? 'border-primary bg-white' : 'border-gray-900 bg-white' }} flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 transition-colors">
                    <div class="flex-grow">
                        @if($address->is_primary)
                            <span class="inline-block bg-primary text-gray-900 font-bold text-[10px] px-2 py-1 uppercase tracking-wider mb-2 border-2 border-gray-900">Alamat Utama</span>
                        @endif
                        <p class="text-gray-900 font-medium leading-relaxed">{{ $address->address_line }}</p>
                    </div>
                    
                    <div class="flex items-center space-x-2 shrink-0 mt-3 sm:mt-0">
                        @if(!$address->is_primary)
                            <form action="{{ route('address.primary', $address->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-white text-gray-900 font-bold px-4 py-2 border-2 border-gray-900 hover:bg-gray-100 text-xs transition-colors uppercase">
                                    Jadikan Utama
                                </button>
                            </form>
                        @endif
                        
                        <form action="{{ route('address.destroy', $address->id) }}" method="POST" x-data @submit.prevent="$dispatch('open-confirm', { title: 'Hapus Alamat', message: 'Anda yakin ingin menghapus alamat ini secara permanen?', form: $el })">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white font-bold px-4 py-2 border-2 border-gray-900 hover:bg-red-700 text-xs transition-colors uppercase">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
