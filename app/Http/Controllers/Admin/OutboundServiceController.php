<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OutboundService;
use Illuminate\Http\Request;

class OutboundServiceController extends Controller
{
    public function index()
    {
        $services = OutboundService::orderBy('sortOrder')->get();
        return view('admin.outbound.services.index', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
        ]);

        $validated['sortOrder'] = OutboundService::max('sortOrder') + 1;
        OutboundService::create($validated);

        return redirect()->back()->with('success', 'Service added!');
    }

    public function update(Request $request, OutboundService $service)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
        ]);

        $service->update($validated);
        return redirect()->back()->with('success', 'Service updated!');
    }

    public function destroy(OutboundService $service)
    {
        $service->delete();
        return redirect()->back()->with('success', 'Service removed!');
    }
}
