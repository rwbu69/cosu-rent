<x-mail::message>
# Yey! Pesanan Kamu Berhasil Dibuat 🎉

Halo Kak **{{ $booking->user->name }}**,

Terima kasih banyak sudah memilih CosuRent untuk kebutuhan cosplay-mu! Saat ini kami sedang mengecek pembayaran untuk pesananmu.

**Berikut adalah rincian pesananmu:**
- **Nomor Pesanan:** #{{ $booking->id }}
- **Kostum:** **{{ $booking->costume->name }}**
- **Tanggal Sewa:** {{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}
- **Total Pembayaran:** Rp {{ number_format($booking->total_price, 0, ',', '.') }}

Tunggu sebentar ya, Kak! Kami akan segera memberi kabar begitu pembayaranmu berhasil diverifikasi oleh tim kami.

<x-mail::button :url="route('orders.index')">
Lihat Pesananku
</x-mail::button>

Salam hangat,<br>
Tim CosuRent
</x-mail::message>