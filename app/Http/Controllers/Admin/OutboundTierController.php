<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PackageTier;
use Illuminate\Http\Request;

class OutboundTierController extends Controller
{
    public function index()
    {
        $tiers = PackageTier::orderBy('id')->get();
        return view('admin.outbound.tiers', compact('tiers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tierName' => 'required|string|max:100',
            'tagline'  => 'nullable|string',
        ]);

        PackageTier::create($validated);

        return back()->with('success', 'Tier berhasil ditambahkan!');
    }

    public function update(Request $request, PackageTier $tier)
    {
        $validated = $request->validate([
            'tierName' => 'required|string|max:100',
            'tagline'  => 'nullable|string',
        ]);

        $tier->update($validated);

        return back()->with('success', 'Tier berhasil diperbarui!');
    }

    public function destroy(PackageTier $tier)
    {
        $tier->delete();
        return back()->with('success', 'Tier berhasil dihapus!');
    }
}
