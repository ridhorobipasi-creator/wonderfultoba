<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register Services
        $this->app->singleton(\App\Services\DashboardService::class);
        $this->app->singleton(\App\Services\BookingService::class);
        
        // Register Repositories
        $this->app->singleton(\App\Repositories\BookingRepository::class);
    }

    public function boot(): void
    {
        // Fix for cPanel Uploads: Auto create storage link if not exists
        if (!file_exists(public_path('storage')) && function_exists('symlink')) {
            @symlink(storage_path('app/public'), public_path('storage'));
        }

        // Register Observers
        \App\Models\Package::observe(\App\Observers\PackageObserver::class);
        \App\Models\Blog::observe(\App\Observers\BlogObserver::class);
        \App\Models\Setting::observe(\App\Observers\SettingObserver::class);

        // Share settings globally
        if (! $this->app->runningInConsole()) {
            try {
                // Point 4: Caching Site Settings for 24 hours
                $decodedSettings = \Illuminate\Support\Facades\Cache::remember('site_settings_global', 86400, function () {
                    $settings = \App\Models\Setting::query()
                        ->select(['key', 'value'])
                        ->get()
                        ->mapWithKeys(fn ($setting) => [$setting->key => $setting->value])
                        ->toArray();
                    
                    $decoded = [];
                    foreach ($settings as $key => $value) {
                        if (is_array($value)) {
                            $decoded[$key] = $value;
                        } else {
                            $decodedValue = json_decode($value, true);
                            $decoded[$key] = (json_last_error() === JSON_ERROR_NONE) ? $decodedValue : $value;
                        }
                    }
                    return $decoded;
                });
                
                view()->share('siteSettings', $decodedSettings);
                
                // Override Mail Config from Database Settings
                if (isset($decodedSettings['mail'])) {
                    $mail = $decodedSettings['mail'];
                    config([
                        'mail.mailers.smtp.host' => $mail['host'] ?? config('mail.mailers.smtp.host'),
                        'mail.mailers.smtp.port' => $mail['port'] ?? config('mail.mailers.smtp.port'),
                        'mail.mailers.smtp.encryption' => ($mail['encryption'] ?? 'none') === 'none' ? null : ($mail['encryption'] ?? config('mail.mailers.smtp.encryption')),
                        'mail.mailers.smtp.username' => $mail['username'] ?? config('mail.mailers.smtp.username'),
                        'mail.mailers.smtp.password' => $mail['password'] ?? config('mail.mailers.smtp.password'),
                        'mail.from.address' => $mail['from_address'] ?? config('mail.from.address'),
                        'mail.from.name' => $mail['from_name'] ?? config('mail.from.name'),
                    ]);

                    if (isset($mail['driver'])) {
                        config(['mail.default' => $mail['driver']]);
                    }
                }
                
                // Share pending bookings count globally for notification bell
                if (! $this->app->runningInConsole()) {
                    $pendingBookingsCount = \App\Models\Booking::where('status', 'pending')->count();
                    view()->share('pendingBookingsCount', $pendingBookingsCount);
                }
            } catch (\Exception $e) {
                // Silently fail if DB not ready
            }
        }
    }
}
