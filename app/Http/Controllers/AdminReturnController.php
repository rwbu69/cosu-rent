<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\CostumeComponent;
use App\Mail\FinalInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AdminReturnController extends Controller
{
    public function index(Request $request)
    {
        // Get active bookings that can be returned
        $bookings = Booking::with('user', 'costume.components')
            ->whereIn('status', ['Sedang Dirental', 'Dikirim Kembali'])
            ->get();

        $selectedBooking = null;
        if ($request->has('booking_id')) {
            $selectedBooking = Booking::with('user', 'costume.components')->find($request->booking_id);
            
            if ($selectedBooking) {
                // Calculate late fee suggestion
                $endDate = \Carbon\Carbon::parse($selectedBooking->end_date)->startOfDay();
                $today = \Carbon\Carbon::today();
                $lateDays = $today->gt($endDate) ? $today->diffInDays($endDate) : 0;
                $suggestedLateFee = $lateDays * $selectedBooking->costume->base_price;
                $selectedBooking->late_days = $lateDays;
                $selectedBooking->suggested_late_fee = $suggestedLateFee;
            }
        }

        return view('admin.return.index', compact('bookings', 'selectedBooking'));
    }

    public function complete(Request $request, $bookingId)
    {
        $request->validate([
            'missing_components' => 'nullable|array',
            'missing_components.*' => 'exists:costume_components,id',
            'penalty_fee' => 'required|numeric|min:0'
        ]);

        $booking = Booking::with('costume', 'user')->findOrFail($bookingId);
        
        // If there are missing components, we mark them.
        if ($request->missing_components) {
            CostumeComponent::whereIn('id', $request->missing_components)
                ->update(['status' => 'Hilang/Rusak']); // You can define proper status if needed
        }

        $booking->update([
            'status' => 'Returned',
            'penalty_fee' => $request->penalty_fee
        ]);

        $deposit = $booking->costume->deposit_price;
        $penalty = $request->penalty_fee;
        $refund = $deposit - $penalty;
        
        $bankInfo = $booking->user->bank_name ? "{$booking->user->bank_name} - {$booking->user->bank_account_number}" : "Rekening belum diisi";

        if ($refund >= 0) {
            $msg = 'Berhasil: Pengembalian selesai. Deposit Rp ' . number_format($deposit, 0, ',', '.') . ' dikembalikan, Denda Rp ' . number_format($penalty, 0, ',', '.') . '. Mohon transfer Refund sebesar Rp ' . number_format($refund, 0, ',', '.') . ' ke rekening: ' . $bankInfo;
        } else {
            $bill = abs($refund);
            $msg = 'Berhasil: Pengembalian selesai. Deposit Rp ' . number_format($deposit, 0, ',', '.') . ' hangus, Denda Rp ' . number_format($penalty, 0, ',', '.') . '. User harus membayar sisa tagihan sebesar Rp ' . number_format($bill, 0, ',', '.');
        }

        try {
            Mail::to($booking->user->email)->send(new FinalInvoice($booking));
        } catch (\Exception $e) {
            Log::error('Failed to send FinalInvoice email: ' . $e->getMessage());
        }

        return redirect()->route('admin.return.index')->with('success', $msg);
    }
}
