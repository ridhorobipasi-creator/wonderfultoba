<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use App\Traits\HandlesImageUploads;

class CarController extends Controller
{
    use HandlesImageUploads;

    public function index()
    {
        $cars = Car::latest('createdAt')->paginate(10);
        return view('admin.cars.index', compact('cars'));
    }

    public function create()
    {
        return view('admin.cars.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'transmission' => 'required|string|in:manual,automatic',
            'fuel' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'priceWithDriver' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|string|in:active,inactive',
            'isFeatured' => 'nullable|boolean',
        ]);

        $data = $validated;
        $data['isFeatured'] = $request->has('isFeatured');
        $data['images'] = $request->input('images', []); 

        Car::create($data);
        Cache::forget('cars_active');

        return redirect()->route('admin.cars.index')->with('success', 'Mobil berhasil ditambahkan.');
    }

    public function edit(Car $car)
    {
        return view('admin.cars.edit', compact('car'));
    }

    public function update(Request $request, Car $car)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'transmission' => 'required|string|in:manual,automatic',
            'fuel' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'priceWithDriver' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|string|in:active,inactive',
            'isFeatured' => 'nullable|boolean',
        ]);

        $data = $validated;
        $data['isFeatured'] = $request->has('isFeatured');
        $data['images'] = $request->input('images', []);

        $car->update($data);
        Cache::forget('cars_active');

        return redirect()->route('admin.cars.index')->with('success', 'Mobil berhasil diperbarui.');
    }

    public function destroy(Car $car)
    {
        $car->delete();
        Cache::forget('cars_active');
        return redirect()->route('admin.cars.index')->with('success', 'Mobil berhasil dihapus.');
    }

    public function show(Car $car)
    {
        return view('admin.cars.show', compact('car'));
    }
}
