<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OutboundService;
use Illuminate\Http\Request;

class OutboundServiceController extends Controller
{
    public function index()
    {
        $services = OutboundService::orderBy('orderPriority')->get();
        return view('admin.outbound.services.index', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'shortDesc' => 'nullable|string|max:200',
            'detailDesc' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'image' => 'nullable|string|max:500',
        ]);

        $validated['orderPriority'] = OutboundService::max('orderPriority') + 1;
        OutboundService::create($validated);

        return redirect()->back()->with('success', 'Layanan berhasil ditambahkan!');
    }

    public function update(Request $request, OutboundService $service)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'shortDesc' => 'nullable|string|max:200',
            'detailDesc' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'image' => 'nullable|string|max:500',
        ]);

        $service->update($validated);
        return redirect()->back()->with('success', 'Layanan berhasil diperbarui!');
    }

    public function destroy(OutboundService $service)
    {
        $service->delete();
        return redirect()->back()->with('success', 'Layanan berhasil dihapus!');
    }
}
