<?php

namespace App\Actions;

use App\Models\Package;

class CalculateBookingPrice
{
    public function execute(array $data): array
    {
        $price = 0;
        $cost = 0;
        $pax = $data['metadata']['pax'] ?? 1;

        if (isset($data['packageId'])) {
            $package = Package::find($data['packageId']);

            if ($package) {
                $pricePerPerson = $package->price ?? 0;
                $costPerPerson = $package->cost_price ?? 0;

                // Check if there are pricing details for specific pax count
                if ($package->pricingDetails && is_array($package->pricingDetails)) {
                    // Find matching pax tier
                    $match = collect($package->pricingDetails)->firstWhere('pax', $pax);

                    if ($match) {
                        $pricePerPerson = $match['price_per_person'] ?? $match['price'] ?? $match['pricePerPerson'] ?? $pricePerPerson;
                    }
                }

                $price = $pricePerPerson * $pax;
                $cost = $costPerPerson * $pax;
            }
        }

        return ['price' => $price, 'cost' => $cost];
    }
}
