<x-layout.public>
    <!-- Sharp Editorial Hero Section with Background Image -->
    <section class="relative flex items-center justify-start overflow-hidden border-b-2 border-gray-900 min-h-[600px] md:min-h-[750px] bg-gray-900">
        
        <!-- Background Image with Opacity -->
        <div class="absolute inset-0">
            <img src="{{ asset('hero.jpg') }}" alt="Hero Background" class="object-cover object-top w-full h-full opacity-40">
        </div>

        <!-- Text Content on Top (Flush Left, No Box) -->
        <div class="relative z-10 w-full px-4 py-16 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="max-w-4xl">
                <h1 class="mb-6 text-6xl font-black leading-[0.9] tracking-tighter text-white uppercase md:text-8xl lg:text-9xl">
                    Sewa <br />
                    Kostum <br />
                    <span class="text-primary">Impianmu<span class="text-secondary">.</span></span>
                </h1>
                <p class="max-w-2xl mb-12 text-xl font-medium text-gray-200 md:text-2xl border-l-4 border-secondary pl-6">
                    Platform sewa kostum cosplay terpercaya. Pilih online, ambil super cepat via RFID, tampil maksimal di event tanpa ribet.
                </p>
                <div class="flex flex-col gap-4 sm:flex-row">
                    <a href="{{ route('catalog.index') }}"
                        class="px-10 py-5 font-black text-center text-gray-900 uppercase transition-colors bg-primary border-2 border-primary hover:bg-white hover:border-white">
                        Jelajahi Katalog
                    </a>
                    <a href="#cara-sewa"
                        class="px-10 py-5 font-black text-center text-white uppercase transition-colors bg-transparent border-2 border-white hover:bg-secondary hover:text-gray-900 hover:border-secondary">
                        Pelajari Sistem
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Sharp Featured Collection -->
    <section class="py-16 bg-white border-b-2 border-gray-900 md:py-24">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex flex-col items-end justify-between mb-12 md:flex-row">
                <div>
                    <h2 class="text-4xl font-black tracking-tight text-gray-900 uppercase">Koleksi Terpopuler</h2>
                    <p class="mt-2 font-medium text-gray-600">Kostum pilihan terbaik minggu ini.</p>
                </div>
                <a href="{{ route('catalog.index') }}"
                    class="hidden pb-1 font-bold text-gray-900 transition-colors border-b-2 border-gray-900 md:inline-block hover:text-primary hover:border-primary">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 gap-0 bg-gray-900 border-2 border-gray-900 md:grid-cols-3">
                @foreach ($featured as $index => $costume)
                    <!-- Sharp Cards, no spacing between, letting borders do the work -->
                    <div
                        class="bg-white border-b-2 md:border-b-0 {{ $index < count($featured) - 1 ? 'md:border-r-2' : '' }} border-gray-900 group flex flex-col relative">
                        <div class="relative overflow-hidden bg-gray-100 border-b-2 border-gray-900 h-80">
                            @if ($costume->image_path)
                                <img src="{{ asset('storage/' . $costume->image_path) }}" alt="{{ $costume->name }}"
                                    class="object-cover w-full h-full transition-all duration-500">
                            @else
                                <div class="flex items-center justify-center w-full h-full font-bold text-gray-400">NO
                                    IMAGE</div>
                            @endif
                            <div
                                class="absolute top-0 right-0 px-4 py-2 font-bold text-gray-900 border-b-2 border-l-2 border-gray-900 bg-primary">
                                Rp {{ number_format($costume->base_price, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="flex flex-col justify-between flex-grow p-6">
                            <div>
                                <p class="mb-1 text-xs font-bold tracking-widest text-gray-500 uppercase">
                                    {{ $costume->series }}</p>
                                <h3 class="mb-4 text-2xl font-black leading-tight text-gray-900">{{ $costume->name }}
                                </h3>
                            </div>
                            <a href="{{ route('catalog.show', $costume->id) }}"
                                class="block w-full py-3 font-bold text-center text-gray-900 transition-colors bg-white border-2 border-gray-900 hover:bg-gray-900 hover:text-white">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-8 text-center md:hidden">
                <a href="{{ route('catalog.index') }}"
                    class="inline-block pb-1 font-bold text-gray-900 border-b-2 border-gray-900">
                    Lihat Semua &rarr;
                </a>
            </div>
        </div>
    </section>

    <!-- Bento Box "Cara Sewa" -->
    {{-- <section id="cara-sewa" class="py-16 bg-white md:py-24">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <h2 class="mb-12 text-4xl font-black tracking-tight text-center text-gray-900 uppercase md:text-left">Sistem Sewa Modern</h2>

            <div class="grid grid-cols-1 md:grid-cols-12 md:grid-rows-2 gap-4 min-h-[500px]">

                <!-- Box 1: Large -->
                <div class="relative flex flex-col justify-between p-8 overflow-hidden text-white bg-gray-900 border-2 border-gray-900 md:col-span-8 md:row-span-2 md:p-12 group">
                    <div class="relative z-10">
                        <span class="block mb-4 text-6xl font-black opacity-50 text-primary">01</span>
                        <h3 class="mb-4 text-3xl font-black leading-tight uppercase md:text-5xl">Pilih & <br>Booking Online</h3>
                        <p class="max-w-md text-lg font-medium text-gray-300">Jelajahi katalog kami yang luas. Pilih ukuran yang pas, tentukan tanggal, dan selesaikan pembayaran dengan instan.</p>
                    </div>
                </div>

                <!-- Box 2: Medium Top Right -->
                <div class="flex flex-col justify-center p-8 transition-colors bg-white border-2 border-gray-900 md:col-span-4 md:row-span-1 hover:bg-primary group">
                    <span class="mb-2 text-4xl font-black text-gray-900 opacity-20">02</span>
                    <h3 class="mb-2 text-2xl font-black text-gray-900 uppercase">Ambil Cepat via RFID</h3>
                    <p class="font-medium text-gray-700 group-hover:text-gray-900">Tap kartu di Kiosk mandiri kami, kostum langsung siap diambil tanpa antrian loket.</p>
                </div>

                <!-- Box 3: Medium Bottom Right -->
                <div class="flex flex-col justify-center p-8 border-2 border-gray-900 md:col-span-4 md:row-span-1 bg-primary">
                    <span class="mb-2 text-4xl font-black text-gray-900 opacity-20">03</span>
                    <h3 class="mb-2 text-2xl font-black text-gray-900 uppercase">Pengecekan QR Code</h3>
                    <p class="font-medium text-gray-900">Pengembalian super akurat. Staf memindai QR Code di setiap komponen untuk menghindari denda kehilangan.</p>
                </div>

            </div>
        </div>
    </section> --}}
</x-layout.public>
