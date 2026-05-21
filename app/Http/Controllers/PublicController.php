<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Setting;
use App\Models\City;
use App\Services\BookingService;
use App\Services\TourService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

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
        return Cache::remember('site_settings_structured_' . implode('_', $keys), 3600, function () use ($keys) {
            $settings = [];
            foreach ($keys as $key) {
                $settings[$key] = Setting::where('key', $key)->first()?->value ?? [];
            }
            return $settings;
        });
    }

    /**
     * Homepage / Split Landing Page
     */
    public function index()
    {
        try {
            $siteSettings = $this->getSiteSettings(['cms_landing', 'cms_tour', 'general']);
            $content = $siteSettings['cms_landing'];

            return view('index', compact('content', 'siteSettings'));
        } catch (\Exception $e) {
            Log::error('Error loading index page: ' . $e->getMessage());
            return view('errors.500');
        }
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
                $gallerySlides = \App\Models\GalleryImage::where('isActive', true)
                    ->orderBy('orderPriority')
                    ->take(12)
                    ->get()
                    ->map(fn($img) => [
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
            Log::error('Error loading tour index: ' . $e->getMessage());
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
            Log::error('Error loading tour packages: ' . $e->getMessage());
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
            $package = $this->tourService->getPackageBySlug($slug);

            if (!$package) {
                return redirect()->route('tour.packages')->with('error', 'Paket tidak ditemukan.');
            }

            // Session-based view counting — prevent F5 inflation
            $viewKey = 'viewed_package_' . $package->id;
            if (!session()->has($viewKey)) {
                $package->increment('views_count');
                session()->put($viewKey, true);
            }

            $city = City::find($package->cityId);

            return view('tour.package-detail', compact('package', 'city', 'siteSettings'));
        } catch (\Exception $e) {
            Log::error("Error loading package detail ($slug): " . $e->getMessage());
            return redirect()->route('tour.packages')->with('error', 'Gagal memuat detail paket.');
        }
    }

    public function tourBlogDetail($slug)
    {
        try {
            $siteSettings = $this->getSiteSettings();
            $post = $this->tourService->getBlogPost($slug);

            if (!$post) {
                return redirect()->route('tour.blog');
            }

            // Session-based view counting — prevent F5 inflation
            $viewKey = 'viewed_blog_' . $post->id;
            if (!session()->has($viewKey)) {
                $post->increment('views_count');
                session()->put($viewKey, true);
            }

            $relatedPosts = $this->tourService->getRelatedBlogs($post->id);

            return view('tour.blog-detail', compact('post', 'relatedPosts', 'siteSettings'));
        } catch (\Exception $e) {
            Log::error("Error loading blog detail ($slug): " . $e->getMessage());
            return redirect()->route('tour.blog');
        }
    }

    public function submitBooking(StoreBookingRequest $request)
    {
        try {
            $validated = $request->validated();
            $package = $this->tourService->getPackageBySlug($request->slug ?? '');

            if (!$package) {
                $package = \App\Models\Package::find($validated['packageId']);
            }

            $endDate = $validated['startDate'];
            if ($package && $package->duration) {
                if (preg_match('/(\d+)\s*(Hari|Days|D|H)/i', $package->duration, $matches)) {
                    $days = (int)$matches[1];
                    if ($days > 1) {
                        $endDate = date('Y-m-d', strtotime($validated['startDate'] . ' + ' . ($days - 1) . ' days'));
                    }
                }
            }

            $booking = $this->bookingService->create(array_merge($validated, [
                'type' => 'package',
                'endDate' => $endDate,
                'status' => 'pending',
                'metadata' => ['pax' => $validated['pax']],
            ]));

            // Set Carbon locale for date formatting
            $locale = session('locale', 'my');
            $carbonLocale = $locale === 'my' ? 'ms' : $locale;
            $formattedDate = \Carbon\Carbon::parse($validated['startDate'])
                ->locale($carbonLocale)
                ->translatedFormat('d F Y');

            // Construct WhatsApp Message
            $waMessage = __("Halo Sujai Laketoba, saya ingin memesan paket wisata.") . "\n\n" .
                         "*" . __("Detail Pesanan:") . "*\n" .
                         "- " . __("Kode Booking") . ": " . $booking->bookingCode . "\n" .
                         "- " . __("Paket") . ": " . $package->name . "\n" .
                         "- " . __("Nama") . ": " . $validated['customerName'] . "\n" .
                         "- " . __("Email") . ": " . $validated['customerEmail'] . "\n" .
                         "- " . __("WhatsApp") . ": " . $validated['customerPhone'] . "\n" .
                         "- " . __("Tanggal") . ": " . $formattedDate . "\n" .
                         "- " . __("Peserta") . ": " . $validated['pax'] . " " . __("Orang") . "\n";

            if (!empty($validated['notes'])) {
                $waMessage .= "- " . __("Catatan") . ": " . $validated['notes'] . "\n";
            }

            $waMessage .= "\n" . __("Mohon konfirmasinya. Terima kasih!");

            $settings = Setting::where('key', 'cms_tour')->first()?->value ?? [];
            $genSettings = Setting::where('key', 'general')->first()?->value ?? [];
            $waNumber = preg_replace('/[^0-9]/', '', $settings['contact_wa'] ?? $genSettings['whatsapp'] ?? '6281323888207');

            $waUrl = "https://wa.me/{$waNumber}?text=" . urlencode($waMessage);

            return back()->with([
                'success' => __('Booking berhasil dikirim! Kami akan menghubungi Anda segera.'),
                'bookingCode' => $booking->bookingCode,
                'whatsappUrl' => $waUrl
            ]);
        } catch (\Exception $e) {
            Log::error('Booking Submission Error: ' . $e->getMessage(), ['request' => $request->all()]);
            return back()->with('error', __('Terjadi kesalahan saat memproses pesanan. Tim IT kami telah dinotifikasi.'));
        }
    }

    public function cars()
    {
        $siteSettings = $this->getSiteSettings(['general']);
        $cars = Cache::remember('cars_active', 3600, function () {
            return \App\Models\Car::where('status', 'available')->orderBy('sortOrder')->get();
        });
        return view('cars.index', compact('cars', 'siteSettings'));
    }

    public function about()
    {
        $siteSettings = $this->getSiteSettings(['cms_landing', 'cms_tour', 'general']);
        $content = Setting::where('key', 'page_about')->first()?->value ?? [];
        $clients = \App\Models\Client::orderBy('orderPriority')->get();

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
        $waSource = $generalSettings['whatsapp'] ?? config('services.whatsapp.number');
        $waNumber = preg_replace('/[^0-9]/', '', (string) $waSource);

        if ($waNumber === '') {
            return back()->with('error', __('Nomor WhatsApp belum dikonfigurasi.'));
        }

        $message = "Halo Sujai Laketoba, saya ingin meminta penawaran outbound.\n\n"
            . "Company: {$validated['company_name']}\n"
            . "Peserta: {$validated['participants']}\n"
            . "Lokasi: {$validated['location']}\n"
            . "Aktivitas: " . ($validated['activity_type'] ?? '-') . "\n"
            . "Estimasi: {$validated['estimated_date']}\n"
            . "WhatsApp: {$validated['whatsapp']}";

        $waUrl = "https://wa.me/{$waNumber}?text=" . urlencode($message);

        return back()->with([
            'success' => __('Permintaan outbound berhasil dikirim.'),
            'whatsappUrl' => $waUrl,
        ]);
    }
}
