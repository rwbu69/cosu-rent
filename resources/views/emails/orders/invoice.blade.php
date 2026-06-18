<x-mail::message>
# Selesai! Terima Kasih Sudah Menyewa 💖

Halo Kak **{{ $booking->user->name }}**,

Tim kami telah menerima kembali kostum **{{ $booking->costume->name }}** (Pesanan **#{{ $booking->id }}**) dan pengecekan kualitas (*QC*) sudah selesai dilakukan. Dengan ini, pesanan Kakak resmi dinyatakan **Selesai**.

**Rincian Akhir Pesanan:**
- **Lama Keterlambatan:** {{ $booking->late_days }} hari
- **Denda (Telat/Rusak):** Rp {{ number_format($booking->penalty_fee, 0, ',', '.') }}
- **Total Pembayaran Awal:** Rp {{ number_format($booking->total_price, 0, ',', '.') }}

*(Catatan: Jika terdapat biaya denda, tim admin kami akan segera/telah menghubungi Kakak. Jika denda Rp 0, Kakak bebas dari tanggungan apapun!)*

<x-mail::button :url="route('orders.index')">
Lihat Riwayat Pesananku
</x-mail::button>

Terima kasih banyak telah mempercayakan CosuRent. Kami tunggu pesanan Kakak yang selanjutnya! ✨

Salam hangat,<br>
Tim CosuRent
</x-mail::message>