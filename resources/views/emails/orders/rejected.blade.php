<x-mail::message>
# Oops! Pembayaran Perlu Diperbaiki ⚠️

Halo Kak **{{ $booking->user->name }}**,

Mohon maaf, bukti pembayaran yang Kakak unggah untuk pesanan **#{{ $booking->id }}** (Kostum: **{{ $booking->costume->name }}**) **tidak dapat kami verifikasi/ditolak**.

Alasan penolakan: (Mungkin bukti transfer buram, nominal tidak sesuai, atau palsu).

Agar pesanan Kakak dapat segera diproses, yuk unggah ulang bukti pembayaran yang baru dan jelas melalui halaman Riwayat Pesanan.

<x-mail::button :url="route('orders.index')">
Unggah Ulang Bukti Bayar
</x-mail::button>

Jika ada kendala, jangan ragu untuk membalas email ini atau hubungi CS kami ya!

Salam hangat,<br>
Tim CosuRent
</x-mail::message>
