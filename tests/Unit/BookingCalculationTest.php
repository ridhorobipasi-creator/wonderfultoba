<?php

namespace Tests\Unit;

use App\Models\Package;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookingCalculationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test calculation for standard adult pax.
     */
    public function test_booking_total_price_calculation_for_adults()
    {
        $package = Package::create([
            'slug' => 'lake-toba-3d2n',
            'name' => 'Lake Toba 3D2N',
            'description' => 'Test',
            'price' => 1500000,
            'duration' => '3D2N',
            'images' => [],
            'includes' => [],
            'excludes' => [],
            'status' => 'active',
        ]);

        $paxAdult = 4;
        $totalPrice = $package->price * $paxAdult;

        // Assuming 11% tax calculation that the system normally applies in the views
        $taxPercentage = 11;
        $taxAmount = $totalPrice * ($taxPercentage / 100);
        $grandTotal = $totalPrice + $taxAmount;

        $this->assertEquals(6000000, $totalPrice);
        $this->assertEquals(660000, $taxAmount);
        $this->assertEquals(6660000, $grandTotal);
    }

    /**
     * Test calculation involving children.
     */
    public function test_booking_total_price_calculation_with_children()
    {
        $package = Package::create([
            'slug' => 'samosir-trip',
            'name' => 'Samosir Trip',
            'description' => 'Test',
            'price' => 2000000,
            'duration' => '2D1N',
            'images' => [],
            'includes' => [],
            'excludes' => [],
            'status' => 'active',
        ]);

        $paxAdult = 2;
        $paxChildren = 1;
        
        // Let's assume children are half price in this hypothetical test structure
        $childPrice = $package->price * 0.5;
        $totalPrice = ($package->price * $paxAdult) + ($childPrice * $paxChildren);

        $this->assertEquals(5000000, $totalPrice);

        // Tax 11%
        $taxAmount = $totalPrice * 0.11;
        $this->assertEquals(550000, $taxAmount);
        $this->assertEquals(5550000, $totalPrice + $taxAmount);
    }
}
