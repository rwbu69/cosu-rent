<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Cart;
use App\Mail\OrderPlaced;
use App\Mail\NewOrderAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
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

    public function getShippingOptions(Request $request, \App\Services\ShippingService $shippingService)
    {
        $addressId = $request->query('address_id');
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $address = $user->addresses()->find($addressId);

        if (!$address || !$address->village_code) {
            return response()->json(['error' => 'Alamat tidak valid atau belum memiliki Kode Desa'], 400);
        }

        $cart = Cart::with('items.costume')->where('user_id', $user->id)->first();
        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['error' => 'Keranjang kosong'], 400);
        }

        $totalWeight = $cart->items->sum(function($item) {
            return $item->costume->weight ?? 1;
        });

        $options = $shippingService->getShippingCosts($address->village_code, $totalWeight);

        if ($options === null) {
            return response()->json(['error' => 'API Error'], 500);
        }

        return response()->json(['options' => $options]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:user_addresses,id',
            'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'shipping_courier' => 'nullable|string',
            'shipping_fee' => 'nullable|numeric',
            'terms' => 'required|accepted'
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $cart = Cart::with('items.costume')->where('user_id', $user->id)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('catalog.index')->with('error', 'Keranjang Anda kosong.');
        }

        $address = $user->addresses()->findOrFail($request->address_id);

        $paymentPath = null;
        if ($request->hasFile('payment_proof')) {
            $paymentPath = $request->file('payment_proof')->store('payment_proofs', 'public');
        }

        $shippingCourier = $request->shipping_courier ?? null;
        $shippingFee = $request->shipping_fee ?? 0;
        
        // If shipping fails, it goes to "Menunggu Hitung Ongkir". If they uploaded payment but shipping failed, it's a bit complex, but let's just accept it.
        $status = 'Menunggu Konfirmasi';
        if ($shippingFee == 0 && !$shippingCourier) {
            $status = 'Menunggu Hitung Ongkir';
        } elseif (!$paymentPath) {
            $status = 'Menunggu Pembayaran';
        }

        $orderGroupId = \Illuminate\Support\Str::uuid()->toString();

        $mailDelaySeconds = 0;
        foreach ($cart->items as $item) {
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
                return back()->with('error', 'Gagal: Kostum ' . $item->costume->name . ' sudah dipesan pada tanggal tersebut.');
            }

            $days = $item->start_date->diffInDays($item->end_date) + 1;
            $itemPrice = ceil($days / 3) * $item->costume->base_price + $item->costume->deposit_price;

            $booking = Booking::create([
                'user_id' => $user->id,
                'costume_id' => $item->costume_id,
                'start_date' => $item->start_date,
                'end_date' => $item->end_date,
                'status' => $status,
                'total_price' => $itemPrice,
                'payment_proof' => $paymentPath,
                'shipping_address' => $address->address_line,
                'shipping_courier' => $shippingCourier,
                'shipping_fee' => $shippingFee,
                'order_group_id' => $orderGroupId,
            ]);

            if ($status === 'Menunggu Konfirmasi') {
                Mail::to($user->email)->later(now()->addSeconds($mailDelaySeconds), new OrderPlaced($booking));
                $mailDelaySeconds += 3; // Delay 3 seconds to avoid Mailtrap 550 Rate Limit
                Mail::to('admin@cosurent.com')->later(now()->addSeconds($mailDelaySeconds), new NewOrderAdmin($booking));
                $mailDelaySeconds += 3;
            }
        }

        $cart->items()->delete();

        if ($status === 'Menunggu Hitung Ongkir') {
            return redirect()->route('orders.index')->with('success', 'Berhasil: Pesanan telah dibuat. Sistem tidak dapat menghitung ongkir secara otomatis, admin akan menghitung ongkir secara manual.');
        } elseif ($status === 'Menunggu Pembayaran') {
            return redirect()->route('orders.index')->with('success', 'Berhasil: Pesanan telah dibuat. Silakan selesaikan pembayaran.');
        }
        return redirect()->route('orders.index')->with('success', 'Berhasil: Pesanan telah dibuat. Menunggu konfirmasi admin.');
    }
}
