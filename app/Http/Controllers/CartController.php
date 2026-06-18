<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'costume_id' => 'required|exists:costumes,id',
            'dates' => 'required|string',
        ]);

        $parts = explode(' to ', $request->dates);
        $startDate = $parts[0] ?? null;
        $endDate = $parts[1] ?? $startDate;

        if (!$startDate || !$endDate) {
            return back()->with('error', 'Tanggal sewa tidak valid.');
        }

        $isBooked = \App\Models\Booking::where('costume_id', $request->costume_id)
            ->where('status', '!=', 'Returned')
            ->where('status', '!=', 'Cancelled')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(function ($q) use ($startDate, $endDate) {
                          $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                      });
            })
            ->exists();

        if ($isBooked) {
            return back()->with('error', 'Gagal: Kostum sudah dipesan pada tanggal tersebut. Silakan pilih tanggal lain.');
        }

        $user = Auth::user();
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        // Prevent duplicate costume in cart for simplicity (or just update dates)
        $existing = $cart->items()->where('costume_id', $request->costume_id)->first();
        if ($existing) {
            $existing->update([
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);
        } else {
            $cart->items()->create([
                'costume_id' => $request->costume_id,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);
        }

        if ($request->action === 'checkout') {
            return redirect()->route('checkout.index');
        }

        return back()->with('success', 'Kostum berhasil ditambahkan ke keranjang.');
    }

    public function destroy(CartItem $cartItem)
    {
        if ($cartItem->cart->user_id !== Auth::id()) {
            abort(403);
        }
        $cartItem->delete();
        return back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }
}
