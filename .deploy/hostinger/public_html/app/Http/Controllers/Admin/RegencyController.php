<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Models\Regency;
use Illuminate\Http\Request;

class RegencyController extends Controller
{
    public function index(Request $request)
    {
        $query = Regency::with('province');
        if ($request->filled('province_id')) {
            $query->where('province_id', $request->province_id);
        }
        $regencies = $query->orderBy('name')->paginate(20);
        $provinces = Province::orderBy('name')->get();

        return view('admin.regencies.index', compact('regencies', 'provinces'));
    }

    public function edit(Regency $regency)
    {
        return view('admin.regencies.edit', compact('regency'));
    }

    public function update(Request $request, Regency $regency)
    {
        $validated = $request->validate([
            'category' => 'nullable|string|max:100',
            'image' => 'nullable|string', // URL for now
        ]);

        $regency->update($validated);

        return redirect()->route('admin.regencies.index')
            ->with('success', 'Kategori Kabupaten berhasil diperbarui!');
    }
}
