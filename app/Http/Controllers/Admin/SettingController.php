<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Package;
use App\Models\Setting;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    use LogsActivity;

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

    public function refreshExchangeRates(Request $request)
    {
        try {
            $setting = Setting::where('key', 'general')->first();
            $apiKey = $setting->value['finance']['exchange_rate_api_key'] ?? '';

            if (empty($apiKey)) {
                return response()->json(['error' => 'API Key ExchangeRate-API belum disetel di pengaturan.'], 400);
            }

            $response = \Illuminate\Support\Facades\Http::get("https://v6.exchangerate-api.com/v6/{$apiKey}/latest/MYR");
            if (!$response->successful()) {
                return response()->json(['error' => 'Gagal menghubungi API kurs. Periksa API key.'], 400);
            }

            $data = $response->json();
            $myrToIdr = $data['conversion_rates']['IDR'] ?? null;
            $myrToSgd = $data['conversion_rates']['SGD'] ?? null;

            if (!$myrToIdr || !$myrToSgd) {
                return response()->json(['error' => 'Data kurs tidak valid dari API.'], 400);
            }

            $sgdToIdr = $myrToIdr / $myrToSgd; // Deriving SGD from MYR data

            return response()->json([
                'MYR' => round($myrToIdr, 2),
                'SGD' => round($sgdToIdr, 2)
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error refreshing exchange rates: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan sistem saat mengambil data kurs.'], 500);
        }
    }
}
