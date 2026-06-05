<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;

class CurrencyHelper
{
    /**
     * Get exchange rate from IDR to target currency.
     * Uses ExchangeRate-API with 24 hours caching.
     *
     * @param  string  $targetCurrency
     * @return float
     */
    public static function getRate($targetCurrency)
    {
        $targetCurrency = strtoupper($targetCurrency);
        if ($targetCurrency === 'IDR') {
            return 1.0;
        }

        $settings = Setting::where('key', 'general')->value('value') ?? [];
        $finance = $settings['finance'] ?? [];
        
        $manualMyrIdr = (float) ($finance['exchange_rate_manual_myr'] ?? 3500);
        $manualSgdIdr = (float) ($finance['exchange_rate_manual_sgd'] ?? 11500);
        
        $fallbacks = [
            'MYR' => $manualMyrIdr > 0 ? 1 / $manualMyrIdr : 0.00028,
            'SGD' => $manualSgdIdr > 0 ? 1 / $manualSgdIdr : 0.000086,
        ];

        return $fallbacks[$targetCurrency] ?? 1.0;
    }

    /**
     * Convert and format price based on active locale.
     * Locales mapping:
     * - 'id' => IDR (Rp) [Default]
     * - 'my' => MYR (RM)
     * - 'en' => SGD (S$)
     *
     * @param  float  $priceInIdr
     * @param  string|null  $locale
     * @return string
     */
    public static function formatPrice($priceInIdr, $locale = null)
    {
        if (is_null($locale)) {
            $locale = session('locale', 'id'); // Default Indonesia (id)
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
        return $symbol.number_format($convertedPrice, $decimals, $decPoint, $thousandsSep);
    }
}
