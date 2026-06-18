<x-layout.admin>
    <x-slot name="title">Pesanan Aktif</x-slot>

    <div x-data="{ modalOpen: false, returnModalOpen: false, currentBooking: null, shipModalOpen: false, shipActionUrl: '', feeModalOpen: false, feeActionUrl: '' }">
        
        <div class="bg-white border border-gray-200 rounded-sm shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-white">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">ID & Pelanggan</th>
                        <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Kostum & Periode</th>
                        <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Total Pembayaran</th>
                        <th scope="col" class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">Bukti Transfer</th>
                        <th scope="col" class="px-6 py-3 text-right text-sm font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">#{{ $booking->id }}</div>
                                <div class="text-sm text-gray-900">{{ $booking->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ '@' . $booking->user->username }}</div>
                                @if($booking->order_group_id)
                                    <div class="text-[10px] bg-gray-200 text-gray-700 px-1 py-0.5 mt-1 rounded-sm inline-block">Grouped</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">{{ $booking->costume->name }}</div>
                                <div class="text-xs text-gray-600">
                                    {{ \Carbon\Carbon::parse($booking->start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}
                                </div>
                                <div class="text-xs font-bold text-gray-500 mt-1 flex items-center gap-1 uppercase tracking-wider">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                                    {{ str_replace('_', ' - ', $booking->shipping_courier ?? 'Ekspedisi') }}
                                </div>
                                <div class="mt-1">
                                    <span class="inline-block px-2 py-0.5 text-xs font-bold rounded-sm border 
                                        @if($booking->status == 'Menunggu Konfirmasi') border-yellow-400 text-yellow-700 bg-yellow-50 
                                        @elseif($booking->status == 'Diproses' || $booking->status == 'Sedang Dikirim') border-blue-400 text-blue-700 bg-blue-50 
                                        @elseif($booking->status == 'Sedang Dirental') border-purple-400 text-purple-700 bg-purple-50 
                                        @elseif($booking->status == 'Returned') border-green-400 text-green-700 bg-green-50
                                        @else border-gray-400 text-gray-700 bg-gray-50 @endif">
                                        {{ $booking->status }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-primary">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($booking->payment_proof)
                                    <button type="button" @click="currentBooking = {{ json_encode([
                                        'id' => $booking->id,
                                        'user' => $booking->user->name,
                                        'costume' => $booking->costume->name,
                                        'total' => 'Rp ' . number_format($booking->total_price, 0, ',', '.'),
                                        'address' => $booking->shipping_address,
                                        'proof_url' => asset('storage/' . $booking->payment_proof)
                                    ]) }}; modalOpen = true" 
                                    class="inline-block bg-white text-gray-700 border border-gray-300 rounded-sm px-3 py-1 font-semibold text-xs hover:border-primary hover:text-primary transition-colors">
                                        Lihat Bukti
                                    </button>
                                @else
                                    <span class="text-xs text-gray-500 italic">Tidak ada bukti</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                @if($booking->status === 'Menunggu Hitung Ongkir')
                                    <button type="button" @click="feeActionUrl = '{{ route('admin.bookings.fee', $booking->id) }}'; feeModalOpen = true" class="inline-block bg-yellow-400 text-yellow-900 rounded-sm px-4 py-2 font-semibold hover:bg-yellow-500 transition-colors shadow-sm">Input Ongkir</button>
                                @elseif($booking->status === 'Menunggu Konfirmasi')
                                    <form action="{{ route('admin.bookings.confirm', $booking->id) }}" method="POST" class="inline-block" x-data @submit.prevent="$dispatch('open-confirm', { title: 'Konfirmasi Pesanan', message: 'Anda yakin ingin mengonfirmasi pesanan ini?', form: $el })">
                                        @csrf
                                        <button type="submit" class="inline-block bg-light-primary text-gray-900 rounded-sm px-4 py-2 font-semibold hover:bg-[#E5A5B0] transition-colors shadow-sm">Konfirmasi</button>
                                    </form>
                                @elseif($booking->status === 'Diproses')
                                    <button type="button" @click="currentBooking = { courier: '{{ strtoupper(str_replace('_', ' - ', $booking->shipping_courier ?? 'Ekspedisi')) }}' }; shipActionUrl = '{{ route('admin.bookings.ship', $booking->id) }}'; shipModalOpen = true" class="inline-block bg-blue-500 text-white rounded-sm px-4 py-2 font-semibold hover:bg-blue-600 transition-colors shadow-sm">Kirim</button>
                                @elseif(in_array($booking->status, ['Dikirim Kembali', 'Returned']))
                                    @if($booking->return_shipping_receipt)
                                        <button type="button" @click="currentBooking = {{ json_encode([
                                            'user' => $booking->user->name,
                                            'courier' => strtoupper(str_replace('_', ' - ', $booking->return_shipping_courier ?? '-')),
                                            'receipt' => $booking->return_shipping_receipt,
                                            'proof_url' => $booking->return_shipping_image_path ? asset('storage/' . $booking->return_shipping_image_path) : null
                                        ]) }}; returnModalOpen = true" 
                                        class="inline-block bg-white text-purple-700 border border-purple-300 rounded-sm px-3 py-1 font-semibold text-xs hover:border-purple-500 hover:text-purple-600 transition-colors">
                                            Lihat Retur
                                        </button>
                                    @else
                                        <span class="text-gray-400 italic text-xs">Tidak ada aksi</span>
                                    @endif
                                @else
                                    <span class="text-gray-400 italic text-xs">Tidak ada aksi</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500 font-medium italic">
                                Tidak ada pesanan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $bookings->links() }}
        </div>

        <!-- Modal Lihat Bukti -->
        <div x-show="modalOpen" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div x-show="modalOpen" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" @click="modalOpen = false" aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal panel -->
                <div x-show="modalOpen" x-transition.scale.origin.bottom class="inline-block align-bottom bg-white rounded-sm text-left overflow-hidden transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full shadow-xl">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-200">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-xl leading-6 font-bold text-gray-900 mb-4" id="modal-title">
                                    Bukti Pembayaran: <span x-text="currentBooking?.user"></span>
                                </h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div class="bg-white border border-gray-200 rounded-sm p-3">
                                        <p class="text-xs text-gray-500 font-bold uppercase mb-1">Kostum</p>
                                        <p class="text-sm font-semibold text-gray-900" x-text="currentBooking?.costume"></p>
                                    </div>
                                    <div class="bg-white border border-gray-200 rounded-sm p-3">
                                        <p class="text-xs text-gray-500 font-bold uppercase mb-1">Total</p>
                                        <p class="text-sm font-bold text-primary" x-text="currentBooking?.total"></p>
                                    </div>
                                </div>
                                
                                <div class="bg-white border border-gray-200 rounded-sm p-3 mb-6">
                                    <p class="text-xs text-gray-500 font-bold uppercase mb-1">Alamat Pengiriman</p>
                                    <p class="text-sm font-medium text-gray-900" x-text="currentBooking?.address"></p>
                                </div>

                                <div class="border border-gray-200 rounded-sm max-h-96 overflow-auto">
                                    <template x-if="currentBooking?.proof_url">
                                        <img :src="currentBooking.proof_url" alt="Bukti Transfer" class="w-full h-auto object-contain">
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" class="w-full inline-flex justify-center rounded-sm border border-gray-300 shadow-sm px-6 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-primary focus:outline-none sm:ml-3 sm:w-auto sm:text-sm transition-colors" @click="modalOpen = false">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Lihat Retur -->
        <div x-show="returnModalOpen" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="returnModalOpen" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" @click="returnModalOpen = false" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="returnModalOpen" x-transition.scale.origin.bottom class="inline-block align-bottom bg-white rounded-sm text-left overflow-hidden transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full shadow-xl">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-200">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-xl leading-6 font-bold text-gray-900 mb-4" id="modal-title">
                                    Informasi Retur: <span x-text="currentBooking?.user"></span>
                                </h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                    <div class="bg-white border border-purple-200 bg-purple-50 rounded-sm p-3">
                                        <p class="text-xs text-purple-900 font-bold uppercase mb-1">Kurir</p>
                                        <p class="text-sm font-semibold text-gray-900" x-text="currentBooking?.courier"></p>
                                    </div>
                                    <div class="bg-white border border-purple-200 bg-purple-50 rounded-sm p-3">
                                        <p class="text-xs text-purple-900 font-bold uppercase mb-1">Nomor Resi</p>
                                        <p class="text-sm font-bold text-gray-900" x-text="currentBooking?.receipt"></p>
                                    </div>
                                </div>
                                
                                <div class="border border-gray-200 rounded-sm max-h-96 overflow-auto">
                                    <template x-if="currentBooking?.proof_url">
                                        <img :src="currentBooking.proof_url" alt="Foto Paket Retur" class="w-full h-auto object-contain">
                                    </template>
                                    <template x-if="!currentBooking?.proof_url">
                                        <div class="p-8 text-center text-gray-500 italic">Tidak ada foto paket retur</div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" class="w-full inline-flex justify-center rounded-sm border border-gray-300 shadow-sm px-6 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-primary focus:outline-none sm:ml-3 sm:w-auto sm:text-sm transition-colors" @click="returnModalOpen = false">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Form Ongkir -->
        <div x-show="feeModalOpen" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="feeModalOpen" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" @click="feeModalOpen = false" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="feeModalOpen" x-transition.scale.origin.bottom class="inline-block align-bottom bg-white rounded-sm text-left overflow-hidden transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full shadow-xl">
                    <form :action="feeActionUrl" method="POST" @submit="feeModalOpen = false">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-200">
                            <h3 class="text-xl leading-6 font-bold text-gray-900 mb-6">Input Ongkos Kirim Manual</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block mb-1 text-sm font-semibold text-gray-700">Biaya Ongkir (Rp)</label>
                                    <input type="number" name="shipping_fee" required min="0" class="w-full p-2 text-sm border-gray-300 rounded-sm focus:ring-0 focus:border-primary" placeholder="Contoh: 15000">
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                            <button type="submit" class="w-full inline-flex justify-center rounded-sm border border-transparent shadow-sm px-6 py-2 bg-yellow-400 text-base font-medium text-yellow-900 hover:bg-yellow-500 focus:outline-none sm:w-auto sm:text-sm transition-colors">
                                Simpan Ongkir
                            </button>
                            <button type="button" class="mt-3 w-full inline-flex justify-center rounded-sm border border-gray-300 shadow-sm px-6 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-primary focus:outline-none sm:mt-0 sm:w-auto sm:text-sm transition-colors" @click="feeModalOpen = false">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Form Kirim -->
        <div x-show="shipModalOpen" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="shipModalOpen" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" @click="shipModalOpen = false" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="shipModalOpen" x-transition.scale.origin.bottom class="inline-block align-bottom bg-white rounded-sm text-left overflow-hidden transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full shadow-xl">
                    <form :action="shipActionUrl" method="POST" enctype="multipart/form-data" @submit="shipModalOpen = false">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-200">
                            <h3 class="text-xl leading-6 font-bold text-gray-900 mb-6">Data Pengiriman</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block mb-1 text-sm font-semibold text-gray-700">Ekspedisi (Dipilih Pelanggan)</label>
                                    <div class="p-2 text-sm bg-gray-50 text-gray-900 border border-gray-200 rounded-sm font-bold" x-text="currentBooking?.courier || 'Ekspedisi'"></div>
                                    <input type="hidden" name="shipping_courier" :value="currentBooking?.courier || 'Ekspedisi'">
                                </div>
                                <div>
                                    <label class="block mb-1 text-sm font-semibold text-gray-700">Nomor Resi</label>
                                    <input type="text" name="shipping_receipt" required class="w-full p-2 text-sm border-gray-300 rounded-sm focus:ring-0 focus:border-primary" placeholder="Masukkan nomor resi">
                                </div>
                                <div>
                                    <label class="block mb-1 text-sm font-semibold text-gray-700">Foto Paket</label>
                                    <input type="file" name="shipping_image" required accept="image/png, image/jpeg, image/jpg" class="w-full p-2 text-sm bg-white border border-gray-300 rounded-sm file:mr-3 file:py-1 file:px-3 file:rounded-sm file:border-0 file:text-xs file:font-bold file:bg-gray-200 file:text-gray-800 hover:file:bg-gray-300">
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                            <button type="submit" class="w-full inline-flex justify-center rounded-sm border border-transparent shadow-sm px-6 py-2 bg-light-primary text-base font-medium text-gray-900 hover:bg-[#E5A5B0] focus:outline-none sm:w-auto sm:text-sm transition-colors">
                                Simpan & Kirim
                            </button>
                            <button type="button" class="mt-3 w-full inline-flex justify-center rounded-sm border border-gray-300 shadow-sm px-6 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-primary focus:outline-none sm:mt-0 sm:w-auto sm:text-sm transition-colors" @click="shipModalOpen = false">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-layout.admin>
