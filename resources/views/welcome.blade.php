<x-layout.public>
    <!-- Nihilist Hero Section -->
    <section class="relative flex items-center justify-start min-h-[600px] md:min-h-[750px] bg-black">

        <!-- Background Image with High Opacity & Desaturation -->
        <div class="absolute inset-0">
            <img src="{{ asset('hero.jpg') }}" alt="Hero Background"
                class="object-cover object-top w-full h-full opacity-30">
        </div>

        <!-- Text Content on Top -->
        <div class="relative z-10 w-full px-4 py-16 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="max-w-4xl" data-aos="fade-up">
                <h1 class="mb-6 text-5xl font-light leading-tight text-white md:text-7xl lg:text-8xl">
                    Sewa <br />
                    Kostum <br />
                    <span class="font-semibold text-primary">Impianmu<span class="text-secondary">.</span></span>
                </h1>
                <p class="max-w-2xl pl-6 mb-12 text-lg font-light text-gray-400 border-l md:text-xl border-secondary">
                    Platform sewa kostum cosplay terpercaya. Pilih online, ambil super cepat via RFID, tampil maksimal
                    di event tanpa ribet.
                </p>
                <div class="flex flex-col gap-4 sm:flex-row">
                    <a href="{{ route('catalog.index') }}"
                        class="px-10 py-4 text-sm tracking-widest text-center text-white transition-all bg-light-primary hover:bg-opacity-80">
                        JELAJAHI KATALOG
                    </a>
                    <a href="#cara-sewa"
                        class="px-10 py-4 text-sm tracking-widest text-center text-white transition-all bg-transparent border border-white hover:border-secondary hover:text-secondary">
                        PELAJARI SISTEM
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Minimalist Featured Collection -->
    <section class="py-24 bg-white">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex flex-col items-start justify-between mb-16 md:flex-row md:items-end" data-aos="fade-up">
                <div>
                    <h2 class="text-3xl font-light text-black">Koleksi Kami</h2>
                    <p class="mt-2 text-sm text-gray-500">Kostum cosplay yang tersedia.</p>
                </div>
                <a href="{{ route('catalog.index') }}"
                    class="hidden pb-1 mt-4 text-sm tracking-wider text-gray-500 transition-colors border-b border-transparent md:inline-block hover:border-gray-500 hover:text-black md:mt-0">
                    Lihat Semua
                </a>
            </div>

            <div class="grid grid-cols-1 gap-12 md:grid-cols-3">
                @foreach ($featured as $index => $costume)
                    <!-- Professional Card Design -->
                    <div data-aos="fade-up" data-aos-delay="{{ $index * 100 }}"
                        class="relative flex flex-col overflow-hidden transition-transform duration-300 ease-out bg-white border border-gray-200 rounded-sm group hover:shadow-xl hover:-translate-y-2 transform-gpu">
                        <div class="relative overflow-hidden bg-gray-100 aspect-[4/5]">
                            @if ($costume->image_path)
                                <img src="{{ asset('storage/' . $costume->image_path) }}" alt="{{ $costume->name }}"
                                    class="object-cover w-full h-full transition-transform duration-700 ease-out transform-gpu will-change-transform group-hover:scale-105">
                            @else
                                <div
                                    class="flex items-center justify-center w-full h-full text-xs font-light tracking-widest text-gray-400">
                                    NO IMAGE</div>
                            @endif
                            <!-- Hover Overlay -->
                            <div
                                class="absolute inset-0 transition-opacity duration-300 ease-out opacity-0 pointer-events-none bg-gradient-to-t from-gray-900/80 via-gray-900/10 to-transparent group-hover:opacity-100">
                            </div>
                            <div
                                class="absolute bottom-0 left-0 right-0 p-6 transition-all duration-300 ease-out translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transform-gpu">
                                <a href="{{ route('catalog.show', $costume->id) }}"
                                    class="block w-full py-3 text-sm font-bold tracking-widest text-center text-gray-900 uppercase transition-colors bg-white rounded-sm shadow-sm hover:bg-light-primary">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                        <div class="flex flex-col flex-grow p-6">
                            <p class="mb-2 text-[10px] font-bold tracking-widest uppercase text-gray-400">
                                {{ $costume->series }}</p>
                            <h3 class="mb-4 text-lg font-extrabold text-gray-900 line-clamp-1"
                                title="{{ $costume->name }}">{{ $costume->name }}</h3>
                            <div class="flex flex-col items-end pt-4 mt-auto border-t border-gray-100">
                                <div class="flex items-center justify-between w-full mb-1">
                                    <span class="text-xs font-medium tracking-wider text-gray-500 uppercase">Sewa</span>
                                    <span class="text-base font-extrabold text-primary">Rp {{ number_format($costume->base_price, 0, ',', '.') }}</span>
                                </div>
                                <span class="text-[10px] text-gray-400 font-medium italic">*Belum termasuk ongkir</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-12 text-center md:hidden">
                <a href="{{ route('catalog.index') }}"
                    class="inline-block pb-1 text-sm tracking-wider text-gray-500 border-b border-gray-500">
                    Lihat Semua
                </a>
            </div>
        </div>
    </section>

    <!-- Bento Box "Cara Sewa" -->
    {{-- <section id="cara-sewa" class="py-16 bg-white md:py-24">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <h2 class="mb-12 text-4xl font-medium tracking-tight text-center text-gray-900 uppercase md:text-left">Sistem Sewa Modern</h2>

            <div class="grid grid-cols-1 md:grid-cols-12 md:grid-rows-2 gap-4 min-h-[500px]">

                <!-- Box 1: Large -->
                <div class="relative flex flex-col justify-between p-8 overflow-hidden text-white bg-gray-900 border border-gray-200 rounded-md md:col-span-8 md:row-span-2 md:p-12 group">
                    <div class="relative z-10">
                        <span class="block mb-4 text-6xl font-medium opacity-50 text-primary">01</span>
                        <h3 class="mb-4 text-3xl font-medium leading-tight uppercase md:text-5xl">Pilih & <br>Booking Online</h3>
                        <p class="max-w-md text-lg font-medium text-gray-300">Jelajahi katalog kami yang luas. Pilih ukuran yang pas, tentukan tanggal, dan selesaikan pembayaran dengan instan.</p>
                    </div>
                </div>

                <!-- Box 2: Medium Top Right -->
                <div class="flex flex-col justify-center p-8 transition-colors bg-white border border-gray-200 rounded-md md:col-span-4 md:row-span-1 hover:bg-primary group">
                    <span class="mb-2 text-4xl font-medium text-gray-900 opacity-20">02</span>
                    <h3 class="mb-2 text-2xl font-medium text-gray-900 uppercase">Ambil Cepat via RFID</h3>
                    <p class="font-medium text-gray-700 group-hover:text-gray-900">Tap kartu di Kiosk mandiri kami, kostum langsung siap diambil tanpa antrian loket.</p>
                </div>

                <!-- Box 3: Medium Bottom Right -->
                <div class="flex flex-col justify-center p-8 border border-gray-200 rounded-md md:col-span-4 md:row-span-1 bg-primary">
                    <span class="mb-2 text-4xl font-medium text-gray-900 opacity-20">03</span>
                    <h3 class="mb-2 text-2xl font-medium text-gray-900 uppercase">Pengecekan QR Code</h3>
                    <p class="font-medium text-gray-900">Pengembalian super akurat. Staf memindai QR Code di setiap komponen untuk menghindari denda kehilangan.</p>
                </div>

            </div>
        </div>
    </section> --}}
</x-layout.public>
