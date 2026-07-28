<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Package;
use App\Services\BookingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PricingTierTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @param  array<int, array<string, mixed>>  $tiers
     */
    private function makePackage(array $tiers, ?float $childPrice = null): Package
    {
        return Package::create([
            'slug' => 'tier-package-'.uniqid(),
            'name' => 'Tier Package',
            'shortDescription' => 'Short desc',
            'description' => 'Full description',
            'images' => [],
            'includes' => [],
            'excludes' => [],
            'pricingDetails' => $tiers === [] ? [] : ['tiers' => $tiers],
            'itinerary' => [],
            'translations' => [],
            'price' => 500.00,
            'childPrice' => $childPrice,
            'duration' => '3 Hari',
            'status' => 'active',
        ]);
    }

    /** Tier grosir yang dipakai di seluruh berkas ini. */
    private function standardTiers(): array
    {
        return [
            ['min_pax' => 1, 'max_pax' => 9, 'price' => 350.00],
            ['min_pax' => 11, 'max_pax' => 15, 'price' => 320.00],
        ];
    }

    public function test_pax_inside_a_tier_uses_that_tier(): void
    {
        $package = $this->makePackage($this->standardTiers());

        $this->assertEquals(350.00, $package->pricingTierFor(1)['price']);
        $this->assertEquals(350.00, $package->pricingTierFor(9)['price']);
        $this->assertEquals(320.00, $package->pricingTierFor(11)['price']);
        $this->assertEquals(320.00, $package->pricingTierFor(15)['price']);
    }

    public function test_pax_in_a_gap_falls_to_the_nearest_tier_below_not_to_base_price(): void
    {
        // 10 pax berada di antara tier 1-9 dan 11-15. Dulu ia tidak cocok
        // dengan tier mana pun dan diam-diam dibayar dengan harga dasar paket
        // (RM 500) -- lebih mahal daripada tier termurah sekalipun, tanpa satu
        // pun gejala di halaman.
        $package = $this->makePackage($this->standardTiers());

        $tier = $package->pricingTierFor(10);

        $this->assertNotNull($tier);
        $this->assertEquals(350.00, $tier['price']);
    }

    public function test_group_larger_than_the_top_tier_keeps_the_top_tier_price(): void
    {
        $package = $this->makePackage($this->standardTiers());

        $this->assertEquals(320.00, $package->pricingTierFor(40)['price']);
    }

    public function test_pax_below_the_lowest_tier_uses_the_lowest_tier(): void
    {
        $package = $this->makePackage([
            ['min_pax' => 4, 'max_pax' => 9, 'price' => 350.00],
        ]);

        $this->assertEquals(350.00, $package->pricingTierFor(1)['price']);
    }

    public function test_package_without_tiers_returns_null_so_base_price_still_applies(): void
    {
        $this->assertNull($this->makePackage([])->pricingTierFor(5));
    }

    public function test_incomplete_tier_rows_are_ignored(): void
    {
        // Baris tanpa max_pax pernah lolos ke perbandingan dan mencocoki apa pun.
        $package = $this->makePackage([
            ['min_pax' => 1, 'price' => 111.00],
            ['min_pax' => 1, 'max_pax' => 9, 'price' => 350.00],
        ]);

        $this->assertEquals(350.00, $package->pricingTierFor(5)['price']);
    }

    public function test_overlapping_tiers_keep_first_written_wins(): void
    {
        $package = $this->makePackage([
            ['min_pax' => 1, 'max_pax' => 10, 'price' => 350.00],
            ['min_pax' => 5, 'max_pax' => 15, 'price' => 320.00],
        ]);

        $this->assertEquals(350.00, $package->pricingTierFor(7)['price']);
    }

    public function test_booking_for_a_gap_pax_is_billed_at_the_tier_price(): void
    {
        // Ujung ke ujung: lewat BookingService, bukan cuma pemilih tier.
        $package = $this->makePackage($this->standardTiers());

        $booking = app(BookingService::class)->create([
            'packageId' => $package->id,
            'type' => 'package',
            'customerName' => 'Rombongan Sepuluh',
            'customerEmail' => 'sepuluh@test.local',
            'customerPhone' => '08123456789',
            'startDate' => now()->addDays(30)->format('Y-m-d'),
            'endDate' => now()->addDays(32)->format('Y-m-d'),
            'status' => 'pending',
            'metadata' => ['pax' => 10, 'paxChildren' => 0],
        ]);

        $breakdown = $booking->metadata['price_breakdown'];

        // 10 dewasa x RM 350 = RM 3.500, bukan 10 x RM 500 = RM 5.000.
        $this->assertEquals(3500.00, $breakdown['price_dewasa_total']);
        $this->assertEquals(10, $breakdown['pax_dewasa']);
    }

    public function test_child_price_falls_back_to_half_of_the_tier_price_not_the_base_price(): void
    {
        // Paket tanpa childPrice: anak = 50% harga dewasa YANG BERLAKU.
        // Setengah dari tier (RM 320), bukan setengah harga dasar (RM 500).
        $package = $this->makePackage($this->standardTiers());

        $booking = app(BookingService::class)->create([
            'packageId' => $package->id,
            'type' => 'package',
            'customerName' => 'Rombongan Dengan Anak',
            'customerEmail' => 'anak@test.local',
            'customerPhone' => '08123456789',
            'startDate' => now()->addDays(30)->format('Y-m-d'),
            'endDate' => now()->addDays(32)->format('Y-m-d'),
            'status' => 'pending',
            'metadata' => ['pax' => 12, 'paxChildren' => 2],
        ]);

        $breakdown = $booking->metadata['price_breakdown'];

        $this->assertEquals(3840.00, $breakdown['price_dewasa_total']); // 12 x 320
        $this->assertEquals(320.00, $breakdown['price_anak_total']);    // 2 x 160
    }

    public function test_tier_child_price_wins_over_every_fallback(): void
    {
        $package = $this->makePackage([
            ['min_pax' => 1, 'max_pax' => 20, 'price' => 300.00, 'child_price' => 100.00],
        ], childPrice: 250.00);

        $booking = app(BookingService::class)->create([
            'packageId' => $package->id,
            'type' => 'package',
            'customerName' => 'Anak Harga Tier',
            'customerEmail' => 'tieranak@test.local',
            'customerPhone' => '08123456789',
            'startDate' => now()->addDays(30)->format('Y-m-d'),
            'endDate' => now()->addDays(32)->format('Y-m-d'),
            'status' => 'pending',
            'metadata' => ['pax' => 2, 'paxChildren' => 3],
        ]);

        $breakdown = $booking->metadata['price_breakdown'];

        $this->assertEquals(300.00, $breakdown['price_anak_total']); // 3 x 100
    }

    protected function tearDown(): void
    {
        Booking::query()->forceDelete();
        parent::tearDown();
    }
}
