<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $bookings = $user->bookings()->with(['costume.components'])->latest()->get();
        return view('orders.index', compact('bookings'));
    }

    public function received(int $id)
    {
        $booking = \App\Models\Booking::findOrFail($id);
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->status !== 'Sedang Dikirim') {
            return back()->with('error', 'Pesanan belum dikirim atau status tidak valid.');
        }

        $booking->update([
            'status' => 'Sedang Dirental'
        ]);

        return back()->with('success', 'Berhasil: Pesanan telah diterima. Selamat merental!');
    }

    public function returnShipping(\Illuminate\Http\Request $request, int $id)
    {
        $request->validate([
            'return_shipping_receipt' => 'required|string|max:255',
        ]);

        $booking = \App\Models\Booking::findOrFail($id);
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->status !== 'Sedang Dirental') {
            return back()->with('error', 'Status pesanan tidak valid untuk dikembalikan.');
        }

        $booking->update([
            'status' => 'Dikirim Kembali',
            'return_shipping_receipt' => $request->return_shipping_receipt,
        ]);

        return back()->with('success', 'Berhasil: Informasi resi pengiriman kembali telah disimpan. Terima kasih!');
    }
}
