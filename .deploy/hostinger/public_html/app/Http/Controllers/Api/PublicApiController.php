<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Blog;
use App\Models\Booking;
use App\Models\City;
use App\Models\Client;
use App\Models\GalleryImage;
use App\Models\Package;
use App\Models\Setting;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PublicApiController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            $user  = auth()->user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'token' => $token,
                'user'  => [
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'email' => $user->email,
                    'role'  => strtoupper($user->role),
                ],
            ]);
        }

        return response()->json(['message' => 'Email atau password salah.'], 401);
    }

    public function getMe(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => strtoupper($user->role),
        ]);
    }

    public function getBlogs()
    {
        $blogs = Blog::where('status', 'published')->latest('createdAt')->get()->map(function ($b) {
            $b->is_published = true;
            $b->author = ['name' => $b->author];
            return $b;
        });

        return response()->json($blogs);
    }

    public function getPackages()
    {
        $packages = Package::where('isOutbound', false)
            ->where('status', 'active')
            ->with(['city'])
            ->get()
            ->map(function ($p) {
                $imgs = is_array($p->images) ? $p->images : json_decode($p->images ?? '[]', true);
                $p->image = $imgs[0] ?? null;
                return $p;
            });

        return response()->json($packages);
    }

    public function getBookings()
    {
        $bookings = Booking::latest('createdAt')->get()->map(function ($b) {
            return [
                'id'            => 'BK-' . str_pad($b->id, 3, '0', STR_PAD_LEFT),
                'customer_name' => $b->customerName,
                'tour_name'     => 'Tour Package',
                'status'        => ucfirst($b->status),
                'total_price'   => $b->totalPrice,
                'booking_date'  => $b->startDate,
            ];
        });

        return response()->json($bookings);
    }

    public function submitBooking(StoreBookingRequest $request)
    {
        $validated = $request->validated();

        try {
            $bookingService = app(BookingService::class);
            $booking        = $bookingService->create(array_merge($validated, [
                'type'     => 'package',
                'endDate'  => $validated['startDate'],
                'status'   => 'pending',
                'metadata' => ['pax' => $validated['pax']],
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dikirim! Tim kami akan segera menghubungi Anda.',
                'data'    => $booking,
            ], 201);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('API Booking Error: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan sistem saat memproses booking.'], 500);
        }
    }

    public function getClients()
    {
        return response()->json(Client::orderBy('orderPriority')->get());
    }

    public function getGallery()
    {
        return response()->json(
            GalleryImage::where('isActive', true)
                ->orderBy('orderPriority')
                ->get()
        );
    }

    public function getOutboundServices()
    {
        if (!Schema::hasTable('outbound_services')) {
            return response()->json([]);
        }

        return response()->json(
            DB::table('outbound_services')
                ->where('isActive', true)
                ->orderBy('orderPriority')
                ->get()
        );
    }

    public function getCities()
    {
        $cities = City::all()->map(function ($c) {
            $c->province = $c->region;
            return $c;
        });

        return response()->json($cities);
    }

    public function getSettings(Request $request)
    {
        $key = $request->query('key');

        if ($key) {
            $setting = Setting::where('key', $key)->first();
            return response()->json($setting?->value);
        }

        $settings = Setting::all();
        $result   = [];
        foreach ($settings as $s) {
            $result[$s->key] = $s->value;
        }

        return response()->json($result);
    }

    public function getStats()
    {
        return response()->json([
            'packages'     => Package::where('isOutbound', false)->where('status', 'active')->count(),
            'totalBookings'=> Booking::count(),
            'happyClients' => Booking::where('status', 'confirmed')->count() + 1540,
        ]);
    }

    public function getDashboard()
    {
        return response()->json([
            'totalBookings'   => Booking::count(),
            'pendingBookings' => Booking::where('status', 'pending')->count(),
            'totalRevenue'    => Booking::where('status', 'confirmed')->sum('totalPrice'),
            'tourPackages'    => Package::where('isOutbound', false)->count(),
            'recentBookings'  => Booking::latest('createdAt')->take(5)->get()->map(function ($b) {
                return [
                    'customer_name' => $b->customerName,
                    'start_date'    => $b->startDate,
                    'total_price'   => $b->totalPrice,
                    'status'        => $b->status,
                    'type'          => 'Tour',
                ];
            }),
        ]);
    }
}
