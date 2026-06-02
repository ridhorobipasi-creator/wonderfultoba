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

        // USERS (uses snake_case timestamps)
        if (isset($data['users'])) {
            foreach ($data['users'] as $user) {
                DB::table('users')->updateOrInsert(
                    ['id' => $user['id']],
                    [
                        'name' => $user['name'],
                        'email' => $user['email'],
                        'password' => Hash::make('password123'),
                        'role' => strtolower($user['role']),
                        'created_at' => $user['created_at'] ?? now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }

        // CITIES (uses camelCase timestamps)
        if (isset($data['cities'])) {
            foreach ($data['cities'] as $city) {
                DB::table('cities')->updateOrInsert(
                    ['id' => $city['id']],
                    [
                        'name' => $city['name'],
                        'slug' => $city['slug'] ?? strtolower(str_replace(' ', '-', $city['name'])),
                        'region' => $city['region'] ?? null,
                        'image' => $city['image'] ?? null,
                        'createdAt' => $city['created_at'] ?? now(),
                        'updatedAt' => now(),
                    ]
                );
            }
        }

        // CUSTOMERS (snake_case)
        if (isset($data['customers'])) {
            foreach ($data['customers'] as $customer) {
                DB::table('customers')->updateOrInsert(
                    ['id' => $customer['id']],
                    [
                        'name' => $customer['name'],
                        'email' => $customer['email'],
                        'phone' => $customer['phone'] ?? null,
                        'address' => $customer['address'] ?? null,
                        'total_bookings' => $customer['total_bookings'] ?? 0,
                        'total_spent' => $customer['total_spent'] ?? 0,
                        'last_booking_at' => $customer['last_booking_at'] ?? null,
                        'created_at' => $customer['created_at'] ?? now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }



        // PACKAGES (camelCase)
        if (isset($data['packages'])) {
            foreach ($data['packages'] as $pkg) {
                DB::table('packages')->updateOrInsert(
                    ['id' => $pkg['id']],
                    [
                        'slug' => $pkg['slug'],
                        'name' => $pkg['name'],
                        'shortDescription' => $pkg['shortDescription'] ?? null,
                        'description' => $pkg['description'] ?? '',
                        'locationTag' => $pkg['locationTag'] ?? null,
                        'price' => $pkg['price'] ?? 0,
                        'childPrice' => $pkg['childPrice'] ?? null,
                        'cost_price' => $pkg['cost_price'] ?? null,
                        'priceDisplay' => $pkg['priceDisplay'] ?? null,
                        'duration' => $pkg['duration'] ?? '',
                        'images' => json_encode($pkg['images'] ?? []),
                        'includes' => json_encode($pkg['includes'] ?? []),
                        'excludes' => json_encode($pkg['excludes'] ?? []),
                        'itinerary' => isset($pkg['itinerary']) ? json_encode($pkg['itinerary']) : null,
                        'dronePrice' => $pkg['dronePrice'] ?? null,
                        'status' => $pkg['status'] ?? 'active',
                        'isFeatured' => $pkg['isFeatured'] ?? false,
                        'cityId' => $pkg['cityId'] ?? null,
                        'createdAt' => $pkg['created_at'] ?? now(),
                        'updatedAt' => now(),
                    ]
                );
            }
        }

        // PACKAGE_IMAGES (snake_case)
        if (isset($data['package_images'])) {
            foreach ($data['package_images'] as $pkgImg) {
                DB::table('package_images')->updateOrInsert(
                    ['id' => $pkgImg['id']],
                    [
                        'package_id' => $pkgImg['package_id'],
                        'image_path' => $pkgImg['image_path'],
                        'sort_order' => $pkgImg['sort_order'] ?? 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }

        // PACKAGE_AMENITIES (snake_case)
        if (isset($data['package_amenities'])) {
            foreach ($data['package_amenities'] as $amenity) {
                DB::table('package_amenities')->updateOrInsert(
                    ['id' => $amenity['id']],
                    [
                        'package_id' => $amenity['package_id'],
                        'name' => $amenity['name'],
                        'type' => $amenity['type'],
                    ]
                );
            }
        }

        // PACKAGE_TIERS (camelCase)
        if (isset($data['package_tiers'])) {
            foreach ($data['package_tiers'] as $tier) {
                DB::table('package_tiers')->updateOrInsert(
                    ['id' => $tier['id']],
                    [
                        'tierName' => $tier['tierName'],
                        'category' => $tier['category'] ?? 'general',
                        'tagline' => $tier['tagline'] ?? null,
                        'createdAt' => now(),
                        'updatedAt' => now(),
                    ]
                );
            }
        }

        // BOOKINGS (camelCase)
        if (isset($data['bookings'])) {
            foreach ($data['bookings'] as $bk) {
                DB::table('bookings')->updateOrInsert(
                    ['id' => $bk['id']],
                    [
                        'userId' => $bk['userId'] ?? null,
                        'customerId' => $bk['customerId'] ?? null,
                        'type' => $bk['type'] ?? 'package',
                        'packageId' => $bk['packageId'] ?? null,
                        'bookingCode' => $bk['bookingCode'],
                        'startDate' => $bk['startDate'],
                        'endDate' => $bk['endDate'],
                        'totalPrice' => $bk['totalPrice'] ?? 0,
                        'total_cost' => $bk['total_cost'] ?? null,
                        'customerName' => $bk['customerName'],
                        'customerEmail' => $bk['customerEmail'],
                        'customerPhone' => $bk['customerPhone'],
                        'notes' => $bk['notes'] ?? null,
                        'status' => strtolower($bk['status'] ?? 'pending'),
                        'createdAt' => $bk['created_at'] ?? now(),
                        'updatedAt' => now(),
                    ]
                );
            }
        }

        // BLOGS (camelCase)
        if (isset($data['blogs'])) {
            $hasTags = DB::getSchemaBuilder()->hasColumn('blogs', 'tags');
            foreach ($data['blogs'] as $blog) {
                $record = [
                    'slug' => $blog['slug'] ?? strtolower(str_replace(' ', '-', $blog['title'])),
                    'title' => $blog['title'],
                    'content' => $blog['content'] ?? '',
                    'excerpt' => $blog['excerpt'] ?? '',
                    'image' => $blog['image'] ?? null,
                    'author' => $blog['author'] ?? 'Admin',
                    'category' => $blog['category'] ?? 'tour',
                    'status' => ($blog['status'] ?? 'published') === 'published' ? 'published' : 'draft',
                    'published_at' => $blog['published_at'] ?? now(),
                    'metaTitle' => $blog['metaTitle'] ?? null,
                    'metaDescription' => $blog['metaDescription'] ?? null,
                    'createdAt' => $blog['published_at'] ?? now(),
                    'updatedAt' => now(),
                ];
                if ($hasTags) {
                    $record['tags'] = json_encode($blog['tags'] ?? []);
                }
                DB::table('blogs')->updateOrInsert(['id' => $blog['id']], $record);
            }
        }

        // GALLERY_IMAGES (camelCase)
        if (isset($data['gallery_images'])) {
            foreach ($data['gallery_images'] as $img) {
                DB::table('gallery_images')->updateOrInsert(
                    ['id' => $img['id']],
                    [
                        'imageUrl' => $img['imageUrl'],
                        'category' => $img['category'] ?? 'Tour',
                        'caption' => $img['caption'] ?? null,
                        'tags' => json_encode($img['tags'] ?? []),
                        'isActive' => $img['isActive'] ?? 1,
                        'orderPriority' => $img['orderPriority'] ?? 0,
                        'createdAt' => now(),
                        'updatedAt' => now(),
                    ]
                );
            }
        }

        // CLIENTS (camelCase)
        if (isset($data['clients'])) {
            foreach ($data['clients'] as $client) {
                DB::table('clients')->updateOrInsert(
                    ['id' => $client['id']],
                    [
                        'name' => $client['name'] ?? 'Client '.$client['id'],
                        'logo' => $client['logo'] ?? null,
                        'websiteUrl' => $client['websiteUrl'] ?? null,
                        'orderPriority' => $client['orderPriority'] ?? 0,
                        'isActive' => $client['isActive'] ?? 1,
                        'createdAt' => now(),
                        'updatedAt' => now(),
                    ]
                );
            }
        }

        // MEDIA (snake_case)
        if (isset($data['media'])) {
            foreach ($data['media'] as $media) {
                DB::table('media')->updateOrInsert(
                    ['id' => $media['id']],
                    [
                        'filename' => $media['filename'],
                        'original_name' => $media['original_name'] ?? $media['filename'],
                        'path' => $media['path'],
                        'category' => $media['category'] ?? 'General',
                        'mime_type' => $media['mime_type'] ?? 'image/jpeg',
                        'size' => $media['size'] ?? 0,
                        'alt_text' => $media['alt_text'] ?? null,
                        'order_priority' => $media['order_priority'] ?? 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }

        // ACTIVITY_LOGS (snake_case)
        if (isset($data['activity_logs'])) {
            foreach ($data['activity_logs'] as $log) {
                DB::table('activity_logs')->updateOrInsert(
                    ['id' => $log['id']],
                    [
                        'user_id' => $log['userId'] ?? null,
                        'action' => $log['action'],
                        'description' => $log['description'],
                        'model' => $log['model_type'] ?? $log['model'] ?? 'system',
                        'model_id' => $log['model_id'] ?? null,
                        'ip_address' => $log['ip_address'] ?? null,
                        'created_at' => $log['created_at'] ?? now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }

        // SETTINGS (camelCase)
        if (isset($data['settings'])) {
            foreach ($data['settings'] as $setting) {
                DB::table('settings')->updateOrInsert(
                    ['key' => $setting['key']],
                    [
                        'key' => $setting['key'],
                        'value' => json_encode($setting['value']),
                        'createdAt' => now(),
                        'updatedAt' => now(),
                    ]
                );
            }
        }

        $this->command->info('Database seeded successfully with comprehensive dummy data!');
    }
}
