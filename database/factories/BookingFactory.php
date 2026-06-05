<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class BookingFactory extends Factory {
    public function definition(): array {
        return [
            'bookingCode' => $this->faker->unique()->lexify('BOOK???'),
            'customerName' => $this->faker->name(),
            'customerEmail' => $this->faker->unique()->safeEmail(),
            'customerPhone' => $this->faker->phoneNumber(),
            'packageId' => \App\Models\Package::factory(),
            'status' => 'pending',
            'type' => 'package',
            'totalPrice' => 100000,
            'startDate' => now(),
            'endDate' => now()->addDays(2),
        ];
    }
}