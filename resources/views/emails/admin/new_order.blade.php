<x-mail::message>
# Ada Pesanan Baru Nih! 🚀

Halo Admin,

Sistem mencatat ada pesanan masuk baru yang sudah dibayar. Yuk, segera dicek kebenarannya!

**Rincian Pesanan:**
- **Nomor Pesanan:** #{{ $booking->id }}
- **Pelanggan:** {{ $booking->user->name }} (@{{ $booking->user->username }})
- **Kostum:** **{{ $booking->costume->name }}**
- **Total:** Rp {{ number_format($booking->total_price, 0, ',', '.') }}

<x-mail::button :url="route('admin.bookings.index')">
Buka Dashboard Admin
</x-mail::button>

Semangat kerjanya!<br>
Sistem CosuRent
</x-mail::message>