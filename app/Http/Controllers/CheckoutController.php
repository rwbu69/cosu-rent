<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $cart = Cart::with('items.costume')->firstOrCreate(['user_id' => $user->id]);
        $addresses = $user->addresses()->orderByDesc('is_primary')->latest()->get();

        if ($cart->items->isEmpty()) {
            return redirect()->route('catalog.index')->with('error', 'Keranjang Anda kosong.');
        }

        $totalDeposit = 0;
        $totalSewa = 0;
        foreach ($cart->items as $item) {
            $days = $item->start_date->diffInDays($item->end_date) + 1;
            $price = ceil($days / 3) * $item->costume->base_price;
            $totalSewa += $price;
            $totalDeposit += $item->costume->deposit_price;
            $item->days = $days;
            $item->subtotal = $price;
        }
        $totalPrice = $totalSewa + $totalDeposit;

        return view('checkout.index', compact('cart', 'totalSewa', 'totalDeposit', 'totalPrice', 'addresses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:user_addresses,id',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'terms' => 'required|accepted'
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $cart = Cart::with('items.costume')->where('user_id', $user->id)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('catalog.index')->with('error', 'Keranjang Anda kosong.');
        }

        $address = $user->addresses()->findOrFail($request->address_id);

        // Upload payment proof once
        $paymentPath = null;
        if ($request->hasFile('payment_proof')) {
            $paymentPath = $request->file('payment_proof')->store('payment_proofs', 'public');
        }

        // Generate a random order group ID
        $orderGroupId = \Illuminate\Support\Str::uuid()->toString();

        foreach ($cart->items as $item) {
            // Check availability per item
            $isBooked = Booking::where('costume_id', $item->costume_id)
                ->where('status', '!=', 'Returned')
                ->where('status', '!=', 'Cancelled')
                ->where(function ($query) use ($item) {
                    $query->whereBetween('start_date', [$item->start_date, $item->end_date])
                          ->orWhereBetween('end_date', [$item->start_date, $item->end_date])
                          ->orWhere(function ($q) use ($item) {
                              $q->where('start_date', '<=', $item->start_date)
                                ->where('end_date', '>=', $item->end_date);
                          });
                })
                ->exists();

            if ($isBooked) {
                return back()->with('error', 'Gagal: Kostum ' . $item->costume->name . ' sudah dipesan pada tanggal tersebut. Silakan hapus dari keranjang atau ganti tanggal.');
            }

            $days = $item->start_date->diffInDays($item->end_date) + 1;
            $itemPrice = ceil($days / 3) * $item->costume->base_price + $item->costume->deposit_price;

            Booking::create([
                'user_id' => $user->id,
                'costume_id' => $item->costume_id,
                'start_date' => $item->start_date,
                'end_date' => $item->end_date,
                'status' => 'Menunggu Konfirmasi',
                'total_price' => $itemPrice,
                'payment_proof' => $paymentPath,
                'shipping_address' => $address->address_line,
                'order_group_id' => $orderGroupId, // Need to add to DB!
            ]);
        }

        // Clear cart
        $cart->items()->delete();

        return redirect()->route('orders.index')->with('success', 'Berhasil: Pesanan telah dibuat. Menunggu konfirmasi admin.');
    }
}
