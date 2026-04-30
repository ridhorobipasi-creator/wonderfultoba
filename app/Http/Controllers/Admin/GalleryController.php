<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = GalleryImage::query();
        if ($request->filled('search')) {
            $query->where('caption', 'like', "%{$request->search}%");
        }
        $images = $query->latest()->paginate(20);
        return view('admin.gallery.index', compact('images'));
    }

    public function create()
    {
        return view('admin.gallery.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'caption' => 'required|string|max:255',
            'category' => 'required|in:tour,outbound',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'tags' => 'nullable|array',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('gallery', 'public');
            GalleryImage::create([
                'caption' => $request->caption,
                'category' => $request->category,
                'imageUrl' => '/storage/' . $path,
                'tags' => $request->tags ?? [],
                'isActive' => true,
            ]);
        }

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Image added to gallery!');
    }

    public function destroy(GalleryImage $gallery)
    {
        if ($gallery->imageUrl) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $gallery->imageUrl));
        }
        $gallery->delete();

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Image removed from gallery!');
    }
}
