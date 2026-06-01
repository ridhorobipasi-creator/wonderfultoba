<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Api\SyncController;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\GalleryImage;
use App\Models\Package;
use App\Models\Setting;
use App\Traits\HandlesImageUploads;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CMSController extends Controller
{
    use HandlesImageUploads;

    public function index()
    {
        $settings = Setting::where('key', 'cms_landing')->first()?->value ?? [];

        return view('admin.cms.index', compact('settings'));
    }

    public function tour()
    {
        $settings = Setting::where('key', 'cms_tour')->first()?->value ?? [];

        // Fetch data for selection lists
        $packages = Package::where('status', 'active')->get();
        $blogs = Blog::where('status', 'published')->orderBy('createdAt', 'desc')->get();
        $gallery = GalleryImage::orderBy('orderPriority', 'asc')->get();

        // Logic for auto-populating slides removed to allow clean reset.

        return view('admin.cms.tour', compact('settings', 'packages', 'blogs', 'gallery'));
    }

    public function pages()
    {
        $about = Setting::where('key', 'page_about')->first()?->value ?? [];
        $terms = Setting::where('key', 'page_terms')->first()?->value ?? [];
        $privacy = Setting::where('key', 'page_privacy')->first()?->value ?? [];

        return view('admin.cms.pages', compact('about', 'terms', 'privacy'));
    }

    public function save(Request $request, $key)
    {
        try {
            DB::beginTransaction();

            // 1. Ambil data lama
            $setting = Setting::where('key', $key)->first();
            $existing = $setting ? ($setting->value ?? []) : [];

            // 2. Ambil data input baru (kecuali token)
            $data = $request->except(['_token']);

            // 3. Handle recursive file uploads (including nested arrays like slides)
            $processFiles = function ($files, &$targetData) use (&$processFiles) {
                foreach ($files as $key => $file) {
                    if (is_array($file)) {
                        if (! isset($targetData[$key])) {
                            $targetData[$key] = [];
                        }
                        $processFiles($file, $targetData[$key]);
                    } elseif ($file instanceof UploadedFile) {
                        $path = $this->uploadAndIndex($file, 'cms', 'cms_upload');

                        // Handle path convention for settings JSON
                        $savedPath = $path; // We'll keep path relative to storage

                        // If the field name ends with '_file', replace it with '_url'
                        $urlField = str_replace(['_file', '_upload'], '', $key);
                        if (! str_ends_with($urlField, '_url')) {
                            $urlField .= '_url';
                        }

                        $targetData[$urlField] = $savedPath;
                        unset($targetData[$key]);
                    }
                }
            };

            $allFiles = $request->allFiles();
            $processFiles($allFiles, $data);

            Log::info('CMS Saving Data for '.$key, ['data' => $data]);

            // 4. Merge data: data baru menimpa data lama.
            // Untuk array nested seperti homepage_slides, array_merge dangkal bisa merusak strukturnya.
            // Karena itu, kita deep-merge secara aman (minimal untuk nested arrays).
            $finalData = $existing;

            $deepMerge = function (&$target, $source) use (&$deepMerge) {
                foreach ((array) $source as $key => $value) {
                    if (is_array($value) && isset($target[$key]) && is_array($target[$key])) {
                        $deepMerge($target[$key], $value);
                    } else {
                        $target[$key] = $value;
                    }
                }
            };

            $deepMerge($finalData, $data);

            Log::info('CMS Final Merged Data', ['final' => $finalData]);

            // 5. Simpan ke database
            if ($setting) {
                $setting->value = $finalData;
                $setting->save();
            } else {
                Setting::create([
                    'key' => $key,
                    'value' => $finalData,
                ]);
            }

            DB::commit();

            // Clear related caches so frontend updates immediately
            Cache::forget('cms_tour_settings');
            Cache::forget('site_settings_all');

            Log::alert("CMS SUCCESS: Saved '{$key}' with fields: ".implode(', ', array_keys($data)));

            SyncController::triggerSync();

            return back()->with('success', 'Perubahan berhasil diterbitkan ke halaman publik!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('CMS FATAL ERROR: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Gagal menyimpan: '.$e->getMessage());
        }
    }
}
