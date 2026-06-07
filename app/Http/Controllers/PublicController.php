<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Blog;
use App\Models\City;
use App\Models\Client;
use App\Models\OutboundPartner;
use App\Models\Package;
use App\Models\Setting;
use App\Services\BookingService;
use App\Services\OutboundService;
use App\Services\TourService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

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
            $siteSettings = Cache::remember('site_settings_all', 3600, function () {
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
            Log::error('Error loading index page: '.$e->getMessage());

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
            Log::error('Error loading tour index: '.$e->getMessage());

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
            Log::error('Error loading tour packages: '.$e->getMessage());

            return back()->with('error', 'Gagal memuat daftar paket.');
        }
    }

    /**
     * Custom Trip Builder — "Paket Suka-Suka".
     * Pelanggan memilih base package + add-on, kalkulasi harga real-time (Alpine),
     * lalu kirim rincian ke admin via WhatsApp.
     */
    public function customTrip()
    {
        try {
            $siteSettings = [
                'cms_tour' => $this->tourService->getTourSettings(),
                'general' => Setting::where('key', 'general')->first()?->value ?? [],
            ];

            $packages = $this->tourService->getAllPackages()
                ->map(fn ($p) => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'slug' => $p->slug,
                    'price' => (float) $p->price,
                    'duration' => $p->duration,
                    'image' => $p->first_image,
                    'location' => $p->city->name ?? $p->locationTag ?? 'Sumatera Utara',
                ])->values();

            // Add-on dapat dikelola lewat Setting 'tour_addons'; jika kosong pakai default berikut.
            $addons = Setting::where('key', 'tour_addons')->first()?->value;
            if (empty($addons) || ! is_array($addons)) {
                $addons = $this->defaultTripAddons();
            }

            $waNumber = preg_replace('/[^0-9]/', '',
                $siteSettings['cms_tour']['contact_wa']
                ?? $siteSettings['general']['whatsapp']
                ?? '6281323888207');

            return view('tour.custom', compact('packages', 'addons', 'waNumber', 'siteSettings'));
        } catch (\Exception $e) {
            Log::error('Custom Trip Builder Error: '.$e->getMessage());

            return back()->with('error', 'Gagal memuat halaman Buat Paket Suka-Suka.');
        }
    }

    /**
     * Daftar add-on default untuk Custom Trip Builder.
     * `per`: 'trip' = sekali per perjalanan, 'pax' = dikali jumlah peserta.
     */
    private function defaultTripAddons(): array
    {
        return [
            ['id' => 'photographer', 'name' => 'Jasa Fotografer Profesional', 'desc' => 'Dokumentasi foto sepanjang perjalanan', 'price' => 500000, 'per' => 'trip', 'icon' => 'fa-camera'],
            ['id' => 'hotel4', 'name' => 'Upgrade Hotel Bintang 4', 'desc' => 'Naik kelas akomodasi ke hotel bintang 4', 'price' => 1000000, 'per' => 'pax', 'icon' => 'fa-hotel'],
            ['id' => 'bbq', 'name' => 'BBQ Night di Samosir', 'desc' => 'Makan malam BBQ tepi Danau Toba', 'price' => 300000, 'per' => 'pax', 'icon' => 'fa-fire'],
            ['id' => 'drone', 'name' => 'Aerial Drone Videography', 'desc' => 'Video udara sinematik destinasi', 'price' => 750000, 'per' => 'trip', 'icon' => 'fa-helicopter'],
            ['id' => 'guide', 'name' => 'Private Tour Guide', 'desc' => 'Pemandu wisata pribadi berbahasa Inggris', 'price' => 400000, 'per' => 'trip', 'icon' => 'fa-user-tie'],
            ['id' => 'pickup', 'name' => 'Penjemputan Bandara Kualanamu', 'desc' => 'Antar-jemput bandara PP', 'price' => 350000, 'per' => 'trip', 'icon' => 'fa-plane-arrival'],
        ];
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
            if (! $package) {
                return redirect()->route('tour.packages')->with('error', 'Paket tidak ditemukan.');
            }

            // Increment Views
            $package->increment('views_count');

            $city = City::find($package->cityId);

            return view('tour.package-detail', compact('package', 'city', 'siteSettings'));
        } catch (\Exception $e) {
            Log::error("Error loading package detail ($slug): ".$e->getMessage());

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
            if (! $post) {
                return redirect()->route('tour.blog');
            }

            // Increment Views
            $post->increment('views_count');

            $relatedPosts = $this->tourService->getRelatedBlogs($post->id);

            return view('tour.blog-detail', compact('post', 'relatedPosts', 'siteSettings'));
        } catch (\Exception $e) {
            Log::error("Error loading blog detail ($slug): ".$e->getMessage());

            return redirect()->route('tour.blog');
        }
    }

    public function submitBooking(StoreBookingRequest $request)
    {
        try {
            $validated = $request->validated();
            $package = $this->tourService->getPackageBySlug($request->slug ?? '');

            if (! $package) {
                $package = Package::find($validated['packageId']);
            }

            if ($package && $package->isOutbound) {
                return back()->with('error', 'Paket outbound hanya dapat dipesan melalui WhatsApp.');
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
                'metadata' => ['pax' => $validated['pax']],
            ]));

            // Construct WhatsApp Message
            $waMessage = "Halo Wonderful Toba, saya ingin memesan paket wisata.\n\n".
                         "*Detail Pesanan:*\n".
                         '- Kode Booking: '.$booking->bookingCode."\n".
                         '- Paket: '.$package->name."\n".
                         '- Nama: '.$validated['customerName']."\n".
                         '- Email: '.$validated['customerEmail']."\n".
                         '- WhatsApp: '.$validated['customerPhone']."\n".
                         '- Tanggal: '.date('d F Y', strtotime($validated['startDate']))."\n".
                         '- Peserta: '.$validated['pax']." Orang\n";

            if (! empty($validated['notes'])) {
                $waMessage .= '- Catatan: '.$validated['notes']."\n";
            }

            $waMessage .= "\nMohon konfirmasinya. Terima kasih!";

            $settings = Setting::where('key', 'cms_tour')->first()?->value ?? [];
            $genSettings = Setting::where('key', 'general')->first()?->value ?? [];
            $waNumber = preg_replace('/[^0-9]/', '', $settings['contact_wa'] ?? $genSettings['whatsapp'] ?? '6281323888207');

            $waUrl = "https://wa.me/{$waNumber}?text=".urlencode($waMessage);

            return back()->with([
                'success' => 'Booking berhasil dikirim! Kami akan menghubungi Anda segera.',
                'bookingCode' => $booking->bookingCode,
                'whatsappUrl' => $waUrl,
            ]);
        } catch (\Exception $e) {
            Log::error('Booking Submission Error: '.$e->getMessage(), ['request' => $request->all()]);

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
            $partners = OutboundPartner::where('isActive', true)->orderBy('orderPriority')->get();
            $gallery = $this->outboundService->getGallery();
            $featuredPackages = $this->outboundService->getFeaturedPackages(3);

            return view('outbound.index', compact('services', 'videos', 'locations', 'clients', 'partners', 'gallery', 'settings', 'featuredPackages', 'siteSettings'));
        } catch (\Exception $e) {
            Log::error('Outbound Page Error: '.$e->getMessage());

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
            Log::error('Outbound Packages Error: '.$e->getMessage());

            return back()->with('error', 'Gagal memuat daftar paket outbound.');
        }
    }

    public function outboundBlog()
    {
        $siteSettings = [
            'cms_outbound' => $this->outboundService->getOutboundSettings(),
            'general' => Setting::where('key', 'general')->first()?->value ?? [],
        ];
        $posts = Blog::where('status', 'published')->where('category', 'Outbound')->latest('createdAt')->get();

        return view('outbound.blog', compact('posts', 'siteSettings'));
    }

    public function about()
    {
        $siteSettings = [
            'cms_landing' => Setting::where('key', 'cms_landing')->first()?->value ?? [],
            'general' => Setting::where('key', 'general')->first()?->value ?? [],
        ];
        $content = Setting::where('key', 'page_about')->first()?->value ?? [];
        $clients = Client::orderBy('orderPriority')->get();

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

    public function submitQuote(Request $request, OutboundService $outboundService)
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
                'whatsappUrl' => $waUrl,
            ]);
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Quote Submission Error: '.$e->getMessage());

            return back()->with('error', 'Gagal mengirim permintaan penawaran.');
        }
    }
}
