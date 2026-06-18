<?php

namespace App\Http\Controllers;

use App\Models\CostumeComponent;
use Illuminate\Http\Request;

class QcController extends Controller
{
    public function index()
    {
        return view('qc.index');
    }

    public function scan(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string',
        ]);

        $component = CostumeComponent::with('costume')->where('barcode_string', $request->barcode)->first();

        if (!$component) {
            return response()->json(['message' => 'Component not found. Please register it first.'], 404);
        }

        // Toggle status
        if ($component->status === 'In Warehouse') {
            $component->status = 'Under Rent';
        } else {
            $component->status = 'In Warehouse';
        }
        $component->save();

        // Check active booking for context
        $activeBooking = \App\Models\Booking::where('costume_id', $component->costume_id)
            ->whereIn('status', ['Diproses', 'Sedang Dikirim', 'Sedang Dirental', 'Dikirim Kembali'])
            ->first();

        $info = "";
        if ($activeBooking) {
            $info = " (Terkait pesanan aktif #{$activeBooking->id} - {$activeBooking->status})";
        }

        return response()->json([
            'message' => "Success: Status changed to {$component->status}{$info}",
            'component' => $component
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string|unique:costume_components,barcode_string',
            'name' => 'required|string',
            'costume_id' => 'required|integer', // Simplified for testing without existing costumes
        ]);

        // Create a dummy costume if it doesn't exist for the sake of the foreign key, or rely on user creating one.
        // Actually, let's just use firstOrCreate for testing purposes.
        $costume = \App\Models\Costume::firstOrCreate(
            ['id' => $request->costume_id],
            ['name' => 'Dummy Costume', 'series' => 'Dummy Series', 'size' => 'M', 'base_price' => 100.00]
        );

        $component = CostumeComponent::create([
            'barcode_string' => $request->barcode,
            'name' => $request->name,
            'costume_id' => $costume->id,
            'status' => 'In Warehouse'
        ]);

        return response()->json([
            'message' => 'Component registered successfully',
            'component' => $component
        ]);
    }
}
