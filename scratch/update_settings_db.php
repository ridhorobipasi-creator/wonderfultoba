<?php

// Run this script using `php artisan tinker scratch/update_settings_db.php` or `include 'scratch/update_settings_db.php'` inside tinker.
// Let's load the model:
use App\Models\Setting;

$settings = Setting::all();
foreach ($settings as $setting) {
    $val = $setting->value;
    if (is_array($val)) {
        // Recursively replace strings in array
        array_walk_recursive($val, function(&$item, $key) {
            if (is_string($item)) {
                $item = str_replace(
                    ['Wonderful Toba', 'wonderfultoba', 'Wonderful Indonesia', 'Outbound & Team Building', 'Corporate Outbound', 'Outbound'],
                    ['Sujai Laketoba', 'sujailaketoba', 'Sujai Laketoba', 'Tour & Travel', 'Tour & Travel', 'Tour'],
                    $item
                );
            }
        });
        $setting->value = $val;
        $setting->save();
        echo "Updated setting key: {$setting->key}\n";
    }
}

echo "All setting records updated in DB.\n";
