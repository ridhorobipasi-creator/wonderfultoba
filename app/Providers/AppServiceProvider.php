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
use Carbon\Carbon;
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
        // NOTE: No storage symlink is created. The 'public' disk writes directly into
        // public/storage (see config/filesystems.php), which is the folder the web server
        // serves at /storage. This avoids the unreliable symlink on shared hosting.

        // -----------------------------------------------------------------------
        // PERSISTENT UPLOAD SYMLINKS
        // User-uploaded files live in storage/app/uploads/ which is NEVER touched
        // by git or Hostinger auto-deploy. We expose them at the expected public
        // URLs by creating symlinks inside public/storage/. If a deploy wipes a
        // symlink, it is transparently re-created on the very next request.
        // -----------------------------------------------------------------------
        $this->ensureUploadSymlinks();

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

    /**
     * Ensure symlinks from public/storage/ point to the persistent upload directories
     * inside storage/app/uploads/. This is called on every boot so that even if
     * Hostinger's git auto-deploy removes the symlinks, they are re-created
     * automatically on the first request after deploy — without any manual SSH work.
     *
     * Directory map (link → target):
     *   public/storage/gallery  →  storage/app/uploads/gallery
     *   public/storage/media    →  storage/app/uploads/media
     */
    private function ensureUploadSymlinks(): void
    {
        $uploadDirs = ['gallery', 'media'];

        foreach ($uploadDirs as $dir) {
            $target = storage_path("app/uploads/{$dir}");
            $link   = public_path("storage/{$dir}");

            // 1. Ensure the real persistent target directory exists
            if (! is_dir($target)) {
                @mkdir($target, 0755, true);
            }

            // 2. If the link path is a real directory (not a symlink), migrate its
            //    contents to the persistent target then remove the directory so we
            //    can replace it with a symlink.
            if (is_dir($link) && ! is_link($link)) {
                $this->migrateDirectory($link, $target);
                // Remove the now-empty (or migrated) real directory
                @rmdir($link);
            }

            // 3. Create symlink if it does not exist (or was wiped by deploy)
            if (! file_exists($link) && ! is_link($link)) {
                @symlink($target, $link);
            }
        }
    }

    /**
     * Recursively move files from $source directory to $destination.
     * Skips files that already exist at destination (keeps newer uploads safe).
     */
    private function migrateDirectory(string $source, string $destination): void
    {
        if (! is_dir($source)) {
            return;
        }

        $items = @scandir($source);
        if (! $items) {
            return;
        }

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $srcPath  = $source  . DIRECTORY_SEPARATOR . $item;
            $destPath = $destination . DIRECTORY_SEPARATOR . $item;

            if (is_dir($srcPath)) {
                if (! is_dir($destPath)) {
                    @mkdir($destPath, 0755, true);
                }
                $this->migrateDirectory($srcPath, $destPath);
                @rmdir($srcPath); // Remove after emptying
            } else {
                // Only move if not already at destination
                if (! file_exists($destPath)) {
                    @rename($srcPath, $destPath);
                }
            }
        }
    }
}
