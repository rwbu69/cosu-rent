<x-layout.admin>
    <x-slot name="title">Tambah Kostum Baru</x-slot>

    <div class="max-w-5xl mx-auto">
        <form action="{{ route('admin.katalog.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8" x-data="componentsForm()">
            @csrf

            @if ($errors->any())
                <div class="p-4 mb-4 border-l-4 border-red-500 bg-red-50">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan pengisian form:</h3>
                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Basic Info -->
            <div class="p-8 bg-white border border-gray-200 rounded-sm shadow-sm">
                <h3 class="pb-2 mb-6 text-xl font-bold text-gray-900 border-b border-gray-200">Informasi Dasar</h3>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">Nama Kostum</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full p-3 border-gray-300 rounded-sm focus:ring-0 focus:border-primary">
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">Series / Anime</label>
                        <input type="text" name="series" value="{{ old('series') }}" required class="w-full p-3 border-gray-300 rounded-sm focus:ring-0 focus:border-primary">
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">Ukuran (Size)</label>
                        <input type="text" name="size" value="{{ old('size') }}" required class="w-full p-3 border-gray-300 rounded-sm focus:ring-0 focus:border-primary" placeholder="S, M, L, XL, All Size">
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">Harga Sewa per 3 Hari (Rp)</label>
                        <input type="number" name="base_price" value="{{ old('base_price') }}" required min="0" class="w-full p-3 border-gray-300 rounded-sm focus:ring-0 focus:border-primary">
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">Harga Uang Jaminan / Deposit (Rp)</label>
                        <input type="number" name="deposit_price" value="{{ old('deposit_price', 0) }}" required min="0" class="w-full p-3 border-gray-300 rounded-sm focus:ring-0 focus:border-primary">
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold text-gray-700">Gambar Kostum</label>
                        <input type="file" name="image" accept="image/png, image/jpeg, image/jpg" class="w-full border border-gray-300 rounded-sm p-2 focus:ring-0 text-sm font-medium bg-gray-50 text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-sm file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-gray-900 hover:file:bg-[#E5A5B0]">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block mb-2 font-semibold text-gray-700">Deskripsi Singkat</label>
                        <textarea name="description" rows="3" required class="w-full p-3 border-gray-300 rounded-sm focus:ring-0 focus:border-primary">{{ old('description') }}</textarea>
                    </div>
                    <div class="md:col-span-2 mt-4">
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" name="is_available" value="1" {{ old('is_available', true) ? 'checked' : '' }} class="w-5 h-5 border-gray-300 rounded text-primary focus:ring-primary">
                            <span class="font-semibold text-gray-700">Tersedia untuk Dirental</span>
                        </label>
                        <p class="mt-1 text-sm text-gray-500 ml-8">Centang jika kostum ini siap untuk dirental oleh pelanggan.</p>
                    </div>
                </div>
            </div>

            <!-- Components (Nested Form) -->
            <div class="p-8 bg-white border border-gray-200 rounded-sm shadow-sm">
                <div class="flex items-center justify-between pb-2 mb-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Komponen / Aksesoris</h3>
                    <button type="button" @click="addComponent" class="bg-light-primary text-gray-900 font-bold px-4 py-2 rounded-sm hover:bg-[#E5A5B0] transition-colors text-sm shadow-sm">
                        + Tambah Komponen
                    </button>
                </div>

                <div class="space-y-4">
                    <template x-for="(comp, index) in components" :key="comp.id">
                        <div class="flex items-start gap-4 p-4 border border-gray-200 rounded-sm bg-gray-50">
                            <div class="grid flex-grow grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <label class="block mb-1 text-sm font-semibold text-gray-700">Nama Komponen (cth: Wig, Sepatu)</label>
                                    <input type="text" x-bind:name="`components[${index}][name]`" x-model="comp.name" required class="w-full p-2 text-sm border-gray-300 rounded-sm focus:ring-0 focus:border-primary">
                                </div>
                                <div>
                                    <label class="block mb-1 text-sm font-semibold text-gray-700">Data QR Code (opsional)</label>
                                    <div class="relative">
                                        <input type="text" x-bind:name="`components[${index}][barcode]`" x-model="comp.barcode" @keydown.enter.prevent="comp.scanned = true; $event.target.blur()" class="w-full p-2 pr-8 text-sm border-gray-300 rounded-sm focus:ring-0 focus:border-primary" placeholder="Tembak QR Code di sini">
                                        <svg x-cloak x-show="comp.scanned" class="absolute right-2 top-1/2 -translate-y-1/2 w-5 h-5 text-[#F2B3BD]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block mb-1 text-sm font-semibold text-gray-700">Gambar Komponen <span class="text-red-500">*</span></label>
                                    <input type="file" x-bind:name="`components[${index}][image]`" required accept="image/png, image/jpeg, image/jpg" class="w-full p-2 text-sm bg-white border border-gray-300 rounded-sm file:mr-3 file:py-1 file:px-3 file:rounded-sm file:border-0 file:text-xs file:font-bold file:bg-gray-200 file:text-gray-800 hover:file:bg-gray-300">
                                </div>
                            </div>
                            <button type="button" @click="removeComponent(index)" class="p-2 mt-6 font-bold text-red-600 transition-colors bg-white border border-gray-300 rounded-sm shadow-sm hover:border-red-600" title="Hapus Komponen">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('admin.katalog.index') }}" class="px-8 py-3 font-bold text-gray-700 transition-colors bg-white border border-gray-300 rounded-sm shadow-sm hover:bg-gray-50">Batal</a>
                <button type="submit" class="bg-light-primary text-gray-900 font-bold px-8 py-3 rounded-sm hover:bg-[#E5A5B0] transition-colors shadow-sm">Simpan Kostum</button>
            </div>
        </form>
    </div>

    <script>
        function componentsForm() {
            return {
                @php
                    $oldComponents = old('components', []);
                    $initialComponents = [];
                    if (!empty($oldComponents)) {
                        foreach($oldComponents as $comp) {
                            $initialComponents[] = [
                                'id' => time() + rand(1, 10000),
                                'name' => $comp['name'] ?? '',
                                'barcode' => $comp['barcode'] ?? ''
                            ];
                        }
                    } else {
                        $initialComponents[] = ['id' => time(), 'name' => '', 'barcode' => '', 'scanned' => false];
                    }
                @endphp
                components: @json($initialComponents),
                addComponent() {
                    this.components.push({ id: Date.now() + Math.random(), name: '', barcode: '', scanned: false });
                },
                removeComponent(index) {
                    if(this.components.length > 1) {
                        this.components.splice(index, 1);
                    } else {
                        window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Kostum minimal harus memiliki 1 komponen.', type: 'error' } }));
                    }
                }
            }
        }
    </script>
</x-layout.admin>
