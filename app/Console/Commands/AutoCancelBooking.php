<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Carbon\Carbon;

class AutoCancelBooking extends Command
{
    protected $signature = 'booking:auto-cancel';
    protected $description = 'Batalkan pesanan yang belum dibayar selama lebih dari 24 jam';

    public function handle()
    {
        $expiredBookings = Booking::where('status', 'Menunggu Pembayaran')
            ->where('created_at', '<', Carbon::now()->subHours(24))
            ->get();

        $count = 0;
        foreach ($expiredBookings as $booking) {
            $booking->update(['status' => 'Dibatalkan']);
            $count++;
            
            // Optionally we can send an email here saying their booking was auto-cancelled.
        }

        $this->info("Berhasil membatalkan {$count} pesanan yang kadaluarsa.");
    }
}
