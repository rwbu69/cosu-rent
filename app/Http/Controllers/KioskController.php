<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\Request;

class KioskController extends Controller
{
    public function index()
    {
        return view('kiosk.index');
    }

    public function scan(Request $request)
    {
        $request->validate([
            'rfid' => 'required|string',
            'action' => 'required|in:checkout,return',
        ]);

        $user = User::where('rfid_uid', $request->rfid)->first();

        if (!$user) {
            return response()->json(['message' => 'RFID not registered.'], 404);
        }

        if ($request->action === 'checkout') {
            $bookings = Booking::where('user_id', $user->id)
                ->where('status', 'Diproses')
                ->get();
                
            if ($bookings->isEmpty()) {
                return response()->json(['message' => 'Tidak ada pesanan yang siap di-checkout (status: Diproses).'], 400);
            }

            foreach ($bookings as $booking) {
                $booking->status = 'Sedang Dirental';
                $booking->save();
            }

            return response()->json([
                'message' => "Checkout berhasil untuk {$bookings->count()} pesanan.",
                'user' => $user,
                'bookings' => $bookings
            ]);
        } elseif ($request->action === 'return') {
            $bookings = Booking::where('user_id', $user->id)
                ->where('status', 'Sedang Dirental')
                ->get();

            if ($bookings->isEmpty()) {
                return response()->json(['message' => 'Tidak ada kostum yang sedang dirental.'], 400);
            }

            $successCount = 0;
            $failedMessages = [];

            foreach ($bookings as $booking) {
                $unreturnedComponents = \App\Models\CostumeComponent::where('costume_id', $booking->costume_id)
                    ->where('status', 'Under Rent')
                    ->count();

                if ($unreturnedComponents > 0) {
                    $failedMessages[] = "Pesanan #{$booking->id}: {$unreturnedComponents} komponen belum di-QC.";
                } else {
                    $booking->status = 'Returned';
                    $booking->save();
                    $successCount++;
                }
            }

            $updatedBookings = Booking::where('user_id', $user->id)
                ->whereIn('status', ['Sedang Dirental', 'Returned'])
                ->get();

            if (count($failedMessages) > 0) {
                return response()->json([
                    'message' => implode(' ', $failedMessages) . ($successCount > 0 ? " ({$successCount} berhasil)." : ""),
                    'user' => $user,
                    'bookings' => $updatedBookings
                ], 400);
            }

            return response()->json([
                'message' => "Pengembalian berhasil untuk {$successCount} pesanan. Terima kasih!",
                'user' => $user,
                'bookings' => $updatedBookings
            ]);
        }
    }

    public function register(Request $request)
    {
        $request->validate([
            'rfid' => 'required|string|unique:users,rfid_uid',
            'username' => 'required|string|exists:users,username',
        ]);

        $user = User::where('username', $request->username)->first();
        $user->rfid_uid = $request->rfid;
        $user->save();

        return response()->json([
            'message' => 'RFID registered successfully to ' . $user->name,
            'user' => $user
        ]);
    }
}
