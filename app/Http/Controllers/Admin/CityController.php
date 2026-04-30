<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CityController extends Controller
{
    public function index(Request $request)
    {
        $query = City::query();
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }
        $cities = $query->orderBy('name')->paginate(15);
        return view('admin.cities.index', compact('cities'));
    }

    public function create()
    {
        return view('admin.cities.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:cities,name',
            'description' => 'nullable|string',
            'image' => 'nullable|string', // Simple for now
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        City::create($validated);

        return redirect()->route('admin.cities.index')
            ->with('success', 'City created successfully!');
    }

    public function edit(City $city)
    {
        return view('admin.cities.edit', compact('city'));
    }

    public function update(Request $request, City $city)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:cities,name,' . $city->id,
            'description' => 'nullable|string',
            'image' => 'nullable|string',
        ]);

        if ($validated['name'] !== $city->name) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $city->update($validated);

        return redirect()->route('admin.cities.index')
            ->with('success', 'City updated successfully!');
    }

    public function destroy(City $city)
    {
        $city->delete();
        return redirect()->route('admin.cities.index')
            ->with('success', 'City deleted successfully!');
    }
}
