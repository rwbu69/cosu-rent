<div>
    <h2>Booking Berhasil!</h2>
    <p>Halo {{ $booking->user->name }},</p>
    <p>Booking Anda untuk kostum <strong>{{ $booking->costume->name }}</strong> (ID: {{ $booking->costume_id }}) telah berhasil dikonfirmasi.</p>
    <p>Berikut rincian pesanan Anda:</p>
    <ul>
        <li><strong>Tanggal Mulai:</strong> {{ $booking->start_date->format('Y-m-d') }}</li>
        <li><strong>Tanggal Kembali:</strong> {{ $booking->end_date->format('Y-m-d') }}</li>
        <li><strong>Total Harga:</strong> Rp {{ number_format($booking->total_price, 0, ',', '.') }}</li>
    </ul>
    <p>Silakan ambil pesanan Anda pada tanggal yang ditentukan menggunakan fitur RFID Kiosk kami.</p>
    <p>Terima kasih telah menyewa di CosRent!</p>
</div>
