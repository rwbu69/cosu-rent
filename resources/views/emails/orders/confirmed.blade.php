<x-mail::message>
# Pembayaran Dikonfirmasi! Kostummu Segera Disiapkan ✨

Halo Kak **{{ $booking->user->name }}**,

Kabar gembira! Pembayaran untuk pesanan **#{{ $booking->id }}** sudah berhasil kami verifikasi. 
Saat ini, tim kami sedang menyiapkan dan mengemas kostum **{{ $booking->costume->name }}** kesayanganmu dengan aman.

**Info Pengiriman:**
Ekspedisi Pilihan: {{ strtoupper(str_replace('_', ' - ', $booking->shipping_courier ?? 'Ekspedisi')) }}

Tenang saja, kami akan mengabari Kakak lagi lengkap dengan nomor resinya begitu paket diserahkan ke abang kurir.

<x-mail::button :url="route('orders.index')">
Pantau Status Pesanan
</x-mail::button>

Salam hangat,<br>
Tim CosuRent
</x-mail::message>