<x-layout.public>
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-8 space-y-8">
        
        <!-- Breadcrumb -->
        <nav class="flex text-sm text-gray-500 font-medium" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('catalog.index') }}" class="hover:text-primary transition-colors">Katalog</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <span class="text-gray-700 font-semibold">{{ $costume->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <!-- Image/Visual Area -->
            <div class="bg-gray-50 border border-gray-200 rounded-sm flex items-center justify-center p-4 min-h-[400px] shadow-sm">
                @if($costume->image_path)
                    <img src="{{ asset('storage/' . $costume->image_path) }}" alt="{{ $costume->name }}" class="w-full h-auto object-cover rounded-sm border border-gray-200 shadow-sm">
                @else
                    <div class="text-center">
                        <h2 class="font-bold text-3xl text-gray-400 opacity-50">NO IMAGE</h2>
                    </div>
                @endif
            </div>

            <!-- Info Area -->
            <div class="space-y-6">
                <div>
                    <p class="text-xs font-semibold bg-primary text-gray-900 inline-block px-3 py-1 rounded-sm shadow-sm mb-4 uppercase tracking-wider">{{ $costume->series }}</p>
                    <h1 class="font-extrabold text-4xl text-gray-900 mb-2 leading-tight">{{ $costume->name }}</h1>
                    <p class="text-3xl font-bold text-gray-800 mt-4">Rp {{ number_format($costume->base_price, 0, ',', '.') }} <span class="text-lg text-gray-500 font-medium">/ 3 hari</span></p>
                    <p class="text-[11px] text-red-500 font-bold italic mt-1">*Harga di atas belum termasuk ongkos kirim</p>
                    <p class="text-sm font-semibold text-gray-500 mt-2">Uang Jaminan (Deposit): Rp {{ number_format($costume->deposit_price, 0, ',', '.') }}</p>
                </div>

                <div class="bg-white border border-gray-200 rounded-sm p-6 shadow-sm">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="text-gray-500">Ukuran:</span>
                        <span class="font-bold text-gray-900 bg-gray-100 px-3 py-1 rounded-sm text-sm">{{ $costume->size }}</span>
                    </div>
                    <p class="text-gray-600 leading-relaxed">{{ $costume->description }}</p>
                </div>

                <div class="bg-white border border-gray-200 rounded-sm p-6 shadow-sm">
                    <h4 class="text-lg font-bold border-b border-gray-200 pb-3 mb-4 text-gray-900">Kelengkapan Kostum</h4>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        @foreach($costume->components as $component)
                            <div class="flex flex-col items-center p-3 border border-gray-200 rounded-sm bg-gray-50 text-center">
                                @if($component->image_path)
                                    <img src="{{ asset('storage/' . $component->image_path) }}" alt="{{ $component->name }}" class="w-16 h-16 object-cover rounded-sm border border-gray-200 mb-2 shadow-sm">
                                @else
                                    <div class="w-16 h-16 bg-gray-200 border border-gray-300 rounded-sm mb-2 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                                <span class="text-sm font-semibold text-gray-800">{{ $component->name }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Section -->
        <div class="bg-white border border-gray-200 rounded-sm p-8 max-w-4xl mx-auto mt-12 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-primary"></div>
            <h4 class="text-2xl font-bold mb-2 text-center text-gray-900">Jadwal & Booking</h4>
            <p class="text-center text-gray-500 mb-8 font-medium">Pilih tanggal sewa untuk mengecek ketersediaan dan melanjutkan pesanan.</p>
            
            <form action="{{ route('cart.store') }}" method="POST" class="flex flex-col md:flex-row gap-4 justify-center items-center">
                @csrf
                <input type="hidden" name="costume_id" value="{{ $costume->id }}">
                <div class="w-full md:w-2/3 relative">
                    <input type="text" id="date_range" name="dates" required placeholder="Pilih rentang tanggal sewa..." class="w-full border-gray-300 rounded-sm p-4 font-semibold focus:ring-0 focus:border-primary bg-gray-50 text-center shadow-inner cursor-pointer hover:bg-white transition-colors">
                </div>
                
                <div class="flex w-full md:w-auto gap-2">
                    <button type="submit" name="action" value="add" class="flex-1 md:flex-none bg-white text-primary border-2 border-primary font-bold px-6 py-4 rounded-sm hover:bg-primary/10 transition-colors shadow-sm whitespace-nowrap">
                        + Keranjang
                    </button>
                    <button type="submit" name="action" value="checkout" class="flex-1 md:flex-none bg-light-primary text-gray-900 font-bold px-8 py-4 rounded-sm hover:bg-[#E5A5B0] transition-colors shadow-sm whitespace-nowrap">
                        Langsung Checkout
                    </button>
                </div>
            </form>
        </div>
        
    </div>

    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get booked dates from backend
            const bookedRanges = [
                @foreach($costume->bookings as $booking)
                    @if(in_array($booking->status, ['Pending', 'Menunggu Konfirmasi', 'Diproses', 'Dikirim ke Customer', 'Sedang Dirental', 'Dikirim Kembali']))
                    {
                        from: "{{ $booking->start_date->format('Y-m-d') }}",
                        to: "{{ $booking->end_date->format('Y-m-d') }}"
                    },
                    @endif
                @endforeach
            ];

            flatpickr("#date_range", {
                mode: "range",
                minDate: "today",
                disable: bookedRanges,
                dateFormat: "Y-m-d",
                showMonths: window.innerWidth > 768 ? 2 : 1,
            });
        });
    </script>
</x-layout.public>
