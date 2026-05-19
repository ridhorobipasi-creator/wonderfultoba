<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HandlesImageUploads;
use Illuminate\Http\Request;

class CMSController extends Controller
{
    use HandlesImageUploads;
    public function index()
    {
        $settings = \App\Models\Setting::where('key', 'cms_landing')->first()?->value ?? [];
        
        // Fetch data for selection lists (for Slider)
        $packages = \App\Models\Package::where('isOutbound', false)->where('status', 'active')->get();
        $blogs = \App\Models\Blog::where('status', 'published')->orderBy('createdAt', 'desc')->get();
        $gallery = \App\Models\GalleryImage::orderBy('orderPriority', 'asc')->get();

        return view('admin.cms.index', compact('settings', 'packages', 'blogs', 'gallery'));
    }


    public function pages()
    {
        $about = \App\Models\Setting::where('key', 'page_about')->first()?->value ?? [];
        $terms = \App\Models\Setting::where('key', 'page_terms')->first()?->value ?? [];
        $privacy = \App\Models\Setting::where('key', 'page_privacy')->first()?->value ?? [];
        
        return view('admin.cms.pages', compact('about', 'terms', 'privacy'));
    }

    public function save(Request $request, $key)
    {
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $setting = \App\Models\Setting::where('key', $key)->first();
            $existing = $setting ? ($setting->value ?? []) : [];
            
            // 1. Ambil data input baru (kecuali token)
            $data = $request->except(['_token']);
            
            // 2. Handle file uploads
            $processFiles = function($files, &$targetData) use (&$processFiles) {
                foreach ($files as $name => $file) {
                    if (is_array($file)) {
                        if (!isset($targetData[$name])) $targetData[$name] = [];
                        $processFiles($file, $targetData[$name]);
                    } else if ($file instanceof \Illuminate\Http\UploadedFile) {
                        // Validate file
                        if (!$file->isValid()) {
                            throw new \Exception("File '{$name}' tidak valid atau melebihi limit upload.");
                        }

                        $path = $this->uploadAndConvert($file, 'cms');
                        
                        // Index into Media Library
                        \App\Models\Media::create([
                            'filename' => basename($path),
                            'original_name' => $file->getClientOriginalName(),
                            'path' => $path,
                            'category' => 'cms_upload',
                            'mime_type' => $file->getClientMimeType(),
                            'size' => $file->getSize(),
                        ]);

                        // Determine the URL field name
                        // e.g. tour_image (file) -> tour_image_url
                        $urlField = $name . '_url';
                        // If the name already has _file or _upload, strip it
                        $baseName = str_replace(['_file', '_upload'], '', $name);
                        if (!str_ends_with($baseName, '_url')) {
                            $urlField = $baseName . '_url';
                        } else {
                            $urlField = $baseName;
                        }
                        
                        $targetData[$urlField] = $path;
                        // Keep the original name for compatibility but as string
                        $targetData[$name] = $path;
                    }
                }
            };

            $processFiles($request->allFiles(), $data);

            // 3. Merge data
            $finalData = array_merge($existing, $data);
            
            // 4. Cleanup: Remove actual File objects from data if any left
            foreach ($finalData as $k => $v) {
                if ($v instanceof \Illuminate\Http\UploadedFile) {
                    unset($finalData[$k]);
                }
            }

            // 5. Simpan ke database
            if ($setting) {
                $setting->value = $finalData;
                $setting->save();
            } else {
                \App\Models\Setting::create([
                    'key' => $key,
                    'value' => $finalData
                ]);
            }

            \Illuminate\Support\Facades\DB::commit();
            
            \App\Http\Controllers\Api\SyncController::triggerSync();
            \Illuminate\Support\Facades\Cache::forget('site_settings_global');

            return back()->with('success', 'Perubahan berhasil disimpan dan diterbitkan!');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            \Illuminate\Support\Facades\Log::error("CMS SAVE ERROR [$key]: " . $e->getMessage());
            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }
}
