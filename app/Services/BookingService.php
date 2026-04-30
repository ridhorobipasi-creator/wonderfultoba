<?php

namespace App\Services;

use App\Models\Booking;
use App\Repositories\BookingRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingService
{
    public function __construct(
        private BookingRepository $repository
    ) {}

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Generate booking code
            $data['bookingCode'] = $this->generateBookingCode();
            
            // Calculate total price
            $data['totalPrice'] = $this->calculateTotalPrice($data);
            
            // Set default status
            $data['status'] = $data['status'] ?? 'pending';
            
            return $this->repository->create($data);
        });
    }

    public function update(Booking $booking, array $data)
    {
        return DB::transaction(function () use ($booking, $data) {
            return $this->repository->update($booking, $data);
        });
    }

    public function updateStatus(Booking $booking, string $status)
    {
        return $this->repository->update($booking, ['status' => $status]);
    }

    public function delete(Booking $booking)
    {
        return $this->repository->delete($booking);
    }

    private function generateBookingCode(): string
    {
        do {
            $code = 'WT-' . strtoupper(Str::random(6));
        } while (Booking::where('bookingCode', $code)->exists());

        return $code;
    }

    private function calculateTotalPrice(array $data): float
    {
        $basePrice = 0;

        if (isset($data['packageId'])) {
            $package = \App\Models\Package::find($data['packageId']);
            $basePrice = $package->price ?? 0;
        } elseif (isset($data['carId'])) {
            $car = \App\Models\Car::find($data['carId']);
            $days = isset($data['startDate']) && isset($data['endDate']) 
                ? now()->parse($data['endDate'])->diffInDays(now()->parse($data['startDate'])) + 1
                : 1;
            $basePrice = ($car->price ?? 0) * $days;
        }

        return $basePrice;
    }

    public function getAll(array $filters = [])
    {
        return $this->repository->getAll($filters);
    }

    public function find(int $id)
    {
        return $this->repository->find($id);
    }
}
