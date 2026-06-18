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
        ], [
            'address_line.required' => 'Alamat tidak boleh kosong.',
            'address_line.min' => 'Alamat terlalu pendek, mohon berikan alamat lengkap.',
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();
        $isFirst = $user->addresses()->count() === 0;

        $user->addresses()->create([
            'address_line' => $request->address_line,
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
