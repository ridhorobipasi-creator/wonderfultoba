<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $json = file_get_contents(database_path('seeders/data.json'));
        $data = json_decode($json, true);

        // 1. CITIES
        if (isset($data['cities'])) {
            foreach ($data['cities'] as $city) {
                DB::table('cities')->insert([
                    'id' => $city['id'],
                    'name' => $city['name'],
                    'slug' => strtolower(str_replace(' ', '-', $city['name'])),
                    'region' => $city['province'] ?? null,
                ]);
            }
        }

        // 2. USERS
        if (isset($data['users'])) {
            foreach ($data['users'] as $user) {
                DB::table('users')->insert([
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'password' => Hash::make('password123'),
                    'role' => strtolower($user['role']),
                ]);
            }
        }

        // 3. PACKAGES
        if (isset($data['packages'])) {
            foreach ($data['packages'] as $pkg) {
                DB::table('packages')->insert([
                    'id' => $pkg['id'],
                    'slug' => $pkg['slug'],
                    'name' => $pkg['name'],
                    'shortDescription' => $pkg['shortDescription'] ?? null,
                    'description' => $pkg['description'] ?? '',
                    'locationTag' => $pkg['locationTag'] ?? null,
                    'price' => $pkg['price'] ?? 0,
                    'duration' => $pkg['duration'] ?? '',
                    'images' => json_encode($pkg['images'] ?? []),
                    'includes' => json_encode($pkg['includes'] ?? []),
                    'excludes' => json_encode($pkg['excludes'] ?? []),
                    'itinerary' => isset($pkg['itinerary']) ? json_encode($pkg['itinerary']) : null,
                    'status' => $pkg['status'] ?? 'active',
                    'isOutbound' => $pkg['isOutbound'] ? 1 : 0,
                    'cityId' => $pkg['cityId'] ?? null,
                    'createdAt' => date('Y-m-d H:i:s', strtotime($pkg['createdAt'] ?? 'now')),
                    'updatedAt' => date('Y-m-d H:i:s', strtotime($pkg['createdAt'] ?? 'now')),
                ]);
            }
        }

        // 4. CARS
        if (isset($data['cars'])) {
            foreach ($data['cars'] as $car) {
                DB::table('cars')->insert([
                    'id' => $car['id'],
                    'name' => $car['name'],
                    'type' => $car['type'] ?? 'Standard',
                    'capacity' => 4,
                    'transmission' => 'Automatic',
                    'fuel' => 'Petrol',
                    'price' => $car['price'] ?? 0,
                    'images' => json_encode(isset($car['image']) ? [$car['image']] : []),
                    'status' => ($car['is_available'] ?? true) ? 'available' : 'unavailable',
                ]);
            }
        }

        // 5. BOOKINGS
        if (isset($data['bookings'])) {
            foreach ($data['bookings'] as $key => $bk) {
                DB::table('bookings')->insert([
                    'id' => $key + 1,
                    'type' => 'package',
                    'startDate' => date('Y-m-d H:i:s', strtotime($bk['booking_date'] ?? 'now')),
                    'endDate' => date('Y-m-d H:i:s', strtotime($bk['booking_date'] ?? 'now') + 86400),
                    'totalPrice' => $bk['total_price'] ?? 0,
                    'customerName' => $bk['customer_name'] ?? 'Demo User',
                    'customerEmail' => 'demo@example.com',
                    'customerPhone' => '08123456789',
                    'status' => strtolower($bk['status'] ?? 'pending'),
                ]);
            }
        }

        // 6. BLOGS
        if (isset($data['blogs'])) {
            foreach ($data['blogs'] as $blog) {
                DB::table('blogs')->insert([
                    'id' => $blog['id'],
                    'slug' => strtolower(str_replace(' ', '-', $blog['title'])),
                    'title' => $blog['title'],
                    'content' => $blog['excerpt'] ?? '',
                    'excerpt' => $blog['excerpt'] ?? '',
                    'image' => $blog['image'] ?? null,
                    'author' => $blog['author']['name'] ?? 'Admin',
                    'category' => $blog['category'] ?? 'tour',
                    'status' => ($blog['is_published'] ?? true) ? 'published' : 'draft',
                    'createdAt' => date('Y-m-d H:i:s', strtotime($blog['createdAt'] ?? 'now')),
                    'updatedAt' => date('Y-m-d H:i:s', strtotime($blog['createdAt'] ?? 'now')),
                ]);
            }
        }

        // 7. OUTBOUND SERVICES
        if (isset($data['outbound_services'])) {
            foreach ($data['outbound_services'] as $key => $obs) {
                DB::table('outbound_services')->insert([
                    'id' => $key + 1,
                    'title' => $obs['title'],
                    'shortDesc' => $obs['shortDesc'] ?? null,
                    'detailDesc' => $obs['detailDesc'] ?? null,
                    'icon' => $obs['icon'] ?? null,
                    'image' => $obs['image'] ?? null,
                ]);
            }
        }

        // 8. OUTBOUND VIDEOS
        if (isset($data['outbound_videos'])) {
            foreach ($data['outbound_videos'] as $key => $obv) {
                DB::table('outbound_videos')->insert([
                    'id' => $key + 1,
                    'title' => $obv['title'],
                    'youtubeUrl' => $obv['youtubeUrl'],
                ]);
            }
        }

        // 9. OUTBOUND LOCATIONS
        if (isset($data['outbound_locations'])) {
            foreach ($data['outbound_locations'] as $key => $obl) {
                DB::table('outbound_locations')->insert([
                    'id' => $key + 1,
                    'name' => $obl['name'],
                    'image' => $obl['image'] ?? null,
                ]);
            }
        }

        // 10. CLIENTS
        if (isset($data['clients'])) {
            foreach ($data['clients'] as $key => $client) {
                DB::table('clients')->insert([
                    'id' => $key + 1,
                    'name' => 'Client '.($key + 1),
                    'logo' => $client['logo'] ?? null,
                ]);
            }
        }

        // 11. GALLERY IMAGES
        if (isset($data['gallery_images'])) {
            foreach ($data['gallery_images'] as $key => $img) {
                DB::table('gallery_images')->insert([
                    'id' => $key + 1,
                    'imageUrl' => $img['imageUrl'],
                ]);
            }
        }

        // 12. PACKAGE TIERS
        if (isset($data['package_tiers'])) {
            foreach ($data['package_tiers'] as $key => $tier) {
                DB::table('package_tiers')->insert([
                    'id' => $tier['id'] ?? ($key + 1),
                    'tierName' => $tier['name'],
                    'category' => 'general',
                    'tagline' => $tier['description'] ?? null,
                ]);
            }
        }

        // 13. SETTINGS
        if (isset($data['settings'])) {
            foreach ($data['settings'] as $setting) {
                DB::table('settings')->insert([
                    'key' => $setting['key'],
                    'value' => json_encode($setting['value']),
                ]);
            }
        }
    }
}
