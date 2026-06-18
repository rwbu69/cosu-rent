<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Mail\BookingConfirmed;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'costume'])
            ->whereNotIn('status', ['Returned', 'Cancelled'])
            ->latest()
            ->paginate(15);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function history()
    {
        $bookings = Booking::with(['user', 'costume'])
            ->whereIn('status', ['Returned', 'Cancelled'])
            ->latest()
            ->paginate(15);

        return view('admin.bookings.history', compact('bookings'));
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

    public function updateFee(Request $request, Booking $booking)
    {
        if ($booking->status !== 'Menunggu Hitung Ongkir') {
            return back()->with('error', 'Gagal: Pesanan ini tidak sedang menunggu hitung ongkir.');
        }

        $request->validate([
            'shipping_fee' => 'required|numeric|min:0',
        ]);

        $booking->update([
            'shipping_fee' => $request->shipping_fee,
            'is_shipping_manual' => true,
            'status' => 'Menunggu Pembayaran',
            'total_price' => $booking->total_price + $request->shipping_fee,
        ]);

        return back()->with('success', 'Berhasil: Ongkir telah ditambahkan, status berubah menjadi Menunggu Pembayaran.');
    }

    public function ship(Request $request, Booking $booking)
    {
        if ($booking->status !== 'Diproses') {
            return back()->with('error', 'Gagal: Pesanan ini belum diproses.');
        }

        $request->validate([
            'shipping_courier' => 'required|string|max:255',
            'shipping_receipt' => 'required|string|max:255',
            'shipping_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = $request->file('shipping_image')->store('shipping_proofs', 'public');

        $booking->update([
            'status' => 'Sedang Dikirim',
            'shipping_courier' => $request->shipping_courier,
            'shipping_receipt' => $request->shipping_receipt,
            'shipping_image_path' => $imagePath,
        ]);

        return back()->with('success', 'Berhasil: Pesanan dilabeli Sedang Dikirim dan data pengiriman disimpan.');
    }
}
