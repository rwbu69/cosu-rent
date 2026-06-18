<x-layout.admin>
    <x-slot name="title">Edit Kostum: {{ $katalog->name }}</x-slot>

    <div class="max-w-5xl mx-auto">
        <form action="{{ route('admin.katalog.update', $katalog->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8" x-data="componentsForm()">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
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
            <div class="bg-white p-8 border border-gray-200 rounded-sm shadow-sm">
                <h3 class="text-xl font-bold mb-6 border-b border-gray-200 pb-2 text-gray-900">Informasi Dasar</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700">Nama Kostum</label>
                        <input type="text" name="name" value="{{ old('name', $katalog->name) }}" required class="w-full border-gray-300 rounded-sm p-3 focus:ring-0 focus:border-primary">
                    </div>
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700">Series / Anime</label>
                        <input type="text" name="series" value="{{ old('series', $katalog->series) }}" required class="w-full border-gray-300 rounded-sm p-3 focus:ring-0 focus:border-primary">
                    </div>
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700">Ukuran (Size)</label>
                        <input type="text" name="size" value="{{ old('size', $katalog->size) }}" required class="w-full border-gray-300 rounded-sm p-3 focus:ring-0 focus:border-primary">
                    </div>
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700">Harga Sewa per Hari (Rp)</label>
                        <input type="number" name="base_price" value="{{ old('base_price', $katalog->base_price) }}" required min="0" class="w-full border-gray-300 rounded-sm p-3 focus:ring-0 focus:border-primary">
                    </div>
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700">Harga Uang Jaminan / Deposit (Rp)</label>
                        <input type="number" name="deposit_price" value="{{ old('deposit_price', $katalog->deposit_price) }}" required min="0" class="w-full border-gray-300 rounded-sm p-3 focus:ring-0 focus:border-primary">
                    </div>
                    <div>
                        <label class="block font-semibold mb-2 text-gray-700">Gambar Kostum (Opsional jika tidak diganti)</label>
                        <input type="file" name="image" accept="image/png, image/jpeg, image/jpg" class="w-full border border-gray-300 rounded-sm p-2 focus:ring-0 text-sm font-medium bg-gray-50 text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-sm file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-gray-900 hover:file:bg-[#E5A5B0]">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block font-semibold mb-2 text-gray-700">Deskripsi Singkat</label>
                        <textarea name="description" rows="3" required class="w-full border-gray-300 rounded-sm p-3 focus:ring-0 focus:border-primary">{{ old('description', $katalog->description) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Components (Nested Form) -->
            <div class="bg-white p-8 border border-gray-200 rounded-sm shadow-sm">
                <div class="flex justify-between items-center mb-6 border-b border-gray-200 pb-2">
                    <h3 class="text-xl font-bold text-gray-900">Komponen / Aksesoris</h3>
                    <button type="button" @click="addComponent" class="bg-primary text-gray-900 font-bold px-4 py-2 rounded-sm hover:bg-[#E5A5B0] transition-colors text-sm shadow-sm">
                        + Tambah Komponen
                    </button>
                </div>

                <div class="space-y-4">
                    <template x-for="(comp, index) in components" :key="comp.temp_id">
                        <div class="flex items-start gap-4 p-4 border border-gray-200 rounded-sm bg-gray-50">
                            <input type="hidden" x-bind:name="`components[${index}][id]`" x-model="comp.id">
                            <div class="flex-grow grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-semibold text-gray-700 text-sm mb-1">Nama Komponen</label>
                                    <input type="text" x-bind:name="`components[${index}][name]`" x-model="comp.name" required class="w-full border-gray-300 rounded-sm p-2 focus:ring-0 focus:border-primary text-sm">
                                </div>
                                <div>
                                    <label class="block font-semibold text-gray-700 text-sm mb-1">Data QR Code</label>
                                    <div class="relative">
                                        <input type="text" x-bind:name="`components[${index}][barcode]`" x-model="comp.barcode" @keydown.enter.prevent="comp.scanned = true; $event.target.blur()" required class="w-full border-gray-300 rounded-sm p-2 focus:ring-0 focus:border-primary text-sm pr-8">
                                        <svg x-cloak x-show="comp.scanned" class="absolute right-2 top-1/2 -translate-y-1/2 w-5 h-5 text-[#F2B3BD]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block font-semibold text-gray-700 text-sm mb-1">
                                        Gambar Komponen <span class="text-red-500" x-show="!comp.has_image">*</span>
                                        <template x-if="comp.has_image">
                                            <span class="text-xs text-primary font-bold ml-2">(Gambar sudah ada, unggah baru untuk mengganti)</span>
                                        </template>
                                    </label>
                                    <input type="file" x-bind:name="`components[${index}][image]`" x-bind:required="!comp.has_image" accept="image/png, image/jpeg, image/jpg" class="w-full border border-gray-300 rounded-sm p-2 text-sm bg-white file:mr-3 file:py-1 file:px-3 file:rounded-sm file:border-0 file:text-xs file:font-bold file:bg-gray-200 file:text-gray-800 hover:file:bg-gray-300">
                                </div>
                            </div>
                            <button type="button" @click="removeComponent(index)" class="mt-6 bg-white text-red-600 font-bold p-2 border border-gray-300 rounded-sm hover:border-red-600 transition-colors shadow-sm" title="Hapus Komponen">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('admin.katalog.index') }}" class="bg-white text-gray-700 font-bold px-8 py-3 rounded-sm border border-gray-300 hover:bg-gray-50 transition-colors shadow-sm">Batal</a>
                <button type="submit" class="bg-primary text-gray-900 font-bold px-8 py-3 rounded-sm hover:bg-[#E5A5B0] transition-colors shadow-sm">Update Kostum</button>
            </div>
        </form>
    </div>

    <script>
        function componentsForm() {
            return {
                @php
                    $oldComponents = old('components');
                    $initialComponents = [];
                    
                    if (!empty($oldComponents)) {
                        foreach($oldComponents as $comp) {
                            $initialComponents[] = [
                                'id' => $comp['id'] ?? '',
                                'temp_id' => time() + rand(1, 10000),
                                'name' => $comp['name'] ?? '',
                                'barcode' => $comp['barcode'] ?? '',
                                'scanned' => false,
                                'has_image' => false
                            ];
                        }
                    } else {
                        $initialComponents = $katalog->components->map(function($comp) {
                            return [
                                'id' => $comp->id,
                                'temp_id' => $comp->id,
                                'name' => $comp->name,
                                'barcode' => $comp->barcode_string ?? $comp->barcode,
                                'has_image' => !empty($comp->image_path),
                                'scanned' => false
                            ];
                        })->values()->all();
                    }
                @endphp
                components: @json($initialComponents),
                addComponent() {
                    this.components.push({ id: '', temp_id: Date.now() + Math.random(), name: '', barcode: '', has_image: false, scanned: false });
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
