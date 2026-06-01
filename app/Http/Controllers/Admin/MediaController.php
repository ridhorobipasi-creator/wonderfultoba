<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Traits\HandlesImageUploads;
use Illuminate\Http\JsonResponse;
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
            if (! in_array($extension, $extensions) || str_contains($file, '/thumbnails/')) {
                continue;
            }

            $exists = Media::where('path', $file)->exists();
            if ($exists) {
                continue;
            }

            $category = 'uncategorized';
            $parts = explode('/', $file);
            if (count($parts) > 1) {
                // If it's in gallery/category/file.webp
                if ($parts[0] === 'gallery' && isset($parts[1])) {
                    $category = $parts[1];
                } else {
                    $category = $parts[0];
                    if (is_numeric($category)) {
                        $category = 'uploads';
                    }
                }
            }

            // Extract dominant color if GD is available
            $dominantColor = null;
            if (extension_loaded('gd') && in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
                try {
                    $absolutePath = Storage::disk('public')->path($file);
                    $imgData = @file_get_contents($absolutePath);
                    if ($imgData) {
                        $image = @imagecreatefromstring($imgData);
                        if ($image) {
                            $dominantColor = $this->extractDominantColor($image);
                            imagedestroy($image);
                        }
                    }
                } catch (\Exception $e) {
                }
            }

            Media::create([
                'filename' => basename($file),
                'original_name' => basename($file),
                'path' => $file,
                'category' => $category,
                'mime_type' => 'image/'.($extension === 'jpg' ? 'jpeg' : $extension),
                'size' => Storage::disk('public')->size($file),
                'thumb' => dirname($file).'/thumbnails/'.basename($file),
                'dominant_color' => $dominantColor,
            ]);
            $indexedCount++;
        }

        return response()->json([
            'success' => true,
            'message' => "Sinkronisasi berhasil! $indexedCount aset baru ditemukan dan ditambahkan.",
        ]);
    }

    public function index(Request $request)
    {
        $query = Media::orderBy('order_priority', 'asc')->orderBy('created_at', 'desc');

        if ($request->category) {
            $query->where('category', $request->category);
        }

        if ($request->search) {
            $query->where('original_name', 'like', '%'.$request->search.'%');
        }

        $media = $query->paginate(24);

        // Append custom attributes to each item
        $media->getCollection()->transform(function ($item) {
            $item->usage_count = $item->usage_count;

            return $item;
        });

        // Handle client-side usage filter (simple version)
        if ($request->usage === 'orphan') {
            $filtered = $media->getCollection()->filter(fn ($i) => $i->usage_count === 0);
            $media->setCollection($filtered);
        } elseif ($request->usage === 'used') {
            $filtered = $media->getCollection()->filter(fn ($i) => $i->usage_count > 0);
            $media->setCollection($filtered);
        }

        $categories = Media::select('category')
            ->selectRaw('count(*) as count')
            ->groupBy('category')
            ->get()
            ->map(function ($cat) {
                return [
                    'name' => $cat->category,
                    'count' => $cat->count,
                    'icon' => match ($cat->category) {
                        'branding' => 'fa-award',
                        'cms' => 'fa-window-restore',
                        'icons' => 'fa-icons',
                        'uploads' => 'fa-cloud-arrow-up',
                        'assets' => 'fa-folder-open',
                        default => 'fa-folder'
                    },
                ];
            });

        if ($request->ajax()) {
            return response()->json([
                'media' => $media,
                'categories' => $categories,
                'stats' => [
                    'total' => Media::count(),
                    'orphans' => Media::get()->filter(fn ($m) => $m->usage_count === 0)->count(),
                ],
            ]);
        }

        return view('admin.media.index', compact('media', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'files.*' => 'required|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
            'category' => 'nullable|string',
            'watermark' => 'nullable',
        ]);

        $uploadedMedia = [];
        $category = $request->category ?? 'uncategorized';
        $watermark = $request->boolean('watermark');

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $this->uploadAndIndex($file, 'gallery/'.$category, $category, null, $watermark);

                if ($path) {
                    $uploadedMedia[] = Media::where('path', $path)->first();
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => count($uploadedMedia).' media berhasil diunggah.',
            'data' => $uploadedMedia,
        ]);
    }

    public function update(Request $request, Media $media)
    {
        $validated = $request->validate([
            'category' => 'nullable|string',
            'alt_text' => 'nullable|string|max:255',
            'order_priority' => 'nullable|integer',
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
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada aset yang dipilih.']);
        }

        $mediaItems = Media::whereIn('id', $ids)->get();
        $count = 0;

        foreach ($mediaItems as $media) {
            $path = $media->path;
            $media->delete();
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);

                // Also delete thumbnail
                $thumbPath = dirname($path).'/thumbnails/'.basename($path);
                if (Storage::disk('public')->exists($thumbPath)) {
                    Storage::disk('public')->delete($thumbPath);
                }
            }
            $count++;
        }

        return response()->json([
            'success' => true,
            'message' => "$count aset berhasil dihapus secara permanen.",
        ]);
    }

    public function bulkDownload(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return back()->with('error', 'Tidak ada aset yang dipilih.');
        }

        $mediaItems = Media::whereIn('id', $ids)->get();
        $zipName = 'wonderful_toba_assets_'.date('YmdHis').'.zip';
        $zipPath = storage_path('app/public/temp/'.$zipName);

        if (! file_exists(dirname($zipPath))) {
            mkdir(dirname($zipPath), 0755, true);
        }

        $zip = new \ZipArchive;
        if ($zip->open($zipPath, \ZipArchive::CREATE) === true) {
            foreach ($mediaItems as $media) {
                $filePath = storage_path('app/public/'.$media->path);
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
            'category' => 'required|string',
        ]);

        Media::whereIn('id', $request->ids)->update(['category' => $request->category]);

        return response()->json(['success' => true, 'message' => 'Aset berhasil dipindahkan.']);
    }

    public function renameFolder(Request $request)
    {
        $request->validate([
            'old_name' => 'required|string',
            'new_name' => 'required|string',
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
            $thumbPath = dirname($path).'/thumbnails/'.basename($path);
            if (Storage::disk('public')->exists($thumbPath)) {
                Storage::disk('public')->delete($thumbPath);
            }
        }

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Media berhasil dihapus.']);
        }

        return back()->with('success', 'Media dihapus.');
    }

    /**
     * Convert all non-webp images in the media library to WebP format.
     *
     * @return JsonResponse
     */
    public function convertAll()
    {
        // Get all Media records where path extension is NOT webp
        $medias = Media::all()->filter(function ($media) {
            return strtolower(pathinfo($media->path, PATHINFO_EXTENSION)) !== 'webp';
        });

        $convertedCount = 0;

        foreach ($medias as $media) {
            $oldPath = $media->path;
            $newPath = $this->convertPathToWebp($oldPath);

            if ($newPath) {
                // Update the media record
                $media->update([
                    'filename' => basename($newPath),
                    'path' => $newPath,
                    'mime_type' => 'image/webp',
                    'size' => Storage::disk('public')->size($newPath),
                    'thumb' => dirname($newPath).'/thumbnails/'.basename($newPath),
                    'dominant_color' => $this->lastDominantColor,
                    'blur_hash' => $this->lastBlurHash,
                    'exif_data' => $this->lastExifData,
                ]);
                $convertedCount++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "$convertedCount file berhasil dikonversi ke format WebP.",
        ]);
    }

    /**
     * Download, convert to WebP, and store/index an image from an external URL.
     *
     * @return JsonResponse
     */
    public function storeFromUrl(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
            'category' => 'nullable|string',
            'alt_text' => 'nullable|string',
            'watermark' => 'nullable',
        ]);

        $category = $request->category ?? 'uncategorized';
        $altText = $request->alt_text;
        $watermark = $request->boolean('watermark');

        $path = $this->uploadFromUrl($request->url, 'gallery/'.$category, $category, $altText, $watermark);

        if ($path) {
            $media = Media::where('path', $path)->first();

            return response()->json([
                'success' => true,
                'message' => 'Media berhasil diunduh dan dikonversi dari URL.',
                'data' => $media,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal mengunduh atau mengonversi gambar dari URL tersebut. Pastikan URL valid dan mengarah ke file gambar.',
        ], 422);
    }

    /**
     * Audit storage: find physical orphan files and database orphan records.
     *
     * @return JsonResponse
     */
    public function audit()
    {
        $allDiskFiles = Storage::disk('public')->allFiles();
        $dbPaths = Media::pluck('path')->toArray();

        $dbPathsSet = array_flip($dbPaths);
        $orphanDiskFiles = [];
        $totalSize = 0;

        $extensions = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'];

        foreach ($allDiskFiles as $file) {
            // Skip thumbnails and non-images
            if (str_contains($file, '/thumbnails/') || str_starts_with($file, 'temp/')) {
                continue;
            }

            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (! in_array($ext, $extensions)) {
                continue;
            }

            if (! isset($dbPathsSet[$file])) {
                $size = Storage::disk('public')->size($file);
                $orphanDiskFiles[] = [
                    'path' => $file,
                    'filename' => basename($file),
                    'size' => $size,
                    'size_formatted' => round($size / 1024, 1).' KB',
                    'url' => Storage::disk('public')->url($file),
                    'created_at' => date('Y-m-d H:i:s', Storage::disk('public')->lastModified($file)),
                ];
                $totalSize += $size;
            }
        }

        return response()->json([
            'success' => true,
            'orphans' => $orphanDiskFiles,
            'total_size' => $totalSize,
            'total_size_formatted' => round($totalSize / (1024 * 1024), 2).' MB',
        ]);
    }

    /**
     * Delete orphan physical files selected by the admin.
     *
     * @return JsonResponse
     */
    public function cleanOrphanFiles(Request $request)
    {
        $paths = $request->input('paths', []);
        $deletedCount = 0;

        foreach ($paths as $path) {
            // Security check
            if (str_contains($path, '..') || str_starts_with($path, '/')) {
                continue;
            }

            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);

                // Delete thumb if exists
                $thumbPath = dirname($path).'/thumbnails/'.basename($path);
                if (Storage::disk('public')->exists($thumbPath)) {
                    Storage::disk('public')->delete($thumbPath);
                }

                $deletedCount++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "$deletedCount file yatim (orphan) berhasil dihapus secara permanen dari disk.",
        ]);
    }

    /**
     * Crop an image and replace the original file with the cropped version.
     *
     * @return JsonResponse
     */
    public function crop(Request $request, Media $media)
    {
        $request->validate([
            'image' => 'required|string', // Base64 data URL
        ]);

        $dataUrl = $request->input('image');
        if (preg_match('/^data:image\/(\w+);base64,/', $dataUrl, $type)) {
            $data = substr($dataUrl, strpos($dataUrl, ',') + 1);
            $data = base64_decode($data);

            if ($data === false) {
                return response()->json(['success' => false, 'message' => 'Format base64 tidak valid.'], 400);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Format data URL tidak valid.'], 400);
        }

        $path = $media->path;
        Storage::disk('public')->put($path, $data);

        // Generate new thumbnail and extract dominant color
        if (extension_loaded('gd')) {
            $image = @imagecreatefromstring($data);
            if ($image) {
                // dominant color
                $dominantColor = $this->extractDominantColor($image);

                // thumbnail
                $width = imagesx($image);
                $height = imagesy($image);
                $targetWidth = 400;
                $targetHeight = floor($height * ($targetWidth / $width));

                $thumbImg = imagecreatetruecolor($targetWidth, $targetHeight);
                imagealphablending($thumbImg, false);
                imagesavealpha($thumbImg, true);
                imagecopyresampled($thumbImg, $image, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);

                ob_start();
                imagewebp($thumbImg, null, 70);
                $thumbData = ob_get_clean();

                $thumbPath = dirname($path).'/thumbnails/'.basename($path);
                Storage::disk('public')->put($thumbPath, $thumbData);

                // Generate responsive variants & blur hash
                $this->generateResponsiveVariants($image, dirname($path), basename($path));
                $blurHash = $this->generateBlurHash($image);

                imagedestroy($thumbImg);
                imagedestroy($image);

                $media->update([
                    'size' => strlen($data),
                    'dominant_color' => $dominantColor,
                    'blur_hash' => $blurHash,
                ]);
            }
        } else {
            $media->update([
                'size' => strlen($data),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Gambar berhasil dipotong (cropped) dan diperbarui!',
            'data' => $media,
        ]);
    }
}
