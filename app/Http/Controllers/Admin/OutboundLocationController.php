<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OutboundLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OutboundLocationController extends Controller
{
    public function index()
    {
        $locations = OutboundLocation::latest()->get();
        return view('admin.outbound.locations.index', compact('locations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('outbound/locations', 'public');
            OutboundLocation::create([
                'name' => $request->name,
                'image' => '/storage/' . $path
            ]);
        }

        return redirect()->back()->with('success', 'Location added!');
    }

    public function destroy(OutboundLocation $location)
    {
        if ($location->image) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $location->image));
        }
        $location->delete();
        return redirect()->back()->with('success', 'Location removed!');
    }
}
