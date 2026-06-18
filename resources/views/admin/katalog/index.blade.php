<x-layout.admin>
    <x-slot name="title">Manajemen Katalog Kostum</x-slot>

    <div class="space-y-6">
        
        <div class="flex justify-between items-center">
            <a href="{{ route('admin.katalog.create') }}" class="bg-light-primary text-gray-900 font-bold px-6 py-2 rounded-sm shadow-sm hover:bg-[#E5A5B0] transition-colors">
                + Tambah Kostum Baru
            </a>
        </div>

        <div class="bg-white border border-gray-200 rounded-sm shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-white">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Kostum</th>
                        <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Series</th>
                        <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Harga/Hari</th>
                        <th scope="col" class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">Jml Komponen</th>
                        <th scope="col" class="px-6 py-3 text-right text-sm font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($costumes as $costume)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">{{ $costume->name }}</div>
                                <div class="text-xs text-gray-500">Size: {{ $costume->size }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $costume->series }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-primary">Rp {{ number_format($costume->base_price, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-sm border border-gray-300 bg-gray-50 text-gray-700">
                                    {{ $costume->components_count }} items
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <a href="{{ route('admin.katalog.edit', $costume->id) }}" class="inline-block bg-white text-gray-700 border border-gray-300 rounded-sm px-3 py-1 font-semibold hover:border-primary hover:text-primary transition-colors">Edit</a>
                                
                                <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'confirm-delete-{{ $costume->id }}')" class="inline-block bg-white text-red-600 border border-gray-300 rounded-sm px-3 py-1 font-semibold hover:border-red-600 transition-colors">Hapus</button>

                                <x-modal name="confirm-delete-{{ $costume->id }}" :show="false">
                                    <div class="p-6">
                                        <h2 class="text-lg font-bold text-gray-900">
                                            Konfirmasi Hapus Kostum
                                        </h2>
                                        <p class="mt-3 text-sm text-gray-600">
                                            Yakin ingin menghapus kostum <strong class="text-gray-900">{{ $costume->name }}</strong>? Seluruh data komponen juga akan terhapus secara permanen dan tidak dapat dikembalikan.
                                        </p>
                                        <div class="mt-6 flex justify-end gap-3">
                                            <button type="button" x-on:click="$dispatch('close')" class="bg-white text-gray-700 border border-gray-300 rounded-sm px-4 py-2 font-semibold hover:bg-gray-50 transition-colors">
                                                Batal
                                            </button>
                                            <form action="{{ route('admin.katalog.destroy', $costume->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-600 text-white rounded-sm px-4 py-2 font-semibold hover:bg-red-700 transition-colors shadow-sm">
                                                    Ya, Hapus Kostum
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </x-modal>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500 font-medium italic">
                                Belum ada data kostum.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $costumes->links() }}
        </div>

    </div>
</x-layout.admin>
