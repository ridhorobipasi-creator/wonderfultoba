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
    protected TourService $tourService;
    protected BookingService $bookingService;

    public function __construct(TourService $tourService, BookingService $bookingService)
    {
        $this->tourService = $tourService;
        $this->bookingService = $bookingService;
    }

    /**
     * Shared site settings loader
     */
    private function getSiteSettings(array $extraKeys = []): array
    {
        $base = Cache::remember('site_settings_general', 3600, fn() => [
            'general' => Setting::where('key', 'general')->first()?->value ?? [],
        ]);

        $extra = [];
        foreach ($extraKeys as $key) {
            $extra[$key] = Cache::remember("site_settings_{$key}", 3600,
                fn() => Setting::where('key', $key)->first()?->value ?? []
            );
        }

        return array_merge($base, $extra);
    }

    /**
     * Homepage — Full Tour Landing with Slider
     */
    public function index()
    {
        try {
            $siteSettings = $this->getSiteSettings(['cms_landing', 'cms_tour']);
            $featuredPackages = $this->tourService->getFeaturedPackages(6);
            $blogs           = $this->tourService->getBlogs(3);
            $gallery         = \App\Models\GalleryImage::latest()->take(9)->get();
            $content         = $siteSettings['cms_landing'];
            
            // Slider data from cms_landing
            $settings = $content;
            $packages = $featuredPackages;

            return view('index', compact('content', 'siteSettings', 'featuredPackages', 'blogs', 'settings', 'packages', 'gallery'));
        } catch (\Exception $e) {
            Log::error('Error loading index page: ' . $e->getMessage());
            return response()->view('errors.500', [], 500);
        }
    }


    /**
     * Tour packages listing
     */
    public function tourPackages(Request $request)
    {
        try {
            $siteSettings = $this->getSiteSettings(['cms_tour']);
            $packages = $this->tourService->getAllPackages();
            $cities   = $this->tourService->getCities();

            return view('tour.packages', compact('packages', 'cities', 'siteSettings'));
        } catch (\Exception $e) {
            Log::error('Error loading tour packages: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat daftar paket.');
        }
    }

    /**
     * Tour gallery
     */
    public function tourGallery()
    {
        $siteSettings = $this->getSiteSettings(['cms_tour']);
        $images = $this->tourService->getGallery();

        return view('tour.gallery', compact('images', 'siteSettings'));
    }

    /**
     * Tour blog listing
     */
    public function tourBlog()
    {
        $siteSettings = $this->getSiteSettings(['cms_tour']);
        $posts = $this->tourService->getBlogs();

        return view('tour.blog', compact('posts', 'siteSettings'));
    }

    /**
     * Tour package detail
     */
    public function tourPackageDetail(string $slug)
    {
        try {
            $siteSettings = $this->getSiteSettings(['cms_tour']);
            $package = $this->tourService->getPackageBySlug($slug);

            if (!$package) {
                return redirect()->route('tour.packages')->with('error', 'Paket tidak ditemukan.');
            }

            $package->increment('views_count');
            $city = City::find($package->cityId);

            // Fetch related packages (same city, excluding current)
            $relatedPackages = \App\Models\Package::where('cityId', $package->cityId)
                ->where('id', '!=', $package->id)
                ->where('status', 'active')
                ->where('isOutbound', false)
                ->take(3)
                ->get();

            return view('tour.package-detail', compact('package', 'city', 'siteSettings', 'relatedPackages'));
        } catch (\Exception $e) {
            Log::error("Error loading package detail ($slug): " . $e->getMessage());
            return redirect()->route('tour.packages')->with('error', 'Gagal memuat detail paket.');
        }
    }

    /**
     * Tour blog detail
     */
    public function tourBlogDetail(string $slug)
    {
        try {
            $siteSettings = $this->getSiteSettings(['cms_tour']);
            $post = $this->tourService->getBlogPost($slug);

            if (!$post) {
                return redirect()->route('tour.blog');
            }

            $post->increment('views_count');
            $relatedPosts = $this->tourService->getRelatedBlogs($post->id);

            return view('tour.blog-detail', compact('post', 'relatedPosts', 'siteSettings'));
        } catch (\Exception $e) {
            Log::error("Error loading blog detail ($slug): " . $e->getMessage());
            return redirect()->route('tour.blog');
        }
    }

    /**
     * Submit booking from tour package detail page
     */
    public function submitBooking(StoreBookingRequest $request)
    {
        try {
            $validated = $request->validated();
            $package = $this->tourService->getPackageBySlug($request->slug ?? '')
                ?? \App\Models\Package::find($validated['packageId'] ?? null);

            $endDate = $validated['startDate'];
            if ($package?->duration) {
                if (preg_match('/(\d+)\s*(Hari|Days|D|H)/i', $package->duration, $matches)) {
                    $days = (int) $matches[1];
                    if ($days > 1) {
                        $endDate = date('Y-m-d', strtotime($validated['startDate'] . ' + ' . ($days - 1) . ' days'));
                    }
                }
            }

            $booking = $this->bookingService->create(array_merge($validated, [
                'type'     => 'package',
                'endDate'  => $endDate,
                'status'   => 'pending',
                'metadata' => ['pax' => $validated['pax']],
            ]));

            return back()->with([
                'success'     => 'Booking berhasil dikirim! Kami akan menghubungi Anda segera.',
                'bookingCode' => $booking->bookingCode,
            ]);
        } catch (\Exception $e) {
            Log::error('Booking Submission Error: ' . $e->getMessage(), ['request' => $request->all()]);
            return back()->with('error', 'Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.');
        }
    }

    /**
     * Car rental page
     */
    public function cars()
    {
        $siteSettings = $this->getSiteSettings();
        $cars = Cache::remember('cars_active', 3600, fn() =>
            \App\Models\Car::where('status', 'active')->orderBy('sortOrder')->get()
        );

        return view('cars.index', compact('cars', 'siteSettings'));
    }

    /**
     * About page
     */
    public function about()
    {
        $siteSettings = $this->getSiteSettings(['cms_landing']);
        $content  = Setting::where('key', 'page_about')->first()?->value ?? [];
        $clients  = \App\Models\Client::orderBy('orderPriority')->get();

        return view('pages.about', compact('content', 'siteSettings', 'clients'));
    }

    /**
     * Contact page
     */
    public function contact()
    {
        $siteSettings = $this->getSiteSettings(['general']);
        return view('pages.contact', compact('siteSettings'));
    }

    /**
     * Terms & Conditions
     */
    public function terms()
    {
        $siteSettings = $this->getSiteSettings(['cms_landing']);
        $content = Setting::where('key', 'page_terms')->first()?->value ?? [];

        return view('pages.terms', compact('content', 'siteSettings'));
    }

    /**
     * Privacy Policy
     */
    public function privacy()
    {
        $siteSettings = $this->getSiteSettings(['cms_landing']);
        $content = Setting::where('key', 'page_privacy')->first()?->value ?? [];

        return view('pages.privacy', compact('content', 'siteSettings'));
    }
    /**
     * Search global
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        $siteSettings = $this->getSiteSettings(['cms_tour']);
        
        if (empty($query)) {
            return redirect()->route('tour.packages');
        }

        $results = $this->tourService->search($query);
        $packages = $results['packages'];
        $blogs = $results['blogs'];

        return view('tour.search', compact('packages', 'blogs', 'query', 'siteSettings'));
    }
}
