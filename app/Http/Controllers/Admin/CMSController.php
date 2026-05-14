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
        return view('admin.cms.index', compact('settings'));
    }

    public function tour()
    {
        $settings = \App\Models\Setting::where('key', 'cms_tour')->first()?->value ?? [];
        
        // Fetch data for selection lists
        $packages = \App\Models\Package::where('isOutbound', false)->where('status', 'active')->get();
        $blogs = \App\Models\Blog::where('status', 'published')->orderBy('createdAt', 'desc')->get();
        $gallery = \App\Models\GalleryImage::orderBy('orderPriority', 'asc')->get();

        // Logic for auto-populating slides removed to allow clean reset.

        return view('admin.cms.tour', compact('settings', 'packages', 'blogs', 'gallery'));
    }

    public function outbound()
    {
        $settings = \App\Models\Setting::where('key', 'cms_outbound')->first()?->value ?? [];
        return view('admin.outbound.cms', compact('settings'));
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

            // 1. Ambil data lama
            $setting = \App\Models\Setting::where('key', $key)->first();
            $existing = $setting ? ($setting->value ?? []) : [];
            
            // 2. Ambil data input baru (kecuali token)
            $data = $request->except(['_token']);
            
            // 3. Handle recursive file uploads (including nested arrays like slides)
            $processFiles = function($files, &$targetData) use (&$processFiles) {
                foreach ($files as $key => $file) {
                    if (is_array($file)) {
                        if (!isset($targetData[$key])) $targetData[$key] = [];
                        $processFiles($file, $targetData[$key]);
                    } else if ($file instanceof \Illuminate\Http\UploadedFile) {
                        $path = $this->uploadAndIndex($file, 'cms', 'cms_upload');

                        // Handle path convention for settings JSON
                        $savedPath = $path; // We'll keep path relative to storage
                        
                        // If the field name ends with '_file', replace it with '_url'
                        $urlField = str_replace(['_file', '_upload'], '', $key);
                        if (!str_ends_with($urlField, '_url')) {
                            $urlField .= '_url';
                        }
                        
                        $targetData[$urlField] = $savedPath;
                        unset($targetData[$key]);
                    }
                }
            };

            $allFiles = $request->allFiles();
            $processFiles($allFiles, $data);

            \Illuminate\Support\Facades\Log::info('CMS Saving Data for ' . $key, ['data' => $data]);

            // 4. Merge data: Data baru menimpa data lama
            $finalData = array_merge($existing, $data);
            
            \Illuminate\Support\Facades\Log::info('CMS Final Merged Data', ['final' => $finalData]);

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
            
            \Illuminate\Support\Facades\Log::alert("CMS SUCCESS: Saved '{$key}' with fields: " . implode(', ', array_keys($data)));

            \App\Http\Controllers\Api\SyncController::triggerSync();

            return back()->with('success', 'Perubahan berhasil diterbitkan ke halaman publik!');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            \Illuminate\Support\Facades\Log::error("CMS FATAL ERROR: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }
}
