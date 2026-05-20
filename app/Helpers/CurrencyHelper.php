<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CurrencyHelper
{
    /**
     * Get exchange rate from IDR to target currency.
     * Uses ExchangeRate-API with 24 hours caching.
     *
     * @param string $targetCurrency
     * @return float
     */
    public static function getRate($targetCurrency)
    {
        $targetCurrency = strtoupper($targetCurrency);
        if ($targetCurrency === 'IDR') {
            return 1.0;
        }

        // Cache rates for 24 hours
        return Cache::remember('exchange_rate_idr_to_' . $targetCurrency, 86400, function () use ($targetCurrency) {
            try {
                $apiKey = 'b753386c73cbdf1122c8f917';
                $response = Http::timeout(5)->get("https://v6.exchangerate-api.com/v6/{$apiKey}/latest/IDR");

                if ($response->successful()) {
                    $data = $response->json();
                    $rates = $data['conversion_rates'] ?? [];
                    if (isset($rates[$targetCurrency])) {
                        return (float) $rates[$targetCurrency];
                    }
                }
                
                Log::warning("Failed to fetch exchange rate for {$targetCurrency} from API. Using fallback rate.");
            } catch (\Exception $e) {
                Log::error("Error fetching exchange rate: " . $e->getMessage());
            }

            // Fallback rates if API fails
            $fallbacks = [
                'MYR' => 0.00030,   // 1 IDR ≈ 0.00030 MYR
                'SGD' => 0.000085,  // 1 IDR ≈ 0.000085 SGD
            ];

            return $fallbacks[$targetCurrency] ?? 1.0;
        });
    }

    /**
     * Convert and format price based on active locale.
     * Locales mapping:
     * - 'id' => IDR (Rp)
     * - 'my' => MYR (RM) [Default]
     * - 'en' => SGD (S$)
     *
     * @param float $priceInIdr
     * @param string|null $locale
     * @return string
     */
    public static function formatPrice($priceInIdr, $locale = null)
    {
        if (is_null($locale)) {
            $locale = session('locale', 'my'); // Default Malaysia (my)
        }

        switch ($locale) {
            case 'id':
                $currency = 'IDR';
                $symbol = 'Rp ';
                $rate = 1.0;
                $decimals = 0;
                $decPoint = ',';
                $thousandsSep = '.';
                break;
            case 'en':
                $currency = 'SGD';
                $symbol = 'S$ ';
                $rate = self::getRate('SGD');
                $decimals = 2;
                $decPoint = '.';
                $thousandsSep = ',';
                break;
            case 'my':
            default:
                $currency = 'MYR';
                $symbol = 'RM ';
                $rate = self::getRate('MYR');
                $decimals = 2;
                $decPoint = '.';
                $thousandsSep = ',';
                break;
        }

        $convertedPrice = $priceInIdr * $rate;

        // If it's a whole number in MYR/SGD, we can choose to round it or keep decimals. Let's keep decimals for MYR/SGD.
        return $symbol . number_format($convertedPrice, $decimals, $decPoint, $thousandsSep);
    }
}
