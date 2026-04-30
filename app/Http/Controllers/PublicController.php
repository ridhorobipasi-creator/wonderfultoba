<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicController extends Controller
{
    private function decodeJsonFields($item, $fields)
    {
        if (! $item) {
            return $item;
        }
        foreach ($fields as $field) {
            if (isset($item->$field) && is_string($item->$field)) {
                $item->$field = json_decode($item->$field, true);
            }
        }

        return $item;
    }

    public function index()
    {
        $settingsRaw = DB::table('settings')->where('key', 'landing_page')->first();
        $content = $settingsRaw ? json_decode($settingsRaw->value, true) : [
            'outbound' => [
                'backgroundImage' => '/storage/2026/04/sumatra-panorama.png',
                'title' => "Corporate\nOutbound.",
                'subtitle' => 'Solusi team building & gathering profesional untuk instansi Anda. Tersedia di puluhan hotel premium Sumut.',
                'ctaText' => 'Jelajahi Outbound',
            ],
            'tour' => [
                'backgroundImage' => '/storage/2026/04/lake-toba-premium.png',
                'title' => "Tour &\nTravel.",
                'subtitle' => 'Eksplorasi keindahan Danau Toba, Berastagi, dan alam liar Bukit Lawang dengan paket liburan eksklusif kami.',
                'ctaText' => 'Jelajahi Wisata',
            ],
            'brand' => ['name' => 'Wonderful Toba', 'tagline' => 'Sumatera Utara'],
        ];

        return view('index', compact('content'));
    }

    public function tour()
    {
        // 1. Get Landing Page Settings
        $settingsRaw = DB::table('settings')->where('key', 'tour_landing')->first();
        $settings = $settingsRaw ? json_decode($settingsRaw->value, true) : [];

        // 2. Get Featured Packages
        $packages = DB::table('packages')
            ->where('status', 'active')
            ->where('isOutbound', 0)
            ->take(3)
            ->get()
            ->map(function ($p) {
                $p = $this->decodeJsonFields($p, ['images', 'includes', 'excludes']);
                $p->image = count($p->images) > 0 ? $p->images[0] : null;

                return $p;
            });

        // 3. Get Recent Blogs
        $blogs = DB::table('blogs')
            ->where('status', 'published')
            ->orderBy('createdAt', 'desc')
            ->take(3)
            ->get();

        return view('tour.index', compact('settings', 'packages', 'blogs'));
    }

    public function tourPackages(Request $request)
    {
        $packages = DB::table('packages')
            ->where('status', 'active')
            ->where('isOutbound', 0)
            ->get()
            ->map(function ($p) {
                return $this->decodeJsonFields($p, ['images', 'includes', 'excludes']);
            });

        $cities = DB::table('cities')->get();

        return view('tour.packages', compact('packages', 'cities'));
    }

    public function tourGallery(Request $request)
    {
        $images = DB::table('gallery_images')
            ->where('category', 'tour')
            ->get();

        return view('tour.gallery', compact('images'));
    }

    public function tourBlog(Request $request)
    {
        $posts = DB::table('blogs')
            ->where('status', 'published')
            ->orderBy('createdAt', 'desc')
            ->get();

        return view('tour.blog', compact('posts'));
    }

    public function tourPackageDetail($slug)
    {
        $package = DB::table('packages')
            ->where('slug', $slug)
            ->first();

        if (! $package) {
            return redirect()->route('tour.packages');
        }

        $package = $this->decodeJsonFields($package, [
            'images', 'includes', 'excludes', 'pricingDetails', 'itinerary',
        ]);

        $city = DB::table('cities')->where('id', $package->cityId)->first();

        return view('tour.package-detail', compact('package', 'city'));
    }

    public function tourBlogDetail($id)
    {
        $post = DB::table('blogs')
            ->where('id', $id)
            ->orWhere('slug', $id)
            ->first();

        if (! $post) {
            return redirect()->route('tour.blog');
        }

        $post = $this->decodeJsonFields($post, ['tags']);

        $relatedPosts = DB::table('blogs')
            ->where('id', '!=', $post->id)
            ->where('status', 'published')
            ->take(3)
            ->get();

        return view('tour.blog-detail', compact('post', 'relatedPosts'));
    }

    public function outbound()
    {
        $services = DB::table('outbound_services')->get();
        $videos = DB::table('outbound_videos')->get();
        $locations = DB::table('outbound_locations')->get();
        $clients = DB::table('clients')->get();
        $gallery = DB::table('gallery_images')->where('category', 'outbound')->get();
        $settingsRaw = DB::table('settings')->where('key', 'outbound_landing')->first();
        $settings = $settingsRaw ? json_decode($settingsRaw->value, true) : [];

        return view('outbound.index', compact('services', 'videos', 'locations', 'clients', 'gallery', 'settings'));
    }

    public function outboundPackages(Request $request)
    {
        $packages = DB::table('packages')
            ->where('status', 'active')
            ->where('isOutbound', 1)
            ->get()
            ->map(function ($p) {
                return $this->decodeJsonFields($p, ['images', 'includes', 'excludes']);
            });

        $cities = DB::table('cities')->get();

        return view('outbound.packages', compact('packages', 'cities'));
    }

    public function outboundBlog(Request $request)
    {
        $posts = DB::table('blogs')
            ->where('status', 'published')
            ->where('category', 'Outbound')
            ->orderBy('createdAt', 'desc')
            ->get();

        return view('outbound.blog', compact('posts'));
    }

    public function carRental(Request $request)
    {
        $cars = DB::table('cars')
            ->where('status', 'available')
            ->get()
            ->map(function ($c) {
                return $this->decodeJsonFields($c, ['images', 'features', 'includes']);
            });

        return view('cars.index', compact('cars'));
    }

    public function about()
    {
        return view('pages.about');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }
}
