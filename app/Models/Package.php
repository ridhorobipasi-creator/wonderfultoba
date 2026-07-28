<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Helpers\CurrencyHelper;
use App\Traits\HasImageFallback;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin \Eloquent
 */

class Package extends Model
{
    use HasFactory;
    use \App\Traits\Syncable, HasImageFallback, SoftDeletes;

    const CREATED_AT = 'createdAt';

    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'slug', 'name', 'shortDescription', 'description', 'locationTag',
        'price', 'childPrice', 'cost_price', 'priceDisplay', 'duration', 'images',
        'includes', 'excludes', 'pricingDetails', 'itinerary', 'itineraryText',
        'dronePrice', 'droneLocation', 'notes', 'status', 'isFeatured',
        'sortOrder', 'metaTitle', 'metaDescription',
        'translations', 'cityId',
    ];

    protected $appends = ['first_image', 'image_url', 'formatted_price', 'translated_name', 'translated_description', 'translated_short_description', 'translated_itinerary_text'];

    protected $casts = [
        'images' => 'array',
        'includes' => 'array',
        'excludes' => 'array',
        'pricingDetails' => 'array',
        'itinerary' => 'array',
        'translations' => 'array',
        'isFeatured' => 'boolean',
        'price' => 'double',
        'childPrice' => 'double',
        'dronePrice' => 'double',
    ];

    /**
     * Tier harga grosir yang berlaku untuk sejumlah peserta.
     *
     * $pax di sini adalah TOTAL orang — dewasa ditambah anak. Satu anak tetap
     * satu kursi dan satu kepala yang harus diurus, jadi 8 dewasa + 4 anak
     * adalah rombongan 12, bukan 8. Yang dibagi bersama hanya ambangnya:
     * harga tetap per jenis, dewasa memakai harga dewasa tier dan anak
     * memakai harga anak tier.
     *
     * Aturannya: kalau paket ini punya harga grosir, harga SELALU datang dari
     * salah satu tier. Sebelumnya jumlah pax yang jatuh di celah antar-tier
     * (mis. tier 1-9 dan 11-15, lalu tamu memesan 10) tidak cocok dengan tier
     * mana pun, tidak pula melampaui tier tertinggi, sehingga diam-diam dibayar
     * dengan harga dasar paket — angka yang mungkin sudah lama tidak diurus.
     * Tidak ada gejalanya: halaman tetap terlihat wajar dengan harga yang salah.
     *
     * Mengembalikan null hanya bila paket ini memang tidak punya tier.
     *
     * @return array<string, mixed>|null
     */
    public function pricingTierFor(int $pax): ?array
    {
        $tiers = [];
        foreach ($this->pricingDetails['tiers'] ?? [] as $tier) {
            // Baris tak lengkap dibuang: membandingkan pax dengan null selalu
            // menghasilkan kecocokan palsu pada tier pertama.
            if (is_array($tier) && isset($tier['min_pax'], $tier['max_pax'])) {
                $tiers[] = $tier;
            }
        }

        if ($tiers === []) {
            return null;
        }

        // Cocok persis. Urutan asli dipertahankan: bila dua tier bertumpuk,
        // yang ditulis lebih dulu yang menang — sama seperti sebelumnya.
        foreach ($tiers as $tier) {
            if ($pax >= $tier['min_pax'] && $pax <= $tier['max_pax']) {
                return $tier;
            }
        }

        $highest = $tiers[0];
        $lowest = $tiers[0];
        foreach ($tiers as $tier) {
            if ($tier['max_pax'] > $highest['max_pax']) {
                $highest = $tier;
            }
            if ($tier['min_pax'] < $lowest['min_pax']) {
                $lowest = $tier;
            }
        }

        // Rombongan lebih besar dari tier tertinggi: pakai tier tertinggi.
        if ($pax > $highest['max_pax']) {
            return $highest;
        }

        // Lebih kecil dari tier terendah: pakai tier terendah.
        if ($pax < $lowest['min_pax']) {
            return $lowest;
        }

        // Jatuh di celah: pakai tier terdekat DI BAWAHNYA. Diskon grosir baru
        // berlaku setelah ambangnya benar-benar tercapai — 10 pax di antara
        // tier 1-9 dan 11-15 membayar harga 1-9, bukan harga diskon 11-15.
        $below = null;
        foreach ($tiers as $tier) {
            if ($tier['max_pax'] < $pax && ($below === null || $tier['max_pax'] > $below['max_pax'])) {
                $below = $tier;
            }
        }

        return $below ?? $lowest;
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'cityId');
    }

    public function cities()
    {
        return $this->belongsToMany(City::class, 'city_package', 'package_id', 'city_id');
    }

    public function packageImages()
    {
        return $this->hasMany(PackageImage::class)->orderBy('sort_order');
    }

    public function amenities()
    {
        return $this->hasMany(PackageAmenity::class);
    }

    public function packageIncludes()
    {
        return $this->hasMany(PackageAmenity::class)->where('type', 'include');
    }

    public function packageExcludes()
    {
        return $this->hasMany(PackageAmenity::class)->where('type', 'exclude');
    }

    public function getFirstImageAttribute()
    {
        $images = $this->images;
        if (is_string($images)) {
            $images = json_decode($images, true);
        }

        return $this->resolveImageUrl($images[0] ?? null);
    }

    public function getImageUrlAttribute()
    {
        return $this->first_image;
    }

    public function getFormattedPriceAttribute()
    {
        return CurrencyHelper::formatPrice($this->price);
    }

    // Dynamic Localization Accessors
    public function getTranslatedNameAttribute()
    {
        $locale = app()->getLocale();
        return $this->translations[$locale]['name'] ?? $this->name;
    }

    public function getTranslatedDescriptionAttribute()
    {
        $locale = app()->getLocale();
        return $this->translations[$locale]['description'] ?? $this->description;
    }

    public function getTranslatedShortDescriptionAttribute()
    {
        $locale = app()->getLocale();
        return $this->translations[$locale]['shortDescription'] ?? $this->shortDescription;
    }

    public function getTranslatedItineraryTextAttribute()
    {
        $locale = app()->getLocale();
        return $this->translations[$locale]['itineraryText'] ?? $this->itineraryText;
    }
}
