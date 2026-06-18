<x-mail::message>
# Kostum Sedang Dikirim Kembali 📦

Halo Admin,

Pelanggan **{{ $booking->user->name }}** baru saja mengonfirmasi bahwa mereka telah mengirimkan kembali kostum yang disewa.

**Rincian Retur:**
- **Nomor Pesanan:** #{{ $booking->id }}
- **Kostum:** **{{ $booking->costume->name }}**
- **Ekspedisi Retur:** {{ strtoupper(str_replace('_', ' - ', $booking->return_shipping_courier ?? '-')) }}
- **Resi Retur:** **{{ $booking->return_shipping_receipt }}**

Mohon pantau kedatangan paket ini. Jika sudah sampai di gudang, jangan lupa untuk segera melakukan *Quality Control (QC)* ya!

<x-mail::button :url="route('admin.return.index')">
Menu Pengembalian & QC
</x-mail::button>

Semangat!<br>
Sistem CosuRent
</x-mail::message>