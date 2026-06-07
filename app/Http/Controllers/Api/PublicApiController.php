<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Blog;
use App\Models\Booking;
use App\Models\City;
use App\Models\Client;
use App\Models\GalleryImage;
use App\Models\OutboundLocation;
use App\Models\OutboundService;
use App\Models\OutboundVideo;
use App\Models\Package;
use App\Models\PackageTier;
use App\Models\Setting;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PublicApiController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            $user = auth()->user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => strtoupper($user->role),
                ],
            ]);
        }

        return response()->json(['message' => 'Email atau password salah.'], 401);
    }

    public function getMe(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => strtoupper($user->role),
        ]);
    }

    public function getBlogs()
    {
        $blogs = Blog::all()->map(function ($b) {
            $b->is_published = $b->status === 'published';
            $b->author = ['name' => $b->author];

            return $b;
        });

        return response()->json($blogs);
    }

    public function getPackages()
    {
        $packages = Package::with(['packageImages', 'city'])->get()->map(function ($p) {
            $p->is_published = $p->status === 'active';
            $p->isOutbound = (bool) $p->isOutbound;
            $p->image = $p->packageImages->first()?->image_path;

            return $p;
        });

        return response()->json($packages);
    }

    public function getBookings()
    {
        $bookings = Booking::all()->map(function ($b) {
            return [
                'id' => 'BK-'.str_pad($b->id, 3, '0', STR_PAD_LEFT),
                'customer_name' => $b->customerName,
                'tour_name' => $b->type === 'package' ? 'Tour Package' : 'Custom Service',
                'status' => ucfirst($b->status),
                'total_price' => $b->totalPrice,
                'booking_date' => $b->startDate,
            ];
        });

        return response()->json($bookings);
    }

    public function submitBooking(StoreBookingRequest $request)
    {
        $validated = $request->validated();
        $package = Package::find($validated['packageId']);

        if ($package && $package->isOutbound) {
            return response()->json(['error' => 'Pemesanan paket outbound hanya dapat dilakukan melalui WhatsApp.'], 400);
        }

        try {
            $bookingService = app(BookingService::class);

            $bookingData = [
                'type' => 'package',
                'packageId' => $validated['packageId'],
                'customerName' => $validated['customerName'],
                'customerEmail' => $validated['customerEmail'],
                'customerPhone' => $validated['customerPhone'],
                'startDate' => $validated['startDate'],
                'endDate' => $validated['startDate'],
                'notes' => $validated['notes'],
                'status' => 'pending',
                'metadata' => [
                    'pax' => $validated['pax'],
                ],
            ];

            $booking = $bookingService->create($bookingData);

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dikirim!',
                'data' => $booking,
            ], 201);
        } catch (\Exception $e) {
            Log::error('API Booking Error: '.$e->getMessage(), ['request' => $request->all()]);

            return response()->json(['error' => 'Maaf, terjadi kesalahan sistem saat memproses booking Anda.'], 500);
        }
    }

    public function getOutboundServices()
    {
        return response()->json(OutboundService::all());
    }

    public function getOutboundVideos()
    {
        return response()->json(OutboundVideo::all());
    }

    public function getOutboundLocations()
    {
        return response()->json(OutboundLocation::all());
    }

    public function getClients()
    {
        return response()->json(Client::all());
    }

    public function getGallery()
    {
        return response()->json(GalleryImage::all());
    }

    public function getCities()
    {
        $cities = City::all()->map(function ($c) {
            $c->province = $c->region;

            return $c;
        });

        return response()->json($cities);
    }

    public function getPackageTiers()
    {
        $tiers = PackageTier::all()->map(function ($t) {
            return [
                'id' => $t->id,
                'name' => $t->tierName,
                'description' => $t->tagline,
            ];
        });

        return response()->json($tiers);
    }

    public function getSettings(Request $request)
    {
        $key = $request->query('key');

        if ($key) {
            $setting = Setting::where('key', $key)->first();

            return response()->json($setting ? $setting->value : null);
        }

        $settings = Setting::all();
        $result = [];
        foreach ($settings as $s) {
            $result[$s->key] = $s->value;
        }

        return response()->json($result);
    }

    public function getStats()
    {
        return response()->json([
            'packages' => Package::count(),
            'happyClients' => 1540,
        ]);
    }

    public function getDashboard()
    {
        return response()->json([
            'totalBookings' => Booking::count(),
            'pendingBookings' => Booking::where('status', 'pending')->count(),
            'totalRevenue' => Booking::sum('totalPrice'),
            'tourPackages' => Package::where('isOutbound', false)->count(),
            'outboundPackages' => Package::where('isOutbound', true)->count(),
            'recentBookings' => Booking::orderBy('createdAt', 'desc')->take(5)->get()->map(function ($b) {
                return [
                    'customer_name' => $b->customerName,
                    'start_date' => $b->startDate,
                    'total_price' => $b->totalPrice,
                    'status' => $b->status,
                    'type' => 'Tour',
                ];
            }),
            'chartData' => [
                ['date' => '2026-04-15', 'revenue' => 15000000],
                ['date' => '2026-04-16', 'revenue' => 25000000],
                ['date' => '2026-04-17', 'revenue' => 10000000],
                ['date' => '2026-04-18', 'revenue' => 35000000],
                ['date' => '2026-04-19', 'revenue' => 45000000],
                ['date' => '2026-04-20', 'revenue' => 30000000],
                ['date' => '2026-04-21', 'revenue' => 55000000],
            ],
        ]);
    }
}
