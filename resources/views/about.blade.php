<x-layout.public>
    <x-slot name="title">Tentang Kami</x-slot>

    <!-- Header Section -->
    <section class="py-16 border-b border-gray-100 md:py-24 bg-gray-50">
        <div class="px-4 mx-auto text-center max-w-7xl sm:px-6 lg:px-8" data-aos="fade-up">
            <h1 class="mb-6 text-4xl font-light text-gray-900 md:text-5xl">Tentang <span
                    class="font-semibold text-primary">CosuRent</span></h1>
            <p class="max-w-3xl mx-auto text-lg font-light leading-relaxed text-gray-500">
                Kami adalah platform sewa kostum cosplay terdepan yang menghubungkan kreativitas dan hobi Anda tanpa
                batas. Dengan koleksi kostum yang selalu diperbarui dan berkualitas tinggi, kami berkomitmen untuk
                membantu Anda tampil maksimal di setiap acara jejepangan, event cosplay, maupun sesi pemotretan.
            </p>
        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-16 md:grid-cols-2">
                <!-- Kiri: Info Gudang -->
                <div data-aos="fade-right">
                    <h2 class="pb-2 mb-6 text-2xl font-semibold text-gray-900 border-b border-gray-200">Informasi Gudang
                        & Alamat</h2>
                    <p class="mb-6 leading-relaxed text-gray-600">
                        Anda dapat mengunjungi gudang kami untuk proses pengambilan mandiri via <strong>Kiosk
                            RFID</strong> atau sekadar melihat-lihat kostum secara langsung dengan membuat janji temu
                        terlebih dahulu.
                    </p>

                    <div class="p-6 mb-6 border border-gray-100 rounded-sm shadow-sm bg-gray-50">
                        <h3 class="mb-2 text-sm font-bold tracking-wider text-gray-900 uppercase">Alamat Utama (CosuRent
                            HQ)</h3>
                        <p class="mb-4 text-gray-600">
                            Jalan Kertajaya Indah Timur No. 123<br>
                            Kecamatan Sukolilo, Surabaya<br>
                            Jawa Timur, 60111
                        </p>
                        <h3 class="mb-2 text-sm font-bold tracking-wider text-gray-900 uppercase">Jam Operasional</h3>
                        <ul class="space-y-1 text-gray-600">
                            <li><span class="inline-block w-24 font-medium text-gray-800">Senin - Jumat:</span> 09:00 -
                                18:00 WIB</li>
                            <li><span class="inline-block w-24 font-medium text-gray-800">Sabtu:</span> 10:00 - 15:00
                                WIB</li>
                            <li><span class="inline-block w-24 font-medium text-gray-800">Minggu:</span> Tutup</li>
                        </ul>
                    </div>
                </div>

                <!-- Kanan: Form Kontak WA -->
                <div data-aos="fade-left">
                    <h2 class="pb-2 mb-6 text-2xl font-semibold text-gray-900 border-b border-gray-200">Hubungi Kami
                        (WhatsApp)</h2>
                    <p class="mb-6 leading-relaxed text-gray-600">
                        Punya pertanyaan mengenai sewa, denda, atau ketersediaan kostum tertentu? Kirimkan pesan
                        langsung ke WhatsApp Admin kami melalui form di bawah ini.
                    </p>

                    <form x-data="{
                        name: '',
                        subject: 'Pertanyaan Umum',
                        message: '',
                        sendToWhatsApp() {
                            if (!this.name || !this.message) {
                                alert('Mohon lengkapi nama dan pesan terlebih dahulu.');
                                return;
                            }
                            const text = `Halo CosuRent!%0A%0A*Nama:* ${this.name}%0A*Subjek:* ${this.subject}%0A*Pesan:*%0A${this.message}`;
                            const phone = '6281234567890'; // Ganti dengan nomor asli admin Anda
                            window.open(`https://wa.me/${phone}?text=${text}`, '_blank');
                        }
                    }" @submit.prevent="sendToWhatsApp"
                        class="p-6 space-y-4 bg-white border border-gray-200 rounded-sm shadow-sm">

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Anda <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="name" x-model="name"
                                class="block w-full mt-1 border-gray-300 rounded-sm shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                                required placeholder="Cth: Budi Santoso">
                        </div>

                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700">Subjek</label>
                            <select id="subject" x-model="subject"
                                class="block w-full mt-1 border-gray-300 rounded-sm shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                <option value="Pertanyaan Umum">Pertanyaan Umum</option>
                                <option value="Ketersediaan Kostum">Ketersediaan Kostum</option>
                                <option value="Kendala Pesanan">Kendala Pesanan / Denda</option>
                                <option value="Kerja Sama">Kerja Sama / Partnership</option>
                            </select>
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700">Pesan <span
                                    class="text-red-500">*</span></label>
                            <textarea id="message" x-model="message" rows="4"
                                class="block w-full mt-1 border-gray-300 rounded-sm shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                                required placeholder="Tuliskan pertanyaan atau pesan Anda di sini..."></textarea>
                        </div>

                        <button type="submit"
                            class="w-full bg-[#E5A5B0] hover:bg-[#D48A96] text-gray-900 font-bold tracking-widest uppercase py-3 px-4 rounded-sm shadow-sm transition-colors flex justify-center items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.888-.788-1.489-1.761-1.662-2.06-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                            </svg>
                            Kirim ke WhatsApp
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-layout.public>
