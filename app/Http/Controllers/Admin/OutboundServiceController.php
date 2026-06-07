<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\OutboundService;
use App\Traits\HandlesImageUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OutboundServiceController extends Controller
{
    use HandlesImageUploads;

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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'media_id' => 'nullable|exists:media,id',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $this->uploadAndIndex($request->file('image'), 'outbound/services', 'outbound');
        } elseif ($request->filled('media_id')) {
            $media = Media::find($request->media_id);
            $validated['image'] = $media->path;
        }

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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'media_id' => 'nullable|exists:media,id',
        ]);

        if ($request->hasFile('image')) {
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }
            $validated['image'] = $this->uploadAndIndex($request->file('image'), 'outbound/services', 'outbound');
        } elseif ($request->filled('media_id')) {
            $media = Media::find($request->media_id);
            $validated['image'] = $media->path;
        }

        $service->update($validated);

        return redirect()->back()->with('success', 'Layanan berhasil diperbarui!');
    }

    public function destroy(OutboundService $service)
    {
        $service->delete();

        return redirect()->back()->with('success', 'Layanan berhasil dihapus!');
    }
}
