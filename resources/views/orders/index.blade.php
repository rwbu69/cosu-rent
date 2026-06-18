<x-layout.public>
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 py-8 space-y-6">
        
        <h2 class="font-bold text-2xl text-gray-900 leading-tight mb-6">
            Riwayat Pesanan
        </h2>

        @if($bookings->isEmpty())
            <div class="bg-white p-12 border border-gray-200 rounded-sm shadow-sm text-center">
                <p class="text-lg font-medium text-gray-500 mb-6">Anda belum memiliki riwayat pesanan.</p>
                <a href="{{ route('catalog.index') }}" class="inline-block bg-light-primary text-gray-900 font-bold px-8 py-3 rounded-sm shadow-sm hover:bg-[#E5A5B0] transition-colors">
                    Mulai Menyewa
                </a>
            </div>
        @else
            <div class="space-y-8">
                @foreach($bookings as $booking)
                    <x-order-card :booking="$booking" />
                @endforeach
            </div>
        @endif

    </div>
</x-layout.public>
