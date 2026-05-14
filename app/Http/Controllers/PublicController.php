<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Setting;
use App\Models\City;
use App\Services\BookingService;
use App\Services\OutboundService;
use App\Services\TourService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class PublicController extends Controller
{
    protected $tourService;
    protected $outboundService;
    protected $bookingService;

    public function __construct(
        TourService $tourService,
        OutboundService $outboundService,
        BookingService $bookingService
    ) {
        $this->tourService = $tourService;
        $this->outboundService = $outboundService;
        $this->bookingService = $bookingService;
    }

    /**
     * Homepage / Split Landing Page
     */
    public function index()
    {
        try {
            $siteSettings = Cache::remember('site_settings_all', 3600, function() {
                return [
                    'cms_landing' => Setting::where('key', 'cms_landing')->first()?->value ?? [],
                    'cms_tour' => Setting::where('key', 'cms_tour')->first()?->value ?? [],
                    'cms_outbound' => Setting::where('key', 'cms_outbound')->first()?->value ?? [],
                    'general' => Setting::where('key', 'general')->first()?->value ?? [],
                ];
            });
            
            $content = $siteSettings['cms_landing'];
            
            return view('index', compact('content', 'siteSettings'));
        } catch (\Exception $e) {
            Log::error('Error loading index page: ' . $e->getMessage());
            return view('errors.500'); 
        }
    }

    /**
     * Tour & Travel Main Page
     */
    public function tour()
    {
        try {
            $siteSettings = [
                'cms_tour' => $this->tourService->getTourSettings(),
                'general' => Setting::where('key', 'general')->first()?->value ?? [],
            ];
            $settings = $siteSettings['cms_tour'];
            $packages = $this->tourService->getFeaturedPackages();
            $blogs = $this->tourService->getBlogs(3);

            return view('tour.index', compact('settings', 'packages', 'blogs', 'siteSettings'));
        } catch (\Exception $e) {
            Log::error('Error loading tour index: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat data tour. Silakan coba beberapa saat lagi.');
        }
    }

    public function tourPackages(Request $request)
    {
        try {
            $siteSettings = [
                'cms_tour' => $this->tourService->getTourSettings(),
                'general' => Setting::where('key', 'general')->first()?->value ?? [],
            ];
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
        $siteSettings = [
            'cms_tour' => $this->tourService->getTourSettings(),
            'general' => Setting::where('key', 'general')->first()?->value ?? [],
        ];
        $images = $this->tourService->getGallery();
        return view('tour.gallery', compact('images', 'siteSettings'));
    }

    public function tourBlog()
    {
        $siteSettings = [
            'cms_tour' => $this->tourService->getTourSettings(),
            'general' => Setting::where('key', 'general')->first()?->value ?? [],
        ];
        $posts = $this->tourService->getBlogs();
        return view('tour.blog', compact('posts', 'siteSettings'));
    }

    public function tourPackageDetail($slug)
    {
        try {
            $siteSettings = [
                'cms_tour' => $this->tourService->getTourSettings(),
                'general' => Setting::where('key', 'general')->first()?->value ?? [],
            ];
            $package = $this->tourService->getPackageBySlug($slug);
            if (!$package) {
                return redirect()->route('tour.packages')->with('error', 'Paket tidak ditemukan.');
            }

            // Increment Views
            $package->increment('views_count');

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
            $siteSettings = [
                'cms_tour' => $this->tourService->getTourSettings(),
                'general' => Setting::where('key', 'general')->first()?->value ?? [],
            ];
            $post = $this->tourService->getBlogPost($slug);
            if (!$post) {
                return redirect()->route('tour.blog');
            }

            // Increment Views
            $post->increment('views_count');

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

            if ($package && $package->isOutbound) {
                return back()->with('error', 'Paket outbound hanya dapat dipesan melalui WhatsApp.');
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

            // Construct WhatsApp Message
            $waMessage = "Halo Wonderful Toba, saya ingin memesan paket wisata.\n\n" .
                         "*Detail Pesanan:*\n" .
                         "- Kode Booking: " . $booking->bookingCode . "\n" .
                         "- Paket: " . $package->name . "\n" .
                         "- Nama: " . $validated['customerName'] . "\n" .
                         "- Email: " . $validated['customerEmail'] . "\n" .
                         "- WhatsApp: " . $validated['customerPhone'] . "\n" .
                         "- Tanggal: " . date('d F Y', strtotime($validated['startDate'])) . "\n" .
                         "- Peserta: " . $validated['pax'] . " Orang\n";
            
            if (!empty($validated['notes'])) {
                $waMessage .= "- Catatan: " . $validated['notes'] . "\n";
            }

            $waMessage .= "\nMohon konfirmasinya. Terima kasih!";

            $settings = Setting::where('key', 'cms_tour')->first()?->value ?? [];
            $genSettings = Setting::where('key', 'general')->first()?->value ?? [];
            $waNumber = preg_replace('/[^0-9]/', '', $settings['contact_wa'] ?? $genSettings['whatsapp'] ?? '6281323888207');
            
            $waUrl = "https://wa.me/{$waNumber}?text=" . urlencode($waMessage);

            return back()->with([
                'success' => 'Booking berhasil dikirim! Kami akan menghubungi Anda segera.',
                'bookingCode' => $booking->bookingCode,
                'whatsappUrl' => $waUrl
            ]);
        } catch (\Exception $e) {
            Log::error('Booking Submission Error: ' . $e->getMessage(), ['request' => $request->all()]);
            return back()->with('error', 'Terjadi kesalahan saat memproses pesanan. Tim IT kami telah dinotifikasi.');
        }
    }

    public function outbound()
    {
        try {
            $siteSettings = [
                'cms_outbound' => $this->outboundService->getOutboundSettings(),
                'cms_landing' => Setting::where('key', 'cms_landing')->first()?->value ?? [],
                'cms_tour' => Setting::where('key', 'cms_tour')->first()?->value ?? [],
                'general' => Setting::where('key', 'general')->first()?->value ?? [],
            ];
            $settings = $siteSettings['cms_outbound'];
            $services = $this->outboundService->getServices();
            $videos = $this->outboundService->getVideos();
            $locations = $this->outboundService->getLocations();
            $clients = $this->outboundService->getClients();
            $gallery = $this->outboundService->getGallery();
            $featuredPackages = $this->outboundService->getFeaturedPackages(3);

            return view('outbound.index', compact('services', 'videos', 'locations', 'clients', 'gallery', 'settings', 'featuredPackages', 'siteSettings'));
        } catch (\Exception $e) {
            Log::error('Outbound Page Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat halaman Outbound.');
        }
    }

    public function outboundPackages()
    {
        try {
            $siteSettings = [
                'cms_outbound' => $this->outboundService->getOutboundSettings(),
                'general' => Setting::where('key', 'general')->first()?->value ?? [],
            ];
            $packages = $this->outboundService->getPackages();
            $cities = $this->tourService->getCities();
            $tiers = $this->outboundService->getTiers();

            return view('outbound.packages', compact('packages', 'cities', 'tiers', 'siteSettings'));
        } catch (\Exception $e) {
            Log::error('Outbound Packages Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat daftar paket outbound.');
        }
    }

    public function outboundBlog()
    {
        $siteSettings = [
            'cms_outbound' => $this->outboundService->getOutboundSettings(),
            'general' => Setting::where('key', 'general')->first()?->value ?? [],
        ];
        $posts = \App\Models\Blog::where('status', 'published')->where('category', 'Outbound')->latest('createdAt')->get();
        return view('outbound.blog', compact('posts', 'siteSettings'));
    }

    public function cars()
    {
        $siteSettings = Cache::remember('site_settings_minimal', 3600, function() {
            return [
                'general' => Setting::where('key', 'general')->first()?->value ?? [],
            ];
        });
        $cars = Cache::remember('cars_active', 3600, function() {
            return \App\Models\Car::where('status', 'active')->orderBy('sortOrder')->get();
        });
        return view('cars.index', compact('cars', 'siteSettings'));
    }

    public function about()
    {
        $siteSettings = [
            'cms_landing' => Setting::where('key', 'cms_landing')->first()?->value ?? [],
            'general' => Setting::where('key', 'general')->first()?->value ?? [],
        ];
        $content = Setting::where('key', 'page_about')->first()?->value ?? [];
        $clients = \App\Models\Client::orderBy('orderPriority')->get();
        return view('pages.about', compact('content', 'siteSettings', 'clients'));
    }

    public function terms()
    {
        $siteSettings = [
            'cms_landing' => Setting::where('key', 'cms_landing')->first()?->value ?? [],
            'general' => Setting::where('key', 'general')->first()?->value ?? [],
        ];
        $content = Setting::where('key', 'page_terms')->first()?->value ?? [];
        return view('pages.terms', compact('content', 'siteSettings'));
    }

    public function privacy()
    {
        $siteSettings = [
            'cms_landing' => Setting::where('key', 'cms_landing')->first()?->value ?? [],
            'general' => Setting::where('key', 'general')->first()?->value ?? [],
        ];
        $content = Setting::where('key', 'page_privacy')->first()?->value ?? [];
        return view('pages.privacy', compact('content', 'siteSettings'));
    }

    public function submitQuote(Request $request, \App\Services\OutboundService $outboundService)
    {
        try {
            $validated = $request->validate([
                'company_name' => 'required|string|max:255',
                'participants' => 'required|string|max:255',
                'location' => 'required|string|max:255',
                'activity_type' => 'required|string|max:255',
                'estimated_date' => 'required|date',
                'whatsapp' => 'required|string|max:255',
            ]);

            $waUrl = $outboundService->processQuoteRequest($validated);

            return back()->with([
                'success' => 'Permintaan penawaran berhasil dikirim! Klik tombol di bawah untuk konfirmasi via WhatsApp.',
                'whatsappUrl' => $waUrl
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Quote Submission Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengirim permintaan penawaran.');
        }
    }
}

