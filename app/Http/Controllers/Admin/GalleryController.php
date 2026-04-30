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
            $query->where('title', 'like', "%{$request->search}%");
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
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'tags' => 'nullable|array',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('gallery', 'public');
            GalleryImage::create([
                'title' => $request->title,
                'url' => '/storage/' . $path,
                'tags' => $request->tags ?? [],
            ]);
        }

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Image added to gallery!');
    }

    public function destroy(GalleryImage $gallery)
    {
        if ($gallery->url) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $gallery->url));
        }
        $gallery->delete();

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Image removed from gallery!');
    }
}
