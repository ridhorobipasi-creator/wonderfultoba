<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Setting;

// Fix cms_tour testimonials
$tourSetting = Setting::where('key', 'cms_tour')->first();
if ($tourSetting) {
    $val = $tourSetting->value;
    
    // Fix Testimonials
    if (isset($val['testimonials']) && is_array($val['testimonials'])) {
        $realImages = [
            'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200', // Female 1
            'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=200', // Male 1
            'https://images.unsplash.com/photo-1531746020798-e6953c6e8e04?w=200', // Female 2
            'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200', // Male 2
        ];
        foreach ($val['testimonials'] as $index => &$t) {
            $t['image'] = $realImages[$index % count($realImages)];
        }
    }
    
    // Fix Specialist Image
    if (isset($val['specialist_image_url'])) {
        $val['specialist_image_url'] = 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=500';
    }

    $tourSetting->value = $val;
    $tourSetting->save();
    echo "cms_tour testimonials updated.\n";
}

// Fix cms_landing
$landingSetting = Setting::where('key', 'cms_landing')->first();
if ($landingSetting) {
    $val = $landingSetting->value;
    
    // Fix statistics
    $val['stats'] = [
        ['number' => '10K+', 'label' => 'Wisatawan', 'sublabel' => 'Puas'],
        ['number' => '1.5K+', 'label' => 'Trip', 'sublabel' => 'Selesai'],
        ['number' => '50+', 'label' => 'Destinasi', 'sublabel' => 'Premium']
    ];

    $landingSetting->value = $val;
    $landingSetting->save();
    echo "cms_landing updated.\n";
}

// Fix general settings (Email & Brand)
$generalSetting = Setting::where('key', 'general')->first();
if ($generalSetting) {
    $val = $generalSetting->value;
    
    $val['site_name'] = 'Sujai Laketoba';
    $val['contact_email'] = 'info@sujailaketoba.com';

    $generalSetting->value = $val;
    $generalSetting->save();
    echo "general settings updated.\n";
}

// Also add Article 4 to terms if missing
$termsSetting = Setting::where('key', 'page_terms')->first();
if ($termsSetting) {
    $val = $termsSetting->value;
    if (isset($val['content']) && !str_contains($val['content'], 'Tanggung Jawab &amp; Asuransi')) {
        $val['content'] = str_replace(
            '<h3 class="text-slate-900 font-bold text-base mb-3 tracking-tight">5. Perubahan Jadwal</h3>',
            '<h3 class="text-slate-900 font-bold text-base mb-3 tracking-tight">4. Tanggung Jawab &amp; Asuransi</h3><p class="mb-6">Sujai Laketoba bertanggung jawab atas keselamatan dan kenyamanan tamu selama program berlangsung sesuai itinerary yang disepakati. Kami menyediakan asuransi perjalanan dasar untuk setiap tamu. Tamu disarankan untuk memiliki asuransi perjalanan pribadi yang mencakup evakuasi medis, terutama untuk tamu internasional.</p><h3 class="text-slate-900 font-bold text-base mb-3 tracking-tight">5. Perubahan Jadwal</h3>',
            $val['content']
        );
        $termsSetting->value = $val;
        $termsSetting->save();
        echo "Terms updated with Article 4.\n";
    }
} else {
    echo "No page_terms setting found.\n";
    $allKeys = Setting::pluck('key')->toArray();
    echo "Available keys: " . implode(', ', $allKeys) . "\n";
}

Illuminate\Support\Facades\Cache::flush();
echo "Cache cleared.\n";
