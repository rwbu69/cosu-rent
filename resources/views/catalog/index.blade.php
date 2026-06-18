<x-layout.public>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12 md:py-16">
        
        <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6 border-b-2 border-gray-900 pb-6">
            <div>
                <h2 class="font-black text-4xl text-gray-900 leading-tight uppercase tracking-tight">
                    Katalog Kostum
                </h2>
                <p class="text-gray-600 font-medium mt-2">Temukan kostum karakter favoritmu di sini.</p>
            </div>
            
            <form action="{{ route('catalog.index') }}" method="GET" class="flex flex-col sm:flex-row gap-0 w-full md:w-auto border-2 border-gray-900">
                <input type="text" name="search" placeholder="Cari nama atau seri..." value="{{ request('search') }}" class="border-0 p-3 focus:ring-0 w-full sm:w-64 font-medium text-gray-900 bg-white">
                <div class="w-px bg-gray-900 hidden sm:block"></div>
                <select name="size" class="border-0 p-3 focus:ring-0 font-medium text-gray-900 bg-white border-t-2 sm:border-t-0 border-gray-900">
                    <option value="">Semua Ukuran</option>
                    <option value="S" {{ request('size') == 'S' ? 'selected' : '' }}>S</option>
                    <option value="M" {{ request('size') == 'M' ? 'selected' : '' }}>M</option>
                    <option value="L" {{ request('size') == 'L' ? 'selected' : '' }}>L</option>
                    <option value="All Size" {{ request('size') == 'All Size' ? 'selected' : '' }}>All Size</option>
                </select>
                <button type="submit" class="bg-gray-900 text-white font-bold px-8 py-3 hover:bg-primary hover:text-gray-900 transition-colors border-l-2 border-gray-900 uppercase">Cari</button>
            </form>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($costumes as $costume)
                <div class="bg-white border-2 border-gray-900 flex flex-col group relative">
                    <div class="h-80 relative overflow-hidden bg-gray-100 border-b-2 border-gray-900">
                        @if($costume->image_path)
                            <img src="{{ asset('storage/' . $costume->image_path) }}" alt="{{ $costume->name }}" class="w-full h-full object-cover transition-all duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center font-bold text-gray-400">NO IMAGE</div>
                        @endif
                        <div class="absolute top-0 right-0 bg-primary text-gray-900 font-bold px-4 py-2 border-b-2 border-l-2 border-gray-900">
                            Rp {{ number_format($costume->base_price, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="p-6 flex flex-col flex-grow justify-between bg-white group-hover:bg-white transition-colors">
                        <div>
                            <p class="text-gray-500 font-bold text-xs uppercase tracking-widest mb-1">{{ $costume->series }}</p>
                            <h3 class="font-black text-2xl text-gray-900 mb-4 leading-tight">{{ $costume->name }}</h3>
                        </div>
                        <div class="mt-auto space-y-4">
                            <p class="text-xs font-bold text-gray-900 border-2 border-gray-900 inline-block px-3 py-1 bg-white">SIZE: {{ strtoupper($costume->size) }}</p>
                            <a href="{{ route('catalog.show', $costume->id) }}" class="block text-center bg-gray-900 text-white font-bold py-3 hover:bg-primary hover:text-gray-900 transition-colors border-2 border-transparent hover:border-gray-900 w-full">
                                Detail Kostum
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-24 bg-white border-2 border-gray-900 flex flex-col items-center justify-center">
                    <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-2xl font-black text-gray-900 uppercase">Kostum Tidak Ditemukan</p>
                    <p class="text-gray-600 font-medium mt-2">Coba sesuaikan kata kunci atau filter pencarian Anda.</p>
                </div>
            @endforelse
        </div>
        
        <div class="mt-12">
            {{ $costumes->links() }}
        </div>
    </div>
</x-layout.public>
