<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Api\SyncController;
use App\Http\Controllers\Controller;
use App\Models\GalleryImage;
use App\Models\Media;
use App\Traits\HandlesImageUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    use \App\Traits\LogsActivity, HandlesImageUploads;

    public function edit(GalleryImage $gallery)
    {
        return view('admin.gallery.edit', compact('gallery'));
    }

    public function update(Request $request, GalleryImage $gallery)
    {
        $validated = $request->validate([
            'caption' => 'required|string|max:255',
            'category' => 'required|in:tour',
            'isActive' => 'boolean',
        ]);

        $gallery->update($validated);
        $this->logActivity('updated', "Updated gallery item: {$gallery->caption}", $gallery);

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery item updated!');
    }

    public function index(Request $request)
    {
        $query = GalleryImage::query();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('caption', 'like', "%{$request->search}%");
        }

        if ($request->filled('start_date')) {
            $query->whereDate('createdAt', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('createdAt', '<=', $request->end_date);
        }

        $images = $query->latest('createdAt')->paginate(20);

        return view('admin.gallery.index', compact('images'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'caption' => 'nullable|string',
            'category' => 'required|in:tour,outbound',
            'gallery_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'gallery_image_media_id' => 'nullable|exists:media,id',
            'tags' => 'nullable|array',
        ]);

        $uploaded = 0;

        // Handle image input (dual mode: file upload or media library)
        if ($request->hasFile('gallery_image')) {
            $media = $this->uploadAndIndex($request->file('gallery_image'), 'gallery', $validated['caption'] ?? 'Gallery Image');
            
            $img = GalleryImage::create([
                'caption' => $validated['caption'] ?? $media->original_name,
                'category' => $validated['category'],
                'image_id' => $media->id,
                'imageUrl' => $media->path, // Keep legacy field
                'tags' => $validated['tags'] ?? [],
                'isActive' => true,
            ]);
            
            $this->logActivity('created', "Uploaded gallery image: {$img->caption}", $img);
            $uploaded++;
        } elseif ($request->filled('gallery_image_media_id')) {
            $media = Media::find($request->gallery_image_media_id);
            
            if ($media) {
                $img = GalleryImage::create([
                    'caption' => $validated['caption'] ?? $media->original_name,
                    'category' => $validated['category'],
                    'image_id' => $media->id,
                    'imageUrl' => $media->path, // Keep legacy field
                    'tags' => $validated['tags'] ?? [],
                    'isActive' => true,
                ]);
                
                $this->logActivity('created', "Added gallery image: {$img->caption}", $img);
                $uploaded++;
            }
        }

        SyncController::triggerSync();

        return redirect()->route('admin.gallery.index')->with('success', "$uploaded gambar berhasil ditambahkan ke galeri!");
    }

    public function destroy(GalleryImage $gallery)
    {
        if ($gallery->imageUrl) {
            $path = str_replace('/storage/', '', $gallery->imageUrl);
            Storage::disk('public')->delete($path);
        }
        $cap = $gallery->caption;
        $gallery->delete();
        $this->logActivity('deleted', "Deleted gallery image: {$cap}");
        SyncController::triggerSync();

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Foto berhasil dihapus dari Galeri!');
    }

    public function toggleStatus(GalleryImage $gallery)
    {
        $gallery->update(['isActive' => ! $gallery->isActive]);

        return back()->with('success', 'Status foto diperbarui.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['message' => 'No IDs provided'], 400);
        }

        GalleryImage::whereIn('id', $ids)->delete();
        $this->logActivity('bulk_deleted', 'Bulk deleted '.count($ids).' gallery images');

        return response()->json(['message' => 'Gallery images deleted successfully']);
    }

    public function storeFromMedia(Request $request)
    {
        $request->validate([
            'media_ids' => 'required|array',
            'category' => 'required|in:tour',
        ]);

        $added = 0;
        $media = Media::whereIn('id', $request->media_ids)->get();

        foreach ($media as $item) {
            GalleryImage::create([
                'caption' => $item->original_name ?? $item->filename,
                'category' => $request->category,
                'imageUrl' => $item->path,
                'isActive' => true,
            ]);
            $added++;
        }

        SyncController::triggerSync();

        return response()->json([
            'success' => true,
            'message' => "$added foto berhasil ditambahkan dari Galeri Pusat.",
        ]);
    }

    public function export(Request $request)
    {
        // ... (unchanged)
    }
}
