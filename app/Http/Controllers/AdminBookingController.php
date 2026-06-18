<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Mail\BookingConfirmed;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AdminBookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'costume'])
            ->latest()
            ->paginate(15);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function confirm(Booking $booking)
    {
        if ($booking->status !== 'Menunggu Konfirmasi') {
            return back()->with('error', 'Gagal: Pesanan ini sudah dikonfirmasi atau dibatalkan sebelumnya.');
        }

        $booking->update([
            'status' => 'Diproses'
        ]);

        // Send email notification
        try {
            Mail::to($booking->user->email)->send(new BookingConfirmed($booking));
        } catch (\Exception $e) {
            // Log error but don't fail the request since booking is confirmed
            Log::error('Failed to send BookingConfirmed email: ' . $e->getMessage());
        }

        return back()->with('success', 'Berhasil: Pesanan telah dikonfirmasi dan email pemberitahuan telah dikirim ke pelanggan.');
    }

    public function ship(Booking $booking)
    {
        if ($booking->status !== 'Diproses') {
            return back()->with('error', 'Gagal: Pesanan ini belum diproses.');
        }

        $booking->update([
            'status' => 'Sedang Dikirim'
        ]);

        return back()->with('success', 'Berhasil: Pesanan dilabeli Sedang Dikirim.');
    }
}
