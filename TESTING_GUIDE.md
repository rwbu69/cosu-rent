# Panduan Testing CosuRent

Dokumen ini berisi panduan langkah demi langkah untuk melakukan pengujian fungsionalitas perangkat keras (RFID & Scanner QR) serta integrasi email (Mailtrap) pada sistem CosuRent.

---

## 1. Panduan Testing RFID (Pengambilan Mandiri / Kiosk)

Sistem RFID pada CosuRent digunakan oleh pelanggan untuk mengambil pesanan secara mandiri di Kiosk. Scanner RFID fisik bekerja dengan prinsip *Keyboard Emulation* (membaca UID kartu dan menekan tombol `ENTER` secara otomatis).

### Persiapan Data:
1. **Daftarkan RFID Pelanggan**:
   - Login sebagai **Admin**.
   - Buka menu **Data Pelanggan** di sidebar admin.
   - Pilih salah satu pelanggan, lalu edit profilnya.
   - Masukkan kombinasi angka/karakter simulasi UID RFID (misal: `0012345678`) ke dalam kolom **RFID UID**.
   - Simpan perubahan.
2. **Buat Pesanan Aktif**:
   - Login menggunakan akun pelanggan tersebut.
   - Lakukan penyewaan kostum hingga status pesanan menjadi **"Disetujui"** atau siap diambil.

### Skenario Testing:
1. Buka halaman Kiosk Pengambilan Mandiri di browser (navigasi ke rute `/kiosk` atau menu yang tersedia).
2. Pastikan kursor (*focus*) berada pada *input field* atau Kiosk dalam keadaan *Ready / Scanner Aktif*.
3. **Menggunakan Alat Fisik**: Tap kartu RFID yang telah didaftarkan ke *RFID Reader*.
4. **Tanpa Alat Fisik (Simulasi)**: Ketik manual UID RFID yang tadi didaftarkan (misal: `0012345678`) pada keyboard, lalu tekan **ENTER**.
5. **Ekspektasi Hasil**: Kiosk akan memunculkan notifikasi berhasil, layar akan menampilkan detail pesanan yang diambil, dan status pesanan di sistem berubah menjadi **"Sedang Dirental"**.

---

## 2. Panduan Testing QR Code / Barcode (Pengembalian & QC)

Sistem Scanner QR Code digunakan oleh Admin di gudang untuk melakukan Pengecekan Kualitas (QC) saat pelanggan mengembalikan kostum untuk memastikan tidak ada komponen (aksesori/wig/baju) yang tertukar atau hilang.

### Persiapan Data:
1. **Dapatkan Barcode Komponen**:
   - Buka menu **Manajemen Katalog** di area Admin.
   - Lihat detail salah satu kostum yang sedang disewa.
   - Catat `barcode_string` dari masing-masing komponen kostum tersebut (misalnya: `COMP-A-001`, `COMP-A-002`).

### Skenario Testing:
1. Login sebagai **Admin** dan masuk ke menu **Pengembalian & QC** (`/admin/return`).
2. Pilih pesanan yang ingin dikembalikan oleh pelanggan.
3. Kursor harus aktif/fokus pada halaman (biasanya ada indikator *Scanner Aktif* di header).
4. **Menggunakan Alat Fisik**: Tembak/Scan QR Code yang tertempel pada fisik komponen kostum menggunakan *Barcode/QR Scanner*.
5. **Tanpa Alat Fisik (Simulasi)**: Ketik string barcode (misal: `COMP-A-001`) pada input tersembunyi/kolom input QC lalu tekan **ENTER**.
6. **Ekspektasi Hasil**: Sistem akan mencentang otomatis komponen tersebut di layar monitor. Jika ada komponen yang tidak di-scan (tidak dicentang) hingga proses diakhiri, sistem akan mengklasifikasikan komponen tersebut sebagai **"Hilang/Rusak"** dan memotong deposit secara otomatis untuk denda.

---

## 3. Panduan Testing Mailtrap (Notifikasi Email)

CosuRent kini telah memiliki **5 fase notifikasi email** terintegrasi menggunakan *driver* SMTP. Mailtrap digunakan sebagai *server SMTP dummy* agar email tidak benar-benar terkirim ke alamat asli pelanggan selama masa testing.

### Persiapan (Setup):
1. Buat akun gratis di [Mailtrap.io](https://mailtrap.io).
2. Di *dashboard* Mailtrap, buat *inbox* baru (misal: "CosuRent Testing").
3. Pilih *Integration: Laravel* untuk melihat kredensial Anda.
4. Buka file `.env` dan sesuaikan kredensial `MAIL_*`:
```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=username_dari_mailtrap
MAIL_PASSWORD=password_dari_mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="hello@cosurent.com"
MAIL_FROM_NAME="${APP_NAME}"
```

5. **Penting:** Karena aplikasi ini menggunakan `ShouldQueue` (opsional jika aktif di `.env` `QUEUE_CONNECTION=database`), pastikan Anda menjalankan perintah ini di terminal baru agar email diproses:
   ```bash
   php artisan queue:work
   ```
   *(Catatan: Jika `.env` menggunakan `QUEUE_CONNECTION=sync`, perintah ini tidak diperlukan).*

### Skenario Testing (5 Fase Email):
1. **Fase Checkout (Order Placed & New Order Admin)**
   - **Aksi:** Login sebagai User, pilih kostum, isi form pengiriman, unggah bukti bayar, lalu klik "Checkout".
   - **Ekspektasi Mailtrap:** Akan masuk 2 email:
     1. Email ke User: "Menunggu Konfirmasi: Pesanan #ID" (Invoice awal untuk user).
     2. Email ke Admin: "PESANAN BARU MASUK: #ID" (Notifikasi untuk admin).

2. **Fase Konfirmasi (Order Confirmed)**
   - **Aksi:** Login sebagai Admin, buka menu **Pesanan Aktif**, lalu klik tombol **Konfirmasi** pada pesanan tadi.
   - **Ekspektasi Mailtrap:** Masuk email ke User: "Dikonfirmasi: Pesanan #ID sedang diproses".

3. **Fase Pengiriman (Order Shipped)**
   - **Aksi:** Masih sebagai Admin, klik tombol **Kirim**, masukkan Ekspedisi dan Nomor Resi, lalu simpan.
   - **Ekspektasi Mailtrap:** Masuk email ke User: "Paket Dikirim: Pesanan #ID" (Lengkap dengan nomor resi).

4. **Fase Pengembalian (Order Returned)**
   - **Aksi:** Login sebagai User, buka **Riwayat Pesanan**, klik **Kirim Kembali Pesanan**, lalu masukkan resi retur dan foto resi.
   - **Ekspektasi Mailtrap:** Masuk email ke Admin: "Barang Dikembalikan: Pesanan #ID" (Pemberitahuan bahwa user sudah retur paket).

5. **Fase Selesai & QC (Final Invoice)**
   - **Aksi:** Login sebagai Admin, masuk ke menu **Pengembalian & QC**, scan barcode/centang komponen yang kembali, lalu tekan **Selesaikan QC & Pesanan**.
   - **Ekspektasi Mailtrap:** Masuk email ke User: "Invoice Akhir: Pesanan #ID Selesai" (Berisi total denda keterlambatan/kerusakan jika ada, atau informasi pengembalian deposit).
