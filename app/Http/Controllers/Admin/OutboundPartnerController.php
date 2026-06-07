<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\OutboundPartner;
use App\Traits\HandlesImageUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class OutboundPartnerController extends Controller
{
    use HandlesImageUploads;

    public function index()
    {
        $partners = OutboundPartner::orderBy('orderPriority')->get();

        return view('admin.outbound.partners.index', compact('partners'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'media_id' => 'nullable|exists:media,id',
            'websiteUrl' => 'nullable|url|max:255',
            'orderPriority' => 'nullable|integer',
            'isActive' => 'nullable|boolean',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $this->uploadAndIndex($request->file('logo'), 'outbound/partners', 'outbound');
        } elseif ($request->filled('media_id')) {
            $media = Media::find($request->media_id);
            $validated['logo'] = $media->path;
        } else {
            $request->validate(['logo' => 'required']);
        }

        $validated['isActive'] = $request->has('isActive') ? (bool) $request->isActive : true;
        $validated['orderPriority'] = $validated['orderPriority'] ?? (OutboundPartner::max('orderPriority') + 1);

        OutboundPartner::create($validated);
        Cache::forget('outbound_partners');

        return redirect()->back()->with('success', 'Partner berhasil ditambahkan!');
    }

    public function update(Request $request, OutboundPartner $partner)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'media_id' => 'nullable|exists:media,id',
            'websiteUrl' => 'nullable|url|max:255',
            'orderPriority' => 'required|integer',
            'isActive' => 'nullable|boolean',
        ]);

        if ($request->hasFile('logo')) {
            if ($partner->logo) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $partner->logo));
            }
            $validated['logo'] = $this->uploadAndIndex($request->file('logo'), 'outbound/partners', 'outbound');
        } elseif ($request->filled('media_id')) {
            $media = Media::find($request->media_id);
            $validated['logo'] = $media->path;
        }

        $validated['isActive'] = $request->has('isActive') ? (bool) $request->isActive : false;

        $partner->update($validated);
        Cache::forget('outbound_partners');

        return redirect()->back()->with('success', 'Partner berhasil diperbarui!');
    }

    public function destroy(OutboundPartner $partner)
    {
        if ($partner->logo) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $partner->logo));
        }
        $partner->delete();
        Cache::forget('outbound_partners');

        return redirect()->back()->with('success', 'Partner berhasil dihapus!');
    }
}
