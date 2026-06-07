<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Package;
use App\Models\User;
use App\Notifications\CustomerBookingNotification;
use App\Notifications\NewBookingNotification;
use App\Repositories\BookingRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class BookingService
{
    public function __construct(
        private BookingRepository $repository
    ) {}

    public function isAvailable(array $data)
    {
        $startDate = $data['startDate'];
        $endDate = $data['endDate'];

        return true;
    }

    public function create(array $data)
    {
        try {
            return DB::transaction(function () use ($data) {
                if (! $this->isAvailable($data)) {
                    throw new \Exception('Armada atau Paket tidak tersedia pada tanggal yang dipilih.');
                }

                // Generate booking code
                $data['bookingCode'] = $this->generateBookingCode();

                // Calculate total price & cost
                $prices = $this->calculateTotalPriceAndCost($data);
                $data['totalPrice'] = $prices['price'];
                $data['total_cost'] = $prices['cost'];

                // Set default status
                $data['status'] = $data['status'] ?? 'pending';

                // Sync Customer
                $customer = Customer::updateOrCreate(
                    ['email' => $data['customerEmail']],
                    [
                        'name' => $data['customerName'],
                        'phone' => $data['customerPhone'] ?? null,
                    ]
                );
                $data['customerId'] = $customer->id;

                $booking = $this->repository->create($data);

                // Update customer stats
                $customer->update([
                    'total_bookings' => $customer->bookings()->count(),
                    'total_spent' => $customer->bookings()->whereIn('status', Booking::REVENUE_STATUSES)->sum('totalPrice'),
                    'last_booking_at' => now(),
                ]);

                // Notify Admins
                try {
                    $admins = User::whereIn('role', ['admin', 'superadmin'])->get();
                    Notification::send($admins, new NewBookingNotification($booking));
                } catch (\Exception $ne) {
                    Log::warning('Failed to send admin booking notification: '.$ne->getMessage());
                }

                // Notify Customer with Invoice
                try {
                    $booking->notify(new CustomerBookingNotification($booking));
                } catch (\Exception $ce) {
                    Log::warning('Failed to send customer booking notification: '.$ce->getMessage());
                }

                Log::info('New booking created: '.$booking->bookingCode, ['booking_id' => $booking->id]);

                return $booking;
            });
        } catch (\Exception $e) {
            Log::error('Booking Creation Failed: '.$e->getMessage());
            throw $e;
        }
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

    public function bulkDelete(array $ids)
    {
        return DB::transaction(function () use ($ids) {
            return Booking::whereIn('id', $ids)->delete();
        });
    }

    private function generateBookingCode(): string
    {
        do {
            $code = 'WT-'.strtoupper(Str::random(6));
        } while (Booking::where('bookingCode', $code)->exists());

        return $code;
    }

    private function calculateTotalPriceAndCost(array $data): array
    {
        $price = 0;
        $cost = 0;
        $pax = $data['metadata']['pax'] ?? 1;

        if (isset($data['packageId'])) {
            $package = Package::find($data['packageId']);
            $pricePerPerson = $package->price ?? 0;
            $costPerPerson = $package->cost_price ?? 0;

            // Check if there are pricing details for specific pax count
            if ($package->pricingDetails && is_array($package->pricingDetails)) {
                // Find matching pax tier
                $match = null;
                foreach ($package->pricingDetails as $detail) {
                    if ($detail['pax'] == $pax) {
                        $match = $detail;
                        break;
                    }
                }

                if ($match) {
                    $pricePerPerson = $match['price_per_person'] ?? $match['price'] ?? $match['pricePerPerson'] ?? $pricePerPerson;
                }
            }

            $price = $pricePerPerson * $pax;
            $cost = $costPerPerson * $pax;
        }

        return ['price' => $price, 'cost' => $cost];
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
