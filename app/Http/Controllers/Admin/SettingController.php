<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Package;
use App\Models\Setting;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class SettingController extends Controller
{
    use \App\Traits\HandlesImageUploads, LogsActivity;

    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token');
        $allFiles = $request->allFiles();

        // Handle File Uploads
        foreach ($allFiles as $key => $files) {
            if (is_array($files)) {
                foreach ($files as $subKey => $file) {
                    if ($file instanceof UploadedFile) {
                        $path = $this->uploadAndIndex($file, 'branding', 'branding');
                        $data[$key][$subKey.'_url'] = $path;
                    }
                }
            } elseif ($files instanceof UploadedFile) {
                $path = $this->uploadAndIndex($files, 'branding', 'branding');
                // If the key is 'general', we might want to store it as 'logo_url' inside general
                $data[$key.'_url'] = $path;
            }
        }

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                // For array values (grouped settings), merge with existing data
                $setting = Setting::firstOrNew(['key' => $key]);
                if (! $setting->exists) {
                    $setting->value = [];
                }
                $existingValue = $setting->value ?? [];

                // Recursively merge to handle deep structures like tour_landing[hero][title]
                $newValue = array_replace_recursive($existingValue, $value);

                $setting->value = $newValue;
                $setting->save();
            } else {
                // For simple key-value pairs (if any)
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }
        }

        $this->logActivity('updated', 'Updated system engine settings', null, $data);

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }

    public function generateSitemap(Request $request)
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Base URLs
        $baseUrl = url('/');
        $urls = [
            '',
            '/tour',
            '/tour/packages',
            '/tour/blog',
            '/about',
            '/terms',
            '/privacy',
        ];

        foreach ($urls as $url) {
            $xml .= '<url>';
            $xml .= '<loc>'.$baseUrl.$url.'</loc>';
            $xml .= '<changefreq>daily</changefreq>';
            $xml .= '<priority>0.8</priority>';
            $xml .= '</url>';
        }

        // Packages (canonical)
        $packages = Package::where('status', 'active')->get();
        foreach ($packages as $package) {
            $xml .= '<url>';
            $xml .= '<loc>'.route('tour.package.detail', $package->slug).'</loc>';
            $xml .= '<lastmod>'.($package->updatedAt ?? $package->createdAt)->format('Y-m-d').'</lastmod>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>0.9</priority>';
            $xml .= '</url>';
        }

        // Programmatic SEO: Packages × Cities
        // e.g. /tour/paket-danau-toba-3h2m/dari-jakarta
        $seoSetting    = Setting::where('key', 'general')->first();
        $originsString = $seoSetting->value['seo_pseo_origins'] ?? 'jakarta, surabaya, bandung, bali, batam, palembang, makassar, semarang, yogyakarta, kuala-lumpur, singapore, penang, pekanbaru, padang, malaysia';
        $allowedOrigins = array_values(array_filter(array_map(
            fn($o) => str_replace(' ', '-', trim(strtolower($o))),
            explode(',', $originsString)
        )));

        foreach ($packages as $package) {
            $lastmod = ($package->updatedAt ?? $package->createdAt)->format('Y-m-d');
            foreach ($allowedOrigins as $kota) {
                $xml .= '<url>';
                $xml .= '<loc>'.url('/tour/package/'.$package->slug.'-dari-'.$kota).'</loc>';
                $xml .= '<lastmod>'.$lastmod.'</lastmod>';
                $xml .= '<changefreq>weekly</changefreq>';
                $xml .= '<priority>0.85</priority>';
                $xml .= '</url>';
            }
        }

        // Blogs
        $blogs = Blog::where('status', 'published')->get();
        foreach ($blogs as $blog) {
            $xml .= '<url>';
            $xml .= '<loc>'.route('tour.blog.detail', $blog->slug).'</loc>';
            $xml .= '<lastmod>'.($blog->updatedAt ?? $blog->createdAt)->format('Y-m-d').'</lastmod>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>0.7</priority>';
            $xml .= '</url>';
        }

        // Programmatic SEO: City Landing Pages (/dari-{kota})
        foreach ($allowedOrigins as $kota) {
            $xml .= '<url>';
            $xml .= '<loc>'.route('landing.origin', $kota).'</loc>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>0.8</priority>';
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        if ($request->isMethod('post')) {
            file_put_contents(public_path('sitemap.xml'), $xml);
            $this->logActivity('system', 'Generated new sitemap.xml');

            return response()->json(['message' => 'Sitemap.xml berhasil diperbarui dan disimpan di folder public!']);
        }

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
