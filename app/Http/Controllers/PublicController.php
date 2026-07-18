<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Blog;
use App\Models\City;
use App\Models\Client;
use App\Models\GalleryImage;
use App\Models\Package;
use App\Models\Setting;
use App\Services\BookingService;
use App\Services\OgBannerService;
use App\Services\TourService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PublicController extends Controller
{
    public function __construct(
        protected TourService $tourService,
        protected BookingService $bookingService
    ) {}

    /**
     * Helper: Get structured site settings.
     * Note: $siteSettings is ALSO shared globally via AppServiceProvider View Composer,
     * but some methods need the structured array for logic (not just views).
     */
    private function getSiteSettings(array $keys = ['cms_tour', 'general']): array
    {
        return Cache::remember('site_settings_structured_'.implode('_', $keys), 3600, function () use ($keys) {
            $settings = [];
            foreach ($keys as $key) {
                $settings[$key] = Setting::where('key', $key)->first()?->value ?? [];
            }

            return $settings;
        });
    }

    /**
     * Tour & Travel Main Page — Cached for performance.
     */
    public function tour()
    {
        try {
            $siteSettings = $this->getSiteSettings(['cms_tour', 'general']);
            $settings = $siteSettings['cms_tour'];

            // Cache homepage data for 10 minutes
            $homeData = Cache::remember('tour_homepage_data', 600, function () {
                $gallerySlides = GalleryImage::where('isActive', true)
                    ->orderBy('orderPriority')
                    ->take(12)
                    ->get()
                    ->map(fn ($img) => [
                        'url' => $img->imageUrl,
                        'caption' => $img->caption ?? '',
                        'category' => $img->category ?? '',
                    ])
                    ->values()
                    ->toArray();

                return [
                    'packages' => $this->tourService->getFeaturedPackages(),
                    'blogs' => $this->tourService->getBlogs(3),
                    'gallerySlides' => $gallerySlides,
                ];
            });

            $packages = $homeData['packages'];
            $blogs = $homeData['blogs'];
            $gallerySlides = $homeData['gallerySlides'];

            // Translate only lightweight fields (title, excerpt, category) — NOT full content
            $blogs->each(function ($b) {
                $b->title = __($b->title);
                $b->excerpt = __($b->excerpt);
                $b->category = __($b->category);
            });

            return view('tour.index', compact('settings', 'packages', 'blogs', 'gallerySlides', 'siteSettings'));
        } catch (\Exception $e) {
            Log::error('Error loading tour index: '.$e->getMessage());

            return back()->with('error', 'Gagal memuat data tour. Silakan coba beberapa saat lagi.');
        }
    }

    public function tourPackages(Request $request)
    {
        try {
            $siteSettings = $this->getSiteSettings();
            $packages = $this->tourService->getAllPackages();
            $cities = $this->tourService->getCities();

            return view('tour.packages', compact('packages', 'cities', 'siteSettings'));
        } catch (\Exception $e) {
            Log::error('Error loading tour packages: '.$e->getMessage());

            return back()->with('error', 'Gagal memuat daftar paket.');
        }
    }

    public function tourGallery()
    {
        $siteSettings = $this->getSiteSettings();
        $images = $this->tourService->getGallery();

        return view('tour.gallery', compact('images', 'siteSettings'));
    }

    public function tourBlog()
    {
        $siteSettings = $this->getSiteSettings();
        $posts = $this->tourService->getBlogs();

        // Translate lightweight fields only
        $posts->each(function ($post) {
            $post->title = __($post->title);
            $post->excerpt = __($post->excerpt);
            $post->category = __($post->category);
        });

        return view('tour.blog', compact('posts', 'siteSettings'));
    }

    public function tourPackageDetail($slug)
    {
        try {
            $siteSettings = $this->getSiteSettings();
            $originCity = null;

            $package = $this->tourService->getPackageBySlug($slug);

            // pSEO: If exact slug not found, try to detect "-dari-{kota}" pattern
            if (! $package && str_contains($slug, '-dari-')) {
                // Find the last occurrence of '-dari-' to extract origin city
                $lastDariPos = strrpos($slug, '-dari-');
                $baseSlug    = substr($slug, 0, $lastDariPos);
                $kotaSlug    = substr($slug, $lastDariPos + 6); // skip '-dari-'

                // Validate kota against allowed origins in settings
                $originsString  = $siteSettings['general']['seo_pseo_origins'] ?? 'jakarta, surabaya, bandung, bali, batam, palembang, makassar, semarang, yogyakarta, kuala-lumpur, singapore, penang, pekanbaru, padang, malaysia';
                $allowedOrigins = array_filter(array_map('trim', explode(',', strtolower($originsString))));
                $allowedOrigins = array_map(fn($o) => str_replace(' ', '-', $o), $allowedOrigins);

                if (in_array($kotaSlug, $allowedOrigins)) {
                    $package    = $this->tourService->getPackageBySlug($baseSlug);
                    $originCity = Str::title(str_replace('-', ' ', $kotaSlug));
                }
            }

            if (! $package) {
                abort(404);
            }

            // Session-based view counting — prevent F5 inflation.
            // Use a raw increment so it does not fire model events (which would
            // clear the tour cache) nor bump updatedAt on every page view.
            $viewKey = 'viewed_package_'.$package->id;
            if (! session()->has($viewKey)) {
                DB::table('packages')->where('id', $package->id)->increment('views_count');
                session()->put($viewKey, true);
            }

            $city = City::find($package->cityId);

            $taxPercentage = 11;
            $setting = Setting::where('key', 'general')->first();
            if ($setting && isset($setting->value['finance']['tax_percentage'])) {
                $taxPercentage = (float) $setting->value['finance']['tax_percentage'];
            }

            return view('tour.package-detail', compact('package', 'city', 'siteSettings', 'originCity', 'taxPercentage'));
        } catch (\Exception $e) {
            Log::error("Error loading package detail ($slug): ".$e->getMessage());

            abort(404);
        }
    }

    public function tourBlogDetail($slug)
    {
        try {
            $siteSettings = $this->getSiteSettings();
            $post = $this->tourService->getBlogPost($slug);

            if (! $post) {
                abort(404);
            }

            // Session-based view counting — prevent F5 inflation.
            // Raw increment: no model events (avoids cache clear), no updatedAt bump.
            $viewKey = 'viewed_blog_'.$post->id;
            if (! session()->has($viewKey)) {
                DB::table('blogs')->where('id', $post->id)->increment('views_count');
                session()->put($viewKey, true);
            }

            $relatedPosts = $this->tourService->getRelatedBlogs($post->id);

            return view('tour.blog-detail', compact('post', 'relatedPosts', 'siteSettings'));
        } catch (\Exception $e) {
            Log::error("Error loading blog detail ($slug): ".$e->getMessage());

            abort(404);
        }
    }

    /**
     * Programmatic SEO Landing Pages (by Origin City)
     */
    public function landingOrigin($kota)
    {
        try {
            $siteSettings = $this->getSiteSettings(['cms_tour', 'general']);
            
            $originsString = $siteSettings['general']['seo_pseo_origins'] ?? 'jakarta, surabaya, bandung, bali, batam, palembang, makassar, semarang, yogyakarta, kuala-lumpur, singapore, penang, pekanbaru, padang, malaysia';
            $allowedOrigins = array_filter(array_map('trim', explode(',', strtolower($originsString))));
            
            // Allow hyphens instead of spaces for URL matching
            $allowedOrigins = array_map(function($o) { return str_replace(' ', '-', $o); }, $allowedOrigins);

            $kotaSlug = strtolower(trim($kota));

            if (!in_array($kotaSlug, $allowedOrigins)) {
                return redirect()->route('tour.packages');
            }

            $originName = Str::title(str_replace('-', ' ', $kotaSlug));
            
            // Re-use logic from tour() method
            $packages = $this->tourService->getFeaturedPackages();
            $blogs = $this->tourService->getBlogs(3);

            return view('tour.landing-origin', compact('packages', 'blogs', 'siteSettings', 'originName', 'kotaSlug'));
        } catch (\Exception $e) {
            Log::error('Error loading pSEO landing page: '.$e->getMessage());
            return redirect()->route('tour.packages');
        }
    }

    public function submitBooking(StoreBookingRequest $request)
    {
        try {
            // Honeypot Security Check (Pencegahan Spam)
            if ($request->filled('website_url')) {
                // Bot terdeteksi karena mengisi hidden input. Berikan respon seolah berhasil.
                Log::warning('Honeypot triggered during booking submission.', ['ip' => $request->ip()]);
                return back()->with([
                    'success' => __('Booking berhasil dikirim! Kami akan menghubungi Anda segera.'),
                    'bookingCode' => 'BOT-' . strtoupper(Str::random(6)),
                    'whatsappUrl' => null,
                ]);
            }

            $validated = $request->validated();
            $package = $this->tourService->getPackageBySlug($request->slug ?? '');

            if (! $package) {
                $package = Package::find($validated['packageId']);
            }

            $endDate = $validated['startDate'];
            if ($package && $package->duration) {
                if (preg_match('/(\d+)\s*(Hari|Days|D|H)/i', $package->duration, $matches)) {
                    $days = (int) $matches[1];
                    if ($days > 1) {
                        $endDate = date('Y-m-d', strtotime($validated['startDate'].' + '.($days - 1).' days'));
                    }
                }
            }

            $booking = $this->bookingService->create(array_merge($validated, [
                'type' => 'package',
                'endDate' => $endDate,
                'status' => 'pending',
                'metadata' => [
                    'pax' => $validated['pax'],
                    'paxChildren' => $validated['paxChildren'] ?? 0,
                    'selected_services' => $validated['selected_services'] ?? []
                ],
            ]));

            // Set Carbon locale for date formatting
            $locale = session('locale', 'id');
            $carbonLocaleMap = ['id' => 'id', 'my' => 'ms', 'en' => 'en'];
            $carbonLocale = $carbonLocaleMap[$locale] ?? 'id';
            $formattedDate = Carbon::parse($validated['startDate'])
                ->locale($carbonLocale)
                ->translatedFormat('d F Y');

            // Construct WhatsApp Message
            $invoiceUrl = route('invoice.download', $booking->bookingCode);
            $trackingUrl = route('booking.track', $booking->bookingCode);
            $bookingDate = now()
                ->locale($carbonLocale)
                ->translatedFormat('d F Y, H:i');

            $waMessage = __('Halo Sujai Laketoba, saya ingin memesan paket wisata.')."\n\n".
                         '*'.__('Detail Pesanan:')."*\n".
                         '- '.__('Kode Booking').': '.$booking->bookingCode."\n".
                         '- '.__('Status').': '.__('Menunggu konfirmasi admin')."\n".
                         '- '.__('Paket').': '.$package->name."\n".
                         '- '.__('Link Paket').': '.route('tour.package.detail', $package->slug)."\n".
                         '- '.__('Nama').': '.$validated['customerName']."\n".
                         '- '.__('Email').': '.$validated['customerEmail']."\n".
                         '- '.__('WhatsApp').': '.$validated['customerPhone']."\n".
                         '- '.__('Tanggal').': '.$formattedDate."\n".
                         '- '.__('Peserta').': '.$validated['pax'].' '.__('Orang')."\n".
                         '- '.__('Estimasi Total').': Rp '.number_format((float) $booking->totalPrice, 0, ',', '.')."\n".
                         '- '.__('Tanggal Booking').': '.$bookingDate."\n\n".
                         '*'.__('Link Penting:')."*\n".
                         '- '.__('Invoice').': '.$invoiceUrl."\n".
                         '- '.__('Tekan ini untuk lihat track').': '.$trackingUrl."\n";

            if (! empty($validated['notes'])) {
                $waMessage .= '- '.__('Catatan').': '.$validated['notes']."\n";
            }

            $waMessage .= "\n".__('Mohon konfirmasinya. Terima kasih!');

            $settings = Setting::where('key', 'cms_tour')->first()?->value ?? [];
            $genSettings = Setting::where('key', 'general')->first()?->value ?? [];

            $waSource = $settings['contact_whatsapp']
                ?? $genSettings['contact_whatsapp'] 
                ?? config('services.whatsapp.number');
            $waNumber = preg_replace('/[^0-9]/', '', (string) $waSource);

            // If there's no WhatsApp number configured, log and return success without WA URL
            if (empty($waNumber)) {
                \Log::warning('submitBooking: WhatsApp number not configured.', ['cms_tour' => $settings, 'general' => $genSettings]);

                return back()->with([
                    'success' => __('Booking berhasil dikirim! Kami akan menghubungi Anda segera.'),
                    'bookingCode' => $booking->bookingCode,
                    'whatsappUrl' => null,
                    'warning' => __('Nomor WhatsApp belum dikonfigurasi. Tim admin akan menghubungi Anda.'),
                ]);
            }

            $waUrl = "https://wa.me/{$waNumber}?text=".urlencode($waMessage);

            return back()->with([
                'success' => __('Booking berhasil dikirim! Kami akan menghubungi Anda segera.'),
                'bookingCode' => $booking->bookingCode,
                'whatsappUrl' => $waUrl,
            ]);
        } catch (\Exception $e) {
            Log::error('Booking Submission Error: '.$e->getMessage(), ['request' => $request->all()]);

            return back()->with('error', __('Terjadi kesalahan saat memproses pesanan. Tim IT kami telah dinotifikasi.'));
        }
    }

    public function showTrackBookingForm()
    {
        $siteSettings = $this->getSiteSettings(['cms_landing', 'cms_tour', 'general']);

        return view('booking.lookup', compact('siteSettings'));
    }

    public function redirectTrackBooking(Request $request)
    {
        $validated = $request->validate([
            'booking_code' => ['required', 'string', 'max:30'],
        ]);

        $code = strtoupper(trim($validated['booking_code']));

        return redirect()->route('booking.track', $code);
    }

    public function trackBooking(string $code)
    {
        $siteSettings = $this->getSiteSettings(['cms_landing', 'cms_tour', 'general']);
        $code = strtoupper(trim($code));
        $booking = \App\Models\Booking::with(['package', 'package.city'])
            ->where('bookingCode', $code)
            ->firstOrFail();

        return view('booking.track', compact('booking', 'siteSettings'));
    }



    public function about()
    {
        $siteSettings = $this->getSiteSettings(['cms_landing', 'cms_tour', 'general']);
        $content = Setting::where('key', 'page_about')->first()?->value ?? [];
        $clients = Client::orderBy('orderPriority')->get();

        return view('pages.about', compact('content', 'siteSettings', 'clients'));
    }



    public function terms()
    {
        $siteSettings = $this->getSiteSettings(['cms_landing', 'general']);
        $content = Setting::where('key', 'page_terms')->first()?->value ?? [];

        return view('pages.terms', compact('content', 'siteSettings'));
    }

    public function privacy()
    {
        $siteSettings = $this->getSiteSettings(['cms_landing', 'general']);
        $content = Setting::where('key', 'page_privacy')->first()?->value ?? [];

        return view('pages.privacy', compact('content', 'siteSettings'));
    }

    public function payment()
    {
        $siteSettings = $this->getSiteSettings(['cms_landing', 'general']);

        return view('pages.payment', compact('siteSettings'));
    }

    public function submitOutboundQuote(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'participants' => 'required|integer|min:1',
            'location' => 'required|string|max:255',
            'activity_type' => 'nullable|string|max:255',
            'estimated_date' => 'required|date',
            'whatsapp' => 'required|string|max:255',
        ]);

        $generalSettings = Setting::where('key', 'general')->first()?->value ?? [];
        $waSource = $generalSettings['contact_whatsapp'] ?? config('services.whatsapp.number');
        $waNumber = preg_replace('/[^0-9]/', '', (string) $waSource);

        if ($waNumber === '') {
            return back()->with('error', __('Nomor WhatsApp belum dikonfigurasi.'));
        }

        $message = "Halo Sujai Laketoba, saya ingin meminta penawaran outbound.\n\n"
            ."Company: {$validated['company_name']}\n"
            ."Peserta: {$validated['participants']}\n"
            ."Lokasi: {$validated['location']}\n"
            .'Aktivitas: '.($validated['activity_type'] ?? '-')."\n"
            ."Estimasi: {$validated['estimated_date']}\n"
            ."WhatsApp: {$validated['whatsapp']}";

        $waUrl = "https://wa.me/{$waNumber}?text=".urlencode($message);

        return back()->with([
            'success' => __('Permintaan outbound berhasil dikirim.'),
            'whatsappUrl' => $waUrl,
        ]);
    }

    /**
     * Generate dynamic, beautiful OpenGraph card banners for social shares.
     */
    public function generateOgBanner(string $type, int $id, OgBannerService $ogBannerService)
    {
        $path = $ogBannerService->getOrGenerateBanner($type, $id);

        return response()->file($path, [
            'Content-Type' => 'image/webp',
            'Cache-Control' => 'public, max-age=31536000'
        ]);
    }
}
