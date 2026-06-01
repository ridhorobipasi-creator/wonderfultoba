<?php

namespace Tests\Feature;

use App\Models\Package;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Tests\TestCase;

class BookingIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_flow_creates_booking_and_returns_whatsapp()
    {
        // Disable throttle middleware to avoid rate limiting in tests
        $this->withoutMiddleware(ThrottleRequests::class);
        // Prepare settings and package
        Setting::create(['key' => 'general', 'value' => ['whatsapp' => '6281260460461']]);

        $package = Package::create([
            'slug' => 'integration-package',
            'name' => 'Integration Package',
            'shortDescription' => 'Short desc',
            'description' => 'Full description',
            'images' => json_encode([]),
            'includes' => json_encode([]),
            'excludes' => json_encode([]),
            'pricingDetails' => json_encode([]),
            'itinerary' => json_encode([]),
            'translations' => json_encode([]),
            'price' => 500000,
            'duration' => '2 Hari',
            'status' => 'active',
        ]);

        $token = 'test-token';

        $response = $this->withSession(['_token' => $token])
            ->post(route('tour.booking.submit'), [
                '_token' => $token,
                'packageId' => $package->id,
                'customerName' => 'Budi Test',
                'customerEmail' => 'budi@test.local',
                'customerPhone' => '08123456789',
                'startDate' => date('Y-m-d', strtotime('+1 day')),
                'pax' => 2,
                'notes' => 'Test booking via integration test',
            ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $response->assertSessionHas('whatsappUrl');

        $this->assertDatabaseHas('bookings', [
            'customerName' => 'Budi Test',
            'customerEmail' => 'budi@test.local',
            'status' => 'pending',
        ]);
    }
}
