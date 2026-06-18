<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Costume;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 0. Pesanan Masuk (Menunggu Konfirmasi / Diproses)
        $incomingBookings = Booking::with(['user', 'costume'])
            ->whereIn('status', ['Menunggu Konfirmasi', 'Diproses'])
            ->latest()
            ->get();

        // 1. Sedang Dirental
        $activeBookings = Booking::with(['user', 'costume.components'])
            ->where('status', 'Sedang Dirental')
            ->latest()
            ->get();

        // 2. Dikirim ke Customer
        $shippingOut = Booking::with(['user', 'costume'])
            ->where('status', 'Sedang Dikirim')
            ->latest()
            ->get();

        // 3. Dikirim Kembali
        $shippingReturn = Booking::with(['user', 'costume'])
            ->where('status', 'Dikirim Kembali')
            ->latest()
            ->get();

        // 4. Tersedia (Ready) - No overlapping active bookings today
        $today = Carbon::today()->toDateString();
        
        $availableCostumes = Costume::whereDoesntHave('bookings', function ($query) use ($today) {
            $query->where('status', '!=', 'Returned')
                  ->where('status', '!=', 'Cancelled')
                  ->where('start_date', '<=', $today)
                  ->where('end_date', '>=', $today);
        })->get();

        return view('admin.dashboard', compact(
            'incomingBookings',
            'activeBookings',
            'shippingOut',
            'shippingReturn',
            'availableCostumes'
        ));
    }
}
