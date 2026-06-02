<?php

namespace App\Providers;

use App\Models\Blog;
use App\Models\Booking;
use App\Models\Package;
use App\Models\Setting;
use App\Observers\BlogObserver;
use App\Observers\PackageObserver;
use App\Observers\SettingObserver;
use App\Repositories\BookingRepository;
use App\Services\BookingService;
use App\Services\DashboardService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register Services
        $this->app->singleton(DashboardService::class);
        $this->app->singleton(BookingService::class);

        // Register Repositories
        $this->app->singleton(BookingRepository::class);
    }

    public function boot(): void
    {
        // Fix for cPanel Uploads: Auto create storage link if not exists
        if (! file_exists(public_path('storage')) && function_exists('symlink')) {
            @symlink(storage_path('app/public'), public_path('storage'));
        }

        // Register Observers
        Package::observe(PackageObserver::class);
        Blog::observe(BlogObserver::class);
        Setting::observe(SettingObserver::class);

        // Share settings globally
        if (! $this->app->runningInConsole()) {
            try {
                // Point 4: Caching Site Settings - Automatically cleared on saved() via Observer
                $decodedSettings = Cache::rememberForever('site_settings_global', function () {
                    $settings = Setting::query()
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
                    $pendingBookingsCount = Booking::where('status', 'pending')->count();
                    view()->share('pendingBookingsCount', $pendingBookingsCount);
                }
            } catch (\Exception $e) {
                // Silently fail if DB not ready
            }
        }
    }
}
