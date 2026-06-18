<div>
    <h2>Pengingat Pengembalian Kostum</h2>
    <p>Halo {{ $booking->user->name }},</p>
    <p>Ini adalah pengingat bahwa batas waktu penyewaan untuk kostum <strong>{{ $booking->costume->name }}</strong> (ID: {{ $booking->costume_id }}) adalah besok, tanggal <strong>{{ $booking->end_date->format('Y-m-d') }}</strong>.</p>
    <p>Mohon segera kembalikan item secara lengkap tepat waktu untuk menghindari denda keterlambatan.</p>
    <p>Terima kasih!</p>
</div>
