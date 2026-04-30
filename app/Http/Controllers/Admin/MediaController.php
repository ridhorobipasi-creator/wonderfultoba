<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index()
    {
        $images = GalleryImage::orderByDesc('createdAt')->get();
        return view('admin.media.index', compact('images'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'files.*' => 'required|image|mimes:jpeg,jpg,png,webp|max:5120',
        ]);

        $uploaded = 0;
        foreach ($request->file('files') as $file) {
            $path = $file->store('media', 'public');
            GalleryImage::create([
                'imageUrl' => Storage::url($path),
                'caption'  => $request->input('caption', ''),
                'category' => $request->input('category', 'general'),
            ]);
            $uploaded++;
        }

        return back()->with('success', "$uploaded file berhasil diunggah ke Media Library.");
    }

    public function destroy($id)
    {
        $image = GalleryImage::findOrFail($id);

        // Delete file from storage if it's a local file
        if (str_contains($image->imageUrl, '/storage/')) {
            $path = str_replace('/storage/', '', $image->imageUrl);
            Storage::disk('public')->delete($path);
        }

        $image->delete();

        return back()->with('success', 'Gambar berhasil dihapus.');
    }

    public function copy($id)
    {
        $image = GalleryImage::findOrFail($id);
        return response()->json(['url' => $image->imageUrl]);
    }
}
