<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OutboundLocation;
use App\Traits\HandlesImageUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class OutboundLocationController extends Controller
{
    use HandlesImageUploads;
    public function index()
    {
        $locations = OutboundLocation::latest()->get();
        return view('admin.outbound.locations.index', compact('locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'media_id' => 'nullable|exists:media,id',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $this->uploadAndIndex($request->file('image'), 'outbound/locations', 'outbound');
        } elseif ($request->filled('media_id')) {
            $media = \App\Models\Media::find($request->media_id);
            $validated['image'] = $media->path;
        }

        OutboundLocation::create($validated);
        Cache::forget('outbound_locations');
        return redirect()->back()->with('success', 'Lokasi berhasil ditambahkan!');
    }

    public function update(Request $request, OutboundLocation $location)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'media_id' => 'nullable|exists:media,id',
        ]);

        if ($request->hasFile('image')) {
            if ($location->image) {
                Storage::disk('public')->delete($location->image);
            }
            $validated['image'] = $this->uploadAndIndex($request->file('image'), 'outbound/locations', 'outbound');
        } elseif ($request->filled('media_id')) {
            $media = \App\Models\Media::find($request->media_id);
            $validated['image'] = $media->path;
        }

        $location->update($validated);
        Cache::forget('outbound_locations');
        return redirect()->back()->with('success', 'Lokasi berhasil diperbarui!');
    }

    public function destroy(OutboundLocation $location)
    {
        if ($location->image) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $location->image));
        }
        $location->delete();
        Cache::forget('outbound_locations');
        return redirect()->back()->with('success', 'Lokasi berhasil dihapus!');
    }
}
