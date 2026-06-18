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
            // Logic for checkout. For this demo, let's just return the user profile and a success message.
            return response()->json([
                'message' => "Checkout initiated for {$user->name}.",
                'user' => $user
            ]);
        } elseif ($request->action === 'return') {
            // Logic for return. Return active bookings.
            $bookings = Booking::where('user_id', $user->id)->where('status', '!=', 'Returned')->get();
            return response()->json([
                'message' => "Return initiated for {$user->name}.",
                'user' => $user,
                'bookings' => $bookings
            ]);
        }
    }

    public function register(Request $request)
    {
        $request->validate([
            'rfid' => 'required|string|unique:users,rfid_uid',
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();
        $user->rfid_uid = $request->rfid;
        $user->save();

        return response()->json([
            'message' => 'RFID registered successfully to ' . $user->name,
            'user' => $user
        ]);
    }
}
