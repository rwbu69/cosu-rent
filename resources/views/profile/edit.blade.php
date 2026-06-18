<x-layout.public>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12 md:py-16 flex flex-col md:flex-row gap-8">
        
        <!-- Sidebar Navigation -->
        <div class="w-full md:w-1/4">
            <div class="bg-gray-900 border-2 border-gray-900 p-6 flex flex-col space-y-4 sticky top-24">
                <h3 class="text-white font-black text-2xl uppercase tracking-tight mb-4">Pengaturan</h3>
                <a href="#informasi-pribadi" class="text-white font-bold hover:text-primary transition-colors">Informasi Pribadi</a>
                <a href="#manajemen-alamat" class="text-white font-bold hover:text-primary transition-colors">Manajemen Alamat</a>
                <a href="#keamanan" class="text-white font-bold hover:text-primary transition-colors">Keamanan & Password</a>
                <a href="#hapus-akun" class="text-red-400 font-bold hover:text-red-500 transition-colors mt-8">Hapus Akun</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="w-full md:w-3/4 space-y-12">
            
            <!-- Section: Informasi Pribadi -->
            <section id="informasi-pribadi" class="bg-white border-2 border-gray-900 p-8 scroll-mt-24">
                <h2 class="font-black text-3xl text-gray-900 uppercase tracking-tight mb-2">Informasi Pribadi</h2>
                <p class="font-medium text-gray-600 mb-8 border-b-2 border-gray-900 pb-6">Perbarui nama, email, nomor kontak, dan detail rekening bank Anda.</p>
                @include('profile.partials.update-profile-information-form')
            </section>

            <!-- Section: Manajemen Alamat -->
            <section id="manajemen-alamat" class="bg-white border-2 border-gray-900 p-8 scroll-mt-24">
                <h2 class="font-black text-3xl text-gray-900 uppercase tracking-tight mb-2">Manajemen Alamat</h2>
                <p class="font-medium text-gray-600 mb-8 border-b-2 border-gray-900 pb-6">Atur alamat pengiriman untuk pesanan Anda.</p>
                @include('profile.partials.address-management')
            </section>

            <!-- Section: Keamanan -->
            <section id="keamanan" class="bg-white border-2 border-gray-900 p-8 scroll-mt-24">
                <h2 class="font-black text-3xl text-gray-900 uppercase tracking-tight mb-2">Keamanan</h2>
                <p class="font-medium text-gray-600 mb-8 border-b-2 border-gray-900 pb-6">Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.</p>
                @include('profile.partials.update-password-form')
            </section>

            <!-- Section: Hapus Akun -->
            <section id="hapus-akun" class="bg-white border-2 border-red-600 p-8 scroll-mt-24">
                <h2 class="font-black text-3xl text-red-600 uppercase tracking-tight mb-2">Hapus Akun</h2>
                <p class="font-medium text-gray-600 mb-8 border-b-2 border-red-600 pb-6">Setelah akun dihapus, semua sumber daya dan datanya akan dihapus secara permanen.</p>
                @include('profile.partials.delete-user-form')
            </section>

        </div>
    </div>
</x-layout.public>
