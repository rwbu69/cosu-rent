<?php

namespace App\Http\Controllers;

use App\Models\Costume;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Costume::query()->where('is_available', true);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('series', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('size')) {
            $query->where('size', $request->size);
        }

        $costumes = $query->paginate(12);

        return view('catalog.index', compact('costumes'));
    }

    public function show($id)
    {
        $costume = Costume::with(['components', 'bookings' => function($q) {
            $q->where('status', '!=', 'Returned')->where('status', '!=', 'Cancelled');
        }])->findOrFail($id);

        return view('catalog.show', compact('costume'));
    }
}
