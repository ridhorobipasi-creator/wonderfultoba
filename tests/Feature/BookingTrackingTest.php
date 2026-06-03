<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Package;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTrackingTest extends TestCase
{
    use RefreshDatabase;

    public function test_tracking_lookup_page_can_be_viewed(): void
    {
        $response = $this->get(route('booking.track.form'));

        $response->assertOk();
        $response->assertSee('Lihat Status Booking');
        $response->assertSee('Kode Booking');
    }

    public function test_tracking_lookup_redirects_to_booking_code(): void
    {
        $response = $this->post(route('booking.track.lookup'), [
            'booking_code' => ' wt-test1 ',
        ]);

        $response->assertRedirect(route('booking.track', 'WT-TEST1'));
    }

    public function test_tracking_page_shows_booking_details(): void
    {
        Setting::create(['key' => 'general', 'value' => ['whatsapp' => '6281260460461']]);

        $package = Package::create([
            'slug' => 'tracking-package',
            'name' => 'Tracking Package',
            'shortDescription' => 'Short desc',
            'description' => 'Full description',
            'images' => [],
            'includes' => [],
            'excludes' => [],
            'pricingDetails' => [],
            'itinerary' => [],
            'translations' => [],
            'price' => 750000,
            'duration' => '2 Hari',
            'status' => 'active',
        ]);

        Booking::create([
            'bookingCode' => 'WT-TRACK1',
            'type' => 'package',
            'packageId' => $package->id,
            'customerName' => 'Budi Test',
            'customerEmail' => 'budi@test.local',
            'customerPhone' => '08123456789',
            'startDate' => now()->addDay(),
            'endDate' => now()->addDays(2),
            'totalPrice' => 1500000,
            'status' => 'pending',
            'metadata' => ['pax' => 2],
        ]);

        $response = $this->get(route('booking.track', 'wt-track1'));

        $response->assertOk();
        $response->assertSee('WT-TRACK1');
        $response->assertSee('Tracking Package');
        $response->assertSee('Menunggu Konfirmasi');
        $response->assertSee('Invoice');
        $response->assertSee('Lihat Paket');
    }

    public function test_unknown_tracking_code_returns_not_found(): void
    {
        $response = $this->get(route('booking.track', 'WT-MISSING'));

        $response->assertNotFound();
    }
}
