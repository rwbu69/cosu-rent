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

CosuRent menggunakan *driver* SMTP untuk mengirimkan email seperti bukti *booking*, konfirmasi pesanan, dan pengingat pengembalian. Mailtrap digunakan sebagai *server SMTP dummy* agar email tidak benar-benar terkirim ke alamat email asli pelanggan selama masa pengembangan/testing.

### Persiapan (Setup):
1. Buat akun gratis di [Mailtrap.io](https://mailtrap.io).
2. Di *dashboard* Mailtrap, buat inbox baru (misal: "CosuRent Testing").
3. Buka pengaturan Inbox tersebut dan pilih *Integration: Laravel*.
4. Buka file `.env` di folder *root* proyek `uas_pwl` ini.
5. Sesuaikan variabel `MAIL_*` dengan kredensial dari Mailtrap:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=username_dari_mailtrap
MAIL_PASSWORD=password_dari_mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@cosurent.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Skenario Testing:
1. **Restart Server**: Jika `php artisan serve` atau *queue worker* sedang berjalan, matikan (Ctrl+C) dan nyalakan kembali agar file `.env` yang baru terbaca.
2. Lakukan aksi yang memicu email di website, contoh:
   - Mendaftarkan akun baru (jika fitur verifikasi email aktif).
   - Checkout pesanan baru (pelanggan akan menerima invoice).
   - Admin menyetujui pesanan (pelanggan menerima konfirmasi).
3. **Ekspektasi Hasil**: Buka *dashboard* Mailtrap Anda. Dalam hitungan detik, email yang dikirim oleh sistem CosuRent akan muncul di kotak masuk (*inbox*) Mailtrap. Anda bisa melihat preview visual email, mengecek tampilan HTML-nya, dan memastikan data dinamis (seperti Nomor Pesanan dan Harga) tercetak dengan benar tanpa melakukan *spam* ke email sungguhan.
