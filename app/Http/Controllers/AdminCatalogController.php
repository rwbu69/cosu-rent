<?php

namespace App\Http\Controllers;

use App\Models\Costume;
use App\Models\CostumeComponent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminCatalogController extends Controller
{
    public function index()
    {
        $costumes = Costume::withCount('components')->latest()->paginate(10);
        return view('admin.katalog.index', compact('costumes'));
    }

    public function create()
    {
        return view('admin.katalog.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:costumes,name',
            'series' => 'required|string|max:255',
            'size' => 'required|string|max:50',
            'base_price' => 'required|numeric|min:0',
            'deposit_price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'components' => 'required|array|min:1',
            'components.*.name' => 'required|string|max:255',
            'components.*.image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'components.*.barcode' => 'nullable|string|max:255|unique:costume_components,barcode_string',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->only('name', 'series', 'size', 'base_price', 'deposit_price', 'description');
            $data['is_available'] = $request->has('is_available');
            
            if ($request->hasFile('image')) {
                $data['image_path'] = $request->file('image')->store('costumes', 'public');
            }

            $costume = Costume::create($data);

            foreach ($request->components as $compData) {
                $compImagePath = null;
                if (isset($compData['image']) && $compData['image'] instanceof \Illuminate\Http\UploadedFile) {
                    $compImagePath = $compData['image']->store('components', 'public');
                }

                $costume->components()->create([
                    'name' => $compData['name'],
                    'image_path' => $compImagePath,
                    'barcode_string' => $compData['barcode'] ?? strtoupper(Str::random(10)),
                ]);
            }

            DB::commit();
            return redirect()->route('admin.katalog.index')->with('success', 'Berhasil: Kostum baru telah ditambahkan ke katalog.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: Terjadi kesalahan saat menyimpan kostum.')->withInput();
        }
    }

    public function edit(Costume $katalog)
    {
        $katalog->load('components');
        return view('admin.katalog.edit', compact('katalog'));
    }

    public function update(Request $request, Costume $katalog)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:costumes,name,' . $katalog->id,
            'series' => 'required|string|max:255',
            'size' => 'required|string|max:50',
            'base_price' => 'required|numeric|min:0',
            'deposit_price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'components' => 'required|array|min:1',
            'components.*.id' => 'nullable|exists:costume_components,id',
            'components.*.name' => 'required|string|max:255',
            'components.*.image' => 'required_without:components.*.id|image|mimes:jpeg,png,jpg|max:2048',
            'components.*.barcode' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->only('name', 'series', 'size', 'base_price', 'deposit_price', 'description');
            $data['is_available'] = $request->has('is_available');
            
            if ($request->hasFile('image')) {
                $data['image_path'] = $request->file('image')->store('costumes', 'public');
            }

            $katalog->update($data);

            $existingIds = [];
            foreach ($request->components as $compData) {
                $compImagePath = null;
                if (isset($compData['image']) && $compData['image'] instanceof \Illuminate\Http\UploadedFile) {
                    $compImagePath = $compData['image']->store('components', 'public');
                }

                if (!empty($compData['id'])) {
                    // Update existing
                    $comp = CostumeComponent::find($compData['id']);
                    if ($comp && $comp->costume_id === $katalog->id) {
                        $updateData = [
                            'name' => $compData['name'],
                            'barcode_string' => $compData['barcode'],
                        ];
                        if ($compImagePath) {
                            $updateData['image_path'] = $compImagePath;
                        }
                        $comp->update($updateData);
                        $existingIds[] = $comp->id;
                    }
                } else {
                    // Create new
                    $newComp = $katalog->components()->create([
                        'name' => $compData['name'],
                        'image_path' => $compImagePath,
                        'barcode_string' => $compData['barcode'] ?? strtoupper(Str::random(10)),
                    ]);
                    $existingIds[] = $newComp->id;
                }
            }

            // Delete components not in the submitted list
            $katalog->components()->whereNotIn('id', $existingIds)->delete();

            DB::commit();
            return redirect()->route('admin.katalog.index')->with('success', 'Berhasil: Kostum telah diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: Terjadi kesalahan saat memperbarui kostum.')->withInput();
        }
    }

    public function destroy(Costume $katalog)
    {
        $katalog->delete();
        return redirect()->route('admin.katalog.index')->with('success', 'Berhasil: Kostum telah dihapus.');
    }
}
