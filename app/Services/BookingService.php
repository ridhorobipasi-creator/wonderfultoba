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

    /**
     * Check if a package/date is available for booking.
     * Currently always returns true — extend with real capacity/blackout logic.
     */
    public function isAvailable(array $data): bool
    {
        // Future: check package capacity, blackout dates, fleet availability
        return true;
    }

    public function create(array $data)
    {
        try {
            $booking = DB::transaction(function () use ($data) {
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

                // Sync Customer — include soft-deleted records so a repeat booking
                // from a previously removed email restores that customer instead of
                // hitting the customers_email_unique index with a fresh insert.
                $customer = Customer::withTrashed()->firstOrNew(['email' => $data['customerEmail']]);
                $customer->fill([
                    'name' => $data['customerName'],
                    'phone' => $data['customerPhone'] ?? null,
                ]);
                if ($customer->trashed()) {
                    $customer->deleted_at = null;
                }
                $customer->save();
                $data['customerId'] = $customer->id;

                $createdBooking = $this->repository->create($data);

                // Update customer stats
                $customer->update([
                    'total_bookings' => $customer->bookings()->count(),
                    'total_spent' => $customer->bookings()->whereIn('status', ['confirmed', 'completed'])->sum('totalPrice'),
                    'last_booking_at' => now(),
                ]);

                return $createdBooking;
            });

            // Notify Admins — must match the actual role names used by the role
            // middleware (superadmin / admin_tour / admin_umum). The old list
            // ('admin', 'superadmin') silently skipped admin_tour & admin_umum.
            try {
                $admins = User::whereIn('role', ['superadmin', 'admin_tour', 'admin_umum'])->get();
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

    private function calculateTotalPriceAndCost(array &$data): array
    {
        $price = 0;
        $cost = 0;
        $pax = $data['metadata']['pax'] ?? 1;
        $paxChildren = $data['metadata']['paxChildren'] ?? 0;
        $selectedServices = $data['metadata']['selected_services'] ?? [];

        $taxPercentage = 11;
        $surchargeWeekend = 0;
        $surchargePeak = 0;
        $peakStart = '';
        $peakEnd = '';

        $setting = \App\Models\Setting::where('key', 'general')->first();
        if ($setting && isset($setting->value['finance'])) {
            $taxPercentage = (float) ($setting->value['finance']['tax_percentage'] ?? 11);
            $surchargeWeekend = (float) ($setting->value['finance']['surcharge_weekend'] ?? 0);
            $surchargePeak = (float) ($setting->value['finance']['surcharge_peak'] ?? 0);
            $peakStart = $setting->value['finance']['surcharge_peak_start'] ?? '';
            $peakEnd = $setting->value['finance']['surcharge_peak_end'] ?? '';
        }

        if (isset($data['packageId'])) {
            $package = Package::find($data['packageId']);
            $pricePerPerson = $package->price ?? 0;
            $costPerPerson = $package->cost_price ?? 0;

            // Check if there are pricing details for specific pax count
            if ($package->pricingDetails && is_array($package->pricingDetails)) {
                // Find matching pax tier
                $match = null;
                foreach ($package->pricingDetails as $detail) {
                    if (isset($detail['pax']) && $detail['pax'] == $pax) {
                        $match = $detail;
                        break;
                    }
                }

                if ($match) {
                    $pricePerPerson = $match['price_per_person'] ?? $match['price'] ?? $match['pricePerPerson'] ?? $pricePerPerson;
                }
            }

            $priceDewasa = $pricePerPerson * $pax;
            $priceAnak = $pricePerPerson * 0.5 * $paxChildren;
            
            $additionalServicesPrice = 0;
            $availableServices = $package->pricingDetails['additional_services'] ?? [
                ['name' => 'Private Jet Charter', 'icon' => 'flight_takeoff', 'price' => 120000000],
                ['name' => 'Pemandu Antropologi', 'icon' => 'person_pin', 'price' => 5500000]
            ];
            
            $detailedServices = [];
            foreach ($availableServices as $srv) {
                if (in_array($srv['name'], $selectedServices)) {
                    $srvPrice = $srv['price'] ?? 0;
                    $additionalServicesPrice += $srvPrice;
                    $detailedServices[] = [
                        'name' => $srv['name'],
                        'price' => $srvPrice
                    ];
                }
            }

            $totalSebelumPajak = $priceDewasa + $priceAnak + $additionalServicesPrice;

            // Apply Surcharges
            $surchargeAmount = 0;
            $appliedSurcharges = [];
            $startDateObj = isset($data['startDate']) ? \Carbon\Carbon::parse($data['startDate']) : null;

            if ($startDateObj) {
                // 1. Check Weekend
                if ($surchargeWeekend > 0 && $startDateObj->isWeekend()) {
                    $amt = $totalSebelumPajak * ($surchargeWeekend / 100);
                    $surchargeAmount += $amt;
                    $appliedSurcharges[] = [
                        'name' => "Weekend Surcharge ({$surchargeWeekend}%)",
                        'amount' => $amt
                    ];
                }

                // 2. Check Peak Season
                if ($surchargePeak > 0 && !empty($peakStart) && !empty($peakEnd)) {
                    try {
                        $currentYear = $startDateObj->year;
                        $startParts = explode('/', $peakStart);
                        $endParts = explode('/', $peakEnd);
                        
                        if (count($startParts) == 2 && count($endParts) == 2) {
                            $peakStartDate = \Carbon\Carbon::createFromDate($currentYear, $startParts[1], $startParts[0])->startOfDay();
                            $peakEndDate = \Carbon\Carbon::createFromDate($currentYear, $endParts[1], $endParts[0])->endOfDay();

                            // Handle year rollover (e.g. 20/12 to 05/01)
                            if ($peakEndDate->lt($peakStartDate)) {
                                if ($startDateObj->month <= $peakEndDate->month) {
                                    $peakStartDate->subYear();
                                } else {
                                    $peakEndDate->addYear();
                                }
                            }

                            if ($startDateObj->between($peakStartDate, $peakEndDate)) {
                                $amt = $totalSebelumPajak * ($surchargePeak / 100);
                                $surchargeAmount += $amt;
                                $appliedSurcharges[] = [
                                    'name' => "Peak Season Surcharge ({$surchargePeak}%)",
                                    'amount' => $amt
                                ];
                            }
                        }
                    } catch (\Exception $e) {
                        Log::warning('Failed to parse peak season dates: ' . $e->getMessage());
                    }
                }
            }

            $totalDenganSurcharge = $totalSebelumPajak + $surchargeAmount;
            $pajakLayanan = round($totalDenganSurcharge * ($taxPercentage / 100));
            $totalAkhir = $totalDenganSurcharge + $pajakLayanan;

            $price = $totalAkhir;
            $cost = ($costPerPerson * $pax) + ($costPerPerson * 0.5 * $paxChildren);

            $data['metadata']['price_breakdown'] = [
                'pax_dewasa' => $pax,
                'price_dewasa_total' => $priceDewasa,
                'pax_anak' => $paxChildren,
                'price_anak_total' => $priceAnak,
                'additional_services' => $detailedServices,
                'subtotal_base' => $totalSebelumPajak,
                'surcharges' => $appliedSurcharges,
                'total_surcharge' => $surchargeAmount,
                'subtotal_with_surcharge' => $totalDenganSurcharge,
                'tax_percentage' => $taxPercentage,
                'tax' => $pajakLayanan,
                'total' => $totalAkhir
            ];
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
