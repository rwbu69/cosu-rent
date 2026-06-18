<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', Carbon::now()->format('Y-m'));

        $query = Booking::with(['user', 'costume'])
            ->where('status', 'Returned')
            ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$month]);

        $bookings = $query->get();

        $totalRevenue = 0;
        $totalPenalty = 0;
        $totalRentals = $bookings->count();

        foreach ($bookings as $booking) {
            // Revenue Sewa: (Total Price - Deposit) + Penalty
            // Note: In Phase 4, total_price is (Sewa + Deposit)
            $sewaMurni = $booking->total_price - $booking->costume->deposit_price;
            $revenue = $sewaMurni + $booking->penalty_fee;
            
            $booking->revenue = $revenue;
            $booking->sewa_murni = $sewaMurni;
            
            $totalRevenue += $revenue;
            $totalPenalty += $booking->penalty_fee;
        }

        return view('admin.report.index', compact('bookings', 'totalRevenue', 'totalPenalty', 'totalRentals', 'month'));
    }

    public function export(Request $request)
    {
        $month = $request->input('month', Carbon::now()->format('Y-m'));

        $bookings = Booking::with(['user', 'costume'])
            ->where('status', 'Returned')
            ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$month])
            ->get();

        $filename = "laporan_keuangan_{$month}.csv";
        $handle = fopen('php://output', 'w');

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // CSV Header
        fputcsv($handle, [
            'ID Pesanan',
            'Tanggal Booking',
            'Pelanggan',
            'Kostum',
            'Masa Sewa',
            'Pendapatan Sewa (Rp)',
            'Denda Keterlambatan/Kerusakan (Rp)',
            'Total Pendapatan Bersih (Rp)'
        ]);

        $sumSewa = 0;
        $sumDenda = 0;
        $sumTotal = 0;

        foreach ($bookings as $booking) {
            $sewaMurni = $booking->total_price - $booking->costume->deposit_price;
            $revenue = $sewaMurni + $booking->penalty_fee;

            fputcsv($handle, [
                '#' . $booking->id,
                $booking->created_at->format('Y-m-d H:i'),
                $booking->user->name,
                $booking->costume->name,
                $booking->start_date . ' s/d ' . $booking->end_date,
                $sewaMurni,
                $booking->penalty_fee,
                $revenue
            ]);

            $sumSewa += $sewaMurni;
            $sumDenda += $booking->penalty_fee;
            $sumTotal += $revenue;
        }

        // Summary Row
        fputcsv($handle, ['', '', '', '', 'TOTAL:', $sumSewa, $sumDenda, $sumTotal]);

        fclose($handle);
        exit;
    }
}
