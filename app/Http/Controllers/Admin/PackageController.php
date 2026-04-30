<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Package::with('city');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('locationTag', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('isOutbound', $request->type === 'outbound');
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by featured
        if ($request->filled('featured')) {
            $query->where('isFeatured', $request->featured === 'yes');
        }

        $packages = $query->orderBy('sortOrder')->orderBy('createdAt', 'desc')->paginate(15);

        return view('admin.packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cities = City::orderBy('name')->get();
        return view('admin.packages.create', compact('cities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'shortDescription' => 'nullable|string',
            'description' => 'nullable|string',
            'locationTag' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'childPrice' => 'nullable|numeric|min:0',
            'duration' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive',
            'isFeatured' => 'boolean',
            'isOutbound' => 'boolean',
            'cityId' => 'nullable|exists:cities,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Handle Image Uploads
        $validated['images'] = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('packages', 'public');
                $validated['images'][] = '/storage/' . $path;
            }
        }

        // Generate slug
        $validated['slug'] = Str::slug($validated['name']);

        Package::create($validated);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Package $package)
    {
        $package->load('city');
        return view('admin.packages.show', compact('package'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Package $package)
    {
        $cities = City::orderBy('name')->get();
        return view('admin.packages.edit', compact('package', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'shortDescription' => 'nullable|string',
            'description' => 'nullable|string',
            'locationTag' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'childPrice' => 'nullable|numeric|min:0',
            'duration' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive',
            'isFeatured' => 'boolean',
            'isOutbound' => 'boolean',
            'cityId' => 'nullable|exists:cities,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'remove_images' => 'nullable|array',
        ]);

        // Handle Image Deletion
        $currentImages = $package->images ?? [];
        if ($request->filled('remove_images')) {
            foreach ($request->remove_images as $imgToRemove) {
                if (($key = array_search($imgToRemove, $currentImages)) !== false) {
                    unset($currentImages[$key]);
                    Storage::disk('public')->delete(str_replace('/storage/', '', $imgToRemove));
                }
            }
        }

        // Handle New Image Uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('packages', 'public');
                $currentImages[] = '/storage/' . $path;
            }
        }
        $validated['images'] = array_values($currentImages);

        // Update slug if name changed
        if ($validated['name'] !== $package->name) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $package->update($validated);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Package $package)
    {
        // Delete all images
        if ($package->images) {
            foreach ($package->images as $image) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $image));
            }
        }
        
        $package->delete();

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package deleted successfully!');
    }
}
