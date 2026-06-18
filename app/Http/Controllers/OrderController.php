<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Mail\OrderReturned;

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
            'return_shipping_courier' => 'required|string|max:255',
            'return_shipping_receipt' => 'required|string|max:255',
            'return_shipping_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $booking = \App\Models\Booking::findOrFail($id);
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->status !== 'Sedang Dirental') {
            return back()->with('error', 'Status pesanan tidak valid untuk dikembalikan.');
        }

        $imagePath = $request->file('return_shipping_image')->store('return_shipping_proofs', 'public');

        $booking->update([
            'status' => 'Dikirim Kembali',
            'return_shipping_courier' => $request->return_shipping_courier,
            'return_shipping_receipt' => $request->return_shipping_receipt,
            'return_shipping_image_path' => $imagePath,
        ]);

        try {
            Mail::to('admin@cosurent.com')->send(new OrderReturned($booking));
        } catch (\Exception $e) {
            Log::error('Failed to send OrderReturned email: ' . $e->getMessage());
        }

        return back()->with('success', 'Berhasil: Informasi resi pengiriman kembali telah disimpan. Terima kasih!');
    }
}
