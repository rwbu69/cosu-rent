<x-layout.admin>
    <x-slot name="title">Riwayat Pesanan</x-slot>

    <div x-data="{ modalOpen: false, currentBooking: null }">
        
        <div class="bg-white border border-gray-200 rounded-sm shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-white">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">ID & Pelanggan</th>
                        <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Kostum & Periode</th>
                        <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Total Pembayaran</th>
                        <th scope="col" class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">Bukti Transfer</th>
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
                                <div class="mt-1">
                                    <span class="inline-block px-2 py-0.5 text-xs font-bold rounded-sm border 
                                        @if($booking->status == 'Returned') border-green-400 text-green-700 bg-green-50
                                        @else border-gray-400 text-gray-700 bg-gray-50 @endif">
                                        {{ $booking->status === 'Returned' ? 'Selesai' : $booking->status }}
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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500 font-medium italic">
                                Tidak ada riwayat pesanan.
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
                <div x-show="modalOpen" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" @click="modalOpen = false" aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

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

    </div>
</x-layout.admin>
