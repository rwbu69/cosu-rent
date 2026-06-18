<x-mail::message>
# Paketmu Sedang Dalam Perjalanan! 🚚💨

Halo Kak **{{ $booking->user->name }}**,

Kostum **{{ $booking->costume->name }}** yang Kakak sewa (Pesanan **#{{ $booking->id }}**) sudah meluncur dan diserahkan ke pihak ekspedisi!

**Detail Pengiriman:**
- **Ekspedisi:** {{ strtoupper(str_replace('_', ' - ', $booking->shipping_courier ?? 'Ekspedisi')) }}
- **Nomor Resi:** **{{ $booking->shipping_receipt }}**

Kakak bisa melacak posisi paketnya langsung menggunakan nomor resi di atas pada website atau aplikasi ekspedisi terkait ya.

<x-mail::button :url="route('orders.index')">
Cek Detail Pesanan
</x-mail::button>

*Psst! Jangan lupa untuk menyimpan resi pengembalian saat masa sewa Kakak habis nanti ya.*

Selamat cosplay dan have fun!<br>
Tim CosuRent
</x-mail::message>