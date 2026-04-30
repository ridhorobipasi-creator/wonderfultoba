<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicApiController extends Controller
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
                    'role' => strtoupper($user->role), // Match mockUser "ADMIN"
                ],
            ]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
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
        $blogs = DB::table('blogs')->get()->map(function ($b) {
            $b->is_published = $b->status === 'published';
            $b->author = ['name' => $b->author]; // Mock data expects object

            return $b;
        });

        return response()->json($blogs);
    }

    public function getPackages()
    {
        $packages = DB::table('packages')->get()->map(function ($p) {
            $p = $this->decodeJsonFields($p, ['images', 'includes', 'excludes', 'pricingDetails', 'itinerary', 'translations']);
            $p->is_published = $p->status === 'active';
            $p->isOutbound = (bool) $p->isOutbound;
            $p->image = count($p->images) > 0 ? $p->images[0] : null; // Provide default image

            return $p;
        });

        return response()->json($packages);
    }

    public function getBookings()
    {
        $bookings = DB::table('bookings')->get()->map(function ($b) {
            return [
                'id' => 'BK-'.str_pad($b->id, 3, '0', STR_PAD_LEFT),
                'customer_name' => $b->customerName,
                'tour_name' => $b->type === 'package' ? 'Tour Package' : 'Car Rental',
                'status' => ucfirst($b->status),
                'total_price' => $b->totalPrice,
                'booking_date' => $b->startDate,
            ];
        });

        return response()->json($bookings);
    }

    public function getCars()
    {
        $cars = DB::table('cars')->get()->map(function ($c) {
            $c = $this->decodeJsonFields($c, ['images', 'features', 'includes', 'pricingDetails', 'translations']);
            $c->is_available = $c->status === 'available';
            $c->image = count($c->images) > 0 ? $c->images[0] : null;

            return $c;
        });

        return response()->json($cars);
    }

    public function getOutboundServices()
    {
        return response()->json(DB::table('outbound_services')->get());
    }

    public function getOutboundVideos()
    {
        return response()->json(DB::table('outbound_videos')->get());
    }

    public function getOutboundLocations()
    {
        return response()->json(DB::table('outbound_locations')->get());
    }

    public function getClients()
    {
        return response()->json(DB::table('clients')->get());
    }

    public function getGallery()
    {
        $gallery = DB::table('gallery_images')->get()->map(function ($g) {
            $g = $this->decodeJsonFields($g, ['tags']);

            return $g;
        });

        return response()->json($gallery);
    }

    public function getCities()
    {
        $cities = DB::table('cities')->get()->map(function ($c) {
            $c->province = $c->region; // Mock data uses province

            return $c;
        });

        return response()->json($cities);
    }

    public function getPackageTiers()
    {
        $tiers = DB::table('package_tiers')->get()->map(function ($t) {
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
            $setting = DB::table('settings')->where('key', $key)->first();

            return response()->json($setting ? json_decode($setting->value) : null);
        }

        // Return all combined (mockData format)
        $settings = DB::table('settings')->get();
        $result = [];
        foreach ($settings as $s) {
            $result[$s->key] = json_decode($s->value, true);
        }

        return response()->json($result);
    }

    public function getStats()
    {
        return response()->json([
            'packages' => DB::table('packages')->count(),
            'happyClients' => 1540, // Static for now as in mock
        ]);
    }

    public function getDashboard()
    {
        return response()->json([
            'totalBookings' => DB::table('bookings')->count(),
            'pendingBookings' => DB::table('bookings')->where('status', 'pending')->count(),
            'totalRevenue' => DB::table('bookings')->sum('totalPrice'),
            'tourPackages' => DB::table('packages')->where('isOutbound', 0)->count(),
            'outboundPackages' => DB::table('packages')->where('isOutbound', 1)->count(),
            'recentBookings' => DB::table('bookings')->orderBy('createdAt', 'desc')->take(5)->get()->map(function ($b) {
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
