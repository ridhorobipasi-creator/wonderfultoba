<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Api\SyncController;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Package;
use App\Services\TourService;
use App\Traits\HandlesImageUploads;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PackageController extends Controller
{
    use HandlesImageUploads, LogsActivity;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $type = $request->get('type', 'tour'); // Default to tour if not specified
        $query = Package::with('city');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('locationTag', 'like', "%{$search}%");
            });
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
     * Display the specified resource.
     */
    public function show(Package $package)
    {
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request, TourService $tourService)
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
            'cityId' => 'nullable|exists:cities,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'itinerary' => 'nullable|array',
            'cost_price' => 'nullable|numeric|min:0',
            'includes' => 'nullable|array',
            'excludes' => 'nullable|array',
            'media_ids' => 'nullable|array',
            'pricingDetails' => 'nullable|array',
            'pricingDetails.additional_services' => 'nullable|array',
            'pricingDetails.additional_services.*.name' => 'required|string|max:255',
            'pricingDetails.additional_services.*.icon' => 'required|string|max:255',
            'pricingDetails.additional_services.*.price' => 'required|numeric|min:0',
        ]);

        try {
            $validated['image_files'] = $request->file('images');
            $package = $tourService->savePackage($validated);

            $this->logActivity('created', "Created new package: {$package->name}", $package);
            SyncController::triggerSync();

            return redirect()->route('admin.packages.index')->with('success', 'Package created successfully!');
        } catch (\Exception $e) {
            Log::error('Package Creation Failed: '.$e->getMessage());

            return back()->withInput()->with('error', 'Failed to create package. '.$e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Package $package, TourService $tourService)
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
            'cityId' => 'nullable|exists:cities,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'remove_images' => 'nullable|array',
            'itinerary' => 'nullable|array',
            'cost_price' => 'nullable|numeric|min:0',
            'includes' => 'nullable|array',
            'excludes' => 'nullable|array',
            'media_ids' => 'nullable|array',
            'pricingDetails' => 'nullable|array',
            'pricingDetails.additional_services' => 'nullable|array',
            'pricingDetails.additional_services.*.name' => 'required|string|max:255',
            'pricingDetails.additional_services.*.icon' => 'required|string|max:255',
            'pricingDetails.additional_services.*.price' => 'required|numeric|min:0',
        ]);

        try {
            $validated['image_files'] = $request->file('images');
            $tourService->savePackage($validated, $package);

            $this->logActivity('updated', "Updated package: {$package->name}", $package);
            SyncController::triggerSync();

            return redirect()->route('admin.packages.index')->with('success', 'Package updated successfully!');
        } catch (\Exception $e) {
            Log::error('Package Update Failed: '.$e->getMessage());

            return back()->withInput()->with('error', 'Failed to update package. '.$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Package $package, TourService $tourService)
    {
        $name = $package->name;
        $tourService->deletePackage($package);

        $this->logActivity('deleted', "Deleted package: {$name}");
        SyncController::triggerSync();

        return redirect()->route('admin.packages.index')->with('success', 'Package deleted successfully!');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['message' => 'No IDs provided'], 400);
        }

        Package::whereIn('id', $ids)->delete();
        $this->logActivity('bulk_deleted', 'Bulk deleted '.count($ids).' packages');

        return response()->json(['message' => 'Packages deleted successfully']);
    }

    public function export(Request $request)
    {
        $format = $request->get('format', 'xlsx');
        $filename = 'packages-export-'.date('Y-m-d').'.'.$format;

        $query = Package::query();
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $packages = $query->get();

        return \Excel::download(new class($packages) implements FromCollection, WithHeadings, WithMapping
        {
            protected $data;

            public function __construct($data)
            {
                $this->data = $data;
            }

            public function collection()
            {
                return $this->data;
            }

            public function headings(): array
            {
                return ['ID', 'Nama', 'Lokasi', 'Harga', 'Durasi', 'Status', 'Featured', 'Dibuat Pada'];
            }

            public function map($row): array
            {
                return [
                    $row->id,
                    $row->name,
                    $row->locationTag,
                    $row->price,
                    $row->duration,
                    strtoupper($row->status),
                    $row->isFeatured ? 'Ya' : 'Tidak',
                    $row->createdAt->format('Y-m-d H:i'),
                ];
            }
        }, $filename);
    }

    public function restore($id)
    {
        $package = Package::onlyTrashed()->findOrFail($id);
        $package->restore();

        $this->logActivity('restored', "Restored package: {$package->name}", $package);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package restored successfully!');
    }

    public function toggleStatus(Package $package)
    {
        $package->status = ($package->status === 'active') ? 'inactive' : 'active';
        $package->save();

        $this->logActivity('toggled', "Toggled status of package: {$package->name} → {$package->status}", $package);
        SyncController::triggerSync();

        return response()->json([
            'success' => true,
            'status' => $package->status,
            'message' => 'Status berhasil diubah ke '.strtoupper($package->status),
        ]);
    }
}
