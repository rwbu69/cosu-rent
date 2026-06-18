<?php

namespace App\Http\Controllers;

use App\Models\UserAddress;
use Illuminate\Http\Request;

class UserAddressController extends Controller
{
    public function index(Request $request)
    {
        return redirect()->route('profile.edit')->withFragment('manajemen-alamat');
    }

    public function store(Request $request)
    {
        $request->validate([
            'address_line' => 'required|string|min:10|max:1000',
            'village_code' => 'required|string',
            'postal_code' => 'required|string|max:10',
        ], [
            'address_line.required' => 'Alamat tidak boleh kosong.',
            'address_line.min' => 'Alamat terlalu pendek, mohon berikan alamat lengkap.',
            'village_code.required' => 'Kode Desa wajib diisi untuk kalkulasi ongkir.',
            'postal_code.required' => 'Kode pos wajib diisi.',
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();
        $isFirst = $user->addresses()->count() === 0;

        $user->addresses()->create([
            'address_line' => $request->address_line,
            'village_code' => $request->village_code,
            'postal_code' => $request->postal_code,
            'is_primary' => $isFirst ? true : false,
        ]);

        return back()->with('success', 'Berhasil: Alamat baru telah ditambahkan!');
    }

    public function destroy(Request $request, int $id)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $address = $user->addresses()->findOrFail($id);
        
        if ($address->is_primary && $user->addresses()->count() > 1) {
            return back()->with('error', 'Gagal: Tidak dapat menghapus Alamat Utama. Jadikan alamat lain sebagai utama terlebih dahulu.');
        }

        $address->delete();

        return back()->with('success', 'Berhasil: Alamat telah dihapus.');
    }

    public function searchVillage(Request $request)
    {
        $query = $request->query('q');
        if (!$query) return response()->json(['results' => []]);

        $apiKey = env('RAJAONGKIR_API_KEY');
        if (!$apiKey) return response()->json(['error' => 'API Key not configured'], 500);

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'key' => $apiKey,
            ])->get('https://rajaongkir.komerce.id/api/v1/destination/domestic-destination', [
                'search' => $query
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['meta']['status']) && $data['meta']['status'] === 'success') {
                    $locations = $data['data'] ?? [];
                    $mapped = array_map(function($v) {
                        return [
                            'village_code' => (string) $v['id'],
                            'village' => $v['label'],
                            'district' => '',
                            'city' => '',
                            'province' => '',
                            'postal_code' => $v['zip_code'] ?? ''
                        ];
                    }, $locations);
                    return response()->json(['results' => $mapped]);
                }
            }

            return response()->json(['error' => 'Gagal mencari data'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function setPrimary(Request $request, int $id)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $address = $user->addresses()->findOrFail($id);

        $user->addresses()->update(['is_primary' => false]);
        $address->update(['is_primary' => true]);

        return back()->with('success', 'Berhasil: Alamat utama telah diubah!');
    }
}
