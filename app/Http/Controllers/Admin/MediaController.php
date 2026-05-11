<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Traits\HandlesImageUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    use HandlesImageUploads;

    public function sync()
    {
        $files = Storage::disk('public')->allFiles();
        $indexedCount = 0;
        $extensions = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'];

        foreach ($files as $file) {
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (!in_array($extension, $extensions) || str_contains($file, '/thumbnails/')) continue;

            $exists = Media::where('path', $file)->exists();
            if ($exists) continue;

            $category = 'uncategorized';
            $parts = explode('/', $file);
            if (count($parts) > 1) {
                // If it's in gallery/category/file.webp
                if ($parts[0] === 'gallery' && isset($parts[1])) {
                    $category = $parts[1];
                } else {
                    $category = $parts[0];
                    if (is_numeric($category)) $category = 'uploads';
                }
            }

            Media::create([
                'filename' => basename($file),
                'original_name' => basename($file),
                'path' => $file,
                'category' => $category,
                'mime_type' => 'image/' . ($extension === 'jpg' ? 'jpeg' : $extension),
                'size' => Storage::disk('public')->size($file),
            ]);
            $indexedCount++;
        }

        return response()->json([
            'success' => true, 
            'message' => "Sinkronisasi berhasil! $indexedCount aset baru ditemukan dan ditambahkan."
        ]);
    }

    public function index(Request $request)
    {
        $query = Media::orderBy('order_priority', 'asc')->orderBy('created_at', 'desc');

        if ($request->category) {
            $query->where('category', $request->category);
        }

        if ($request->search) {
            $query->where('original_name', 'like', '%' . $request->search . '%');
        }

        $media = $query->paginate(24);
        
        // Append custom attributes to each item
        $media->getCollection()->transform(function($item) {
            $item->usage_count = $item->usage_count;
            return $item;
        });

        // Handle client-side usage filter (simple version)
        if ($request->usage === 'orphan') {
            $filtered = $media->getCollection()->filter(fn($i) => $i->usage_count === 0);
            $media->setCollection($filtered);
        } elseif ($request->usage === 'used') {
            $filtered = $media->getCollection()->filter(fn($i) => $i->usage_count > 0);
            $media->setCollection($filtered);
        }

        $categories = Media::select('category')
            ->selectRaw('count(*) as count')
            ->groupBy('category')
            ->get()
            ->map(function($cat) {
                return [
                    'name' => $cat->category,
                    'count' => $cat->count,
                    'icon' => match($cat->category) {
                        'branding' => 'fa-award',
                        'cms' => 'fa-window-restore',
                        'icons' => 'fa-icons',
                        'uploads' => 'fa-cloud-arrow-up',
                        'assets' => 'fa-folder-open',
                        default => 'fa-folder'
                    }
                ];
            });

        if ($request->ajax()) {
            return response()->json([
                'media' => $media,
                'categories' => $categories,
                'stats' => [
                    'total' => Media::count(),
                    'orphans' => Media::get()->filter(fn($m) => $m->usage_count === 0)->count()
                ]
            ]);
        }

        return view('admin.media.index', compact('media', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'files.*' => 'required|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'category' => 'nullable|string'
        ]);

        $uploadedMedia = [];
        $category = $request->category ?? 'uncategorized';

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $this->uploadAndConvert($file, 'gallery/' . $category);
                
                if ($path) {
                    $media = Media::create([
                        'filename' => basename($path),
                        'original_name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'category' => $category,
                        'mime_type' => $file->getClientMimeType(),
                        'size' => $file->getSize(),
                    ]);
                    $uploadedMedia[] = $media;
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => count($uploadedMedia) . ' media berhasil diunggah.',
            'data' => $uploadedMedia
        ]);
    }

    public function update(Request $request, Media $media)
    {
        $validated = $request->validate([
            'category' => 'nullable|string',
            'alt_text' => 'nullable|string|max:255',
            'order_priority' => 'nullable|integer'
        ]);

        $media->update($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Media diperbarui.']);
        }

        return back()->with('success', 'Media diperbarui.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) return response()->json(['success' => false, 'message' => 'Tidak ada aset yang dipilih.']);

        $mediaItems = Media::whereIn('id', $ids)->get();
        $count = 0;

        foreach ($mediaItems as $media) {
            $path = $media->path;
            $media->delete();
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
                
                // Also delete thumbnail
                $thumbPath = dirname($path) . '/thumbnails/' . basename($path);
                if (Storage::disk('public')->exists($thumbPath)) {
                    Storage::disk('public')->delete($thumbPath);
                }
            }
            $count++;
        }

        return response()->json([
            'success' => true,
            'message' => "$count aset berhasil dihapus secara permanen."
        ]);
    }

    public function bulkDownload(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) return back()->with('error', 'Tidak ada aset yang dipilih.');

        $mediaItems = Media::whereIn('id', $ids)->get();
        $zipName = 'wonderful_toba_assets_' . date('YmdHis') . '.zip';
        $zipPath = storage_path('app/public/temp/' . $zipName);

        if (!file_exists(dirname($zipPath))) {
            mkdir(dirname($zipPath), 0755, true);
        }

        $zip = new \ZipArchive;
        if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
            foreach ($mediaItems as $media) {
                $filePath = storage_path('app/public/' . $media->path);
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, $media->filename);
                }
            }
            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function move(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'category' => 'required|string'
        ]);

        Media::whereIn('id', $request->ids)->update(['category' => $request->category]);

        return response()->json(['success' => true, 'message' => 'Aset berhasil dipindahkan.']);
    }

    public function renameFolder(Request $request)
    {
        $request->validate([
            'old_name' => 'required|string',
            'new_name' => 'required|string'
        ]);

        Media::where('category', $request->old_name)->update(['category' => $request->new_name]);

        return response()->json(['success' => true, 'message' => 'Folder berhasil diganti nama.']);
    }

    public function rename(Request $request, Media $media)
    {
        $request->validate(['filename' => 'required|string|max:255']);
        
        $media->update(['original_name' => $request->filename]);

        return response()->json(['success' => true, 'message' => 'File berhasil diganti nama.']);
    }

    public function destroy(Media $media)
    {
        $path = $media->path;
        $media->delete();

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            
            // Also delete thumbnail
            $thumbPath = dirname($path) . '/thumbnails/' . basename($path);
            if (Storage::disk('public')->exists($thumbPath)) {
                Storage::disk('public')->delete($thumbPath);
            }
        }

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Media berhasil dihapus.']);
        }

        return back()->with('success', 'Media dihapus.');
    }
}
