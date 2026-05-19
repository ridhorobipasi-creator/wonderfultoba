<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CMSController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Api\SyncController;



/*
|--------------------------------------------------------------------------
| Web Routes — Sujailake Toba Tour Platform
|--------------------------------------------------------------------------
*/

// ─── Public Homepage ─────────────────────────────────────────────────────────
Route::get('/', [App\Http\Controllers\PublicController::class, 'index'])->name('index');
Route::get('/home', [App\Http\Controllers\PublicController::class, 'index'])->name('home');

// ─── Auth ─────────────────────────────────────────────────────────────────────
Route::get('/login', [App\Http\Controllers\WebAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\WebAuthController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\WebAuthController::class, 'logout'])->name('logout');
Route::post('/register', [App\Http\Controllers\WebAuthController::class, 'register'])->name('register');

// ─── API Sync (CMS realtime, no Supabase) ────────────────────────────────────
Route::get('/api/sync/version', [SyncController::class, 'getVersion'])->name('api.sync.version');

// ─── Admin Panel ──────────────────────────────────────────────────────────────
Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    // Dashboard
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index']);
    Route::get('/dashboard/stats', [App\Http\Controllers\Admin\DashboardController::class, 'stats'])->name('dashboard.stats');

    // Profile
    Route::get('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');

    // ── Paket Tour ──────────────────────────────────────────────────────────
    Route::get('packages/export', [PackageController::class, 'export'])->name('packages.export');
    Route::post('packages/bulk-destroy', [PackageController::class, 'bulkDestroy'])->name('packages.bulk-destroy');
    Route::post('packages/{package}/toggle-status', [PackageController::class, 'toggleStatus'])->name('packages.toggle-status');
    Route::resource('packages', PackageController::class);

    // ── Bookings ────────────────────────────────────────────────────────────
    Route::get('bookings/export', [App\Http\Controllers\Admin\BookingController::class, 'export'])->name('bookings.export');
    Route::post('bookings/bulk-destroy', [App\Http\Controllers\Admin\BookingController::class, 'bulkDestroy'])->name('bookings.bulk-destroy');
    Route::get('bookings/{booking}/invoice', [App\Http\Controllers\PdfController::class, 'streamInvoice'])->name('bookings.invoice');
    Route::get('bookings/{booking}/invoice/download', [App\Http\Controllers\PdfController::class, 'downloadInvoice'])->name('bookings.invoice.download');
    Route::patch('bookings/{booking}/status', [App\Http\Controllers\Admin\BookingController::class, 'updateStatus'])->name('bookings.status');
    Route::resource('bookings', App\Http\Controllers\Admin\BookingController::class);

    // ── Blog / Artikel ──────────────────────────────────────────────────────
    Route::get('blogs/export', [BlogController::class, 'export'])->name('blogs.export');
    Route::post('blogs/bulk-destroy', [BlogController::class, 'bulkDestroy'])->name('blogs.bulk-destroy');
    Route::resource('blogs', BlogController::class);

    // ── Galeri Foto ─────────────────────────────────────────────────────────
    Route::get('gallery/export', [GalleryController::class, 'export'])->name('gallery.export');
    Route::post('gallery/bulk-destroy', [GalleryController::class, 'bulkDestroy'])->name('gallery.bulk-destroy');
    Route::post('gallery/store-from-media', [GalleryController::class, 'storeFromMedia'])->name('gallery.store-from-media');
    Route::post('gallery/{gallery}/toggle-status', [GalleryController::class, 'toggleStatus'])->name('gallery.toggle-status');
    Route::resource('gallery', GalleryController::class);

    // ── Armada Mobil ────────────────────────────────────────────────────────
    Route::resource('cars', App\Http\Controllers\Admin\CarController::class);

    // ── CMS ─────────────────────────────────────────────────────────────────
    Route::get('/cms-halaman-utama', [CMSController::class, 'index'])->name('cms.index');
    Route::get('/cms-halaman-statis', [CMSController::class, 'pages'])->name('cms.pages');
    Route::post('/cms-save/{key}', [CMSController::class, 'save'])->name('cms.save');

    // ── Media Library ───────────────────────────────────────────────────────
    Route::post('media/sync', [MediaController::class, 'sync'])->name('media.sync');
    Route::post('media/move', [MediaController::class, 'move'])->name('media.move');
    Route::post('media/rename-folder', [MediaController::class, 'renameFolder'])->name('media.rename-folder');
    Route::post('media/{media}/rename', [MediaController::class, 'rename'])->name('media.rename');
    Route::post('media/bulk-delete', [MediaController::class, 'bulkDestroy'])->name('media.bulk-delete');
    Route::post('media/bulk-download', [MediaController::class, 'bulkDownload'])->name('media.bulk-download');
    Route::resource('media', MediaController::class);

    // ── Wilayah & Destinasi ─────────────────────────────────────────────────
    Route::get('cities/regencies', [App\Http\Controllers\Admin\CityController::class, 'getRegencies'])->name('cities.regencies');
    Route::resource('cities', App\Http\Controllers\Admin\CityController::class);
    Route::resource('regencies', App\Http\Controllers\Admin\RegencyController::class)->only(['index', 'edit', 'update']);

    // ── Pelanggan & Klien ───────────────────────────────────────────────────
    Route::get('customers/export', [App\Http\Controllers\Admin\CustomerController::class, 'export'])->name('customers.export');
    Route::post('customers/bulk-destroy', [App\Http\Controllers\Admin\CustomerController::class, 'bulkDestroy'])->name('customers.bulk-destroy');
    Route::resource('customers', App\Http\Controllers\Admin\CustomerController::class);
    Route::resource('clients', App\Http\Controllers\Admin\ClientController::class);

    // ── Superadmin Only ─────────────────────────────────────────────────────
    Route::middleware('role:superadmin,admin_umum')->group(function () {

        // Laporan Keuangan
        Route::get('/reports/financial', [App\Http\Controllers\Admin\ReportController::class, 'financial'])->name('reports.financial');
        Route::get('/reports/financial/export', [App\Http\Controllers\Admin\ReportController::class, 'export'])->name('reports.financial.export');

        // Pengaturan Umum
        Route::get('/settings/general', [App\Http\Controllers\Admin\GeneralSettingsController::class, 'index'])->name('settings.general.index');
        Route::post('/settings/general', [App\Http\Controllers\Admin\GeneralSettingsController::class, 'update'])->name('settings.general.update');
        Route::get('/settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
        Route::post('/settings/sitemap', [App\Http\Controllers\Admin\SettingController::class, 'generateSitemap'])->name('settings.sitemap');

        // Log Aktivitas
        Route::get('logs', [App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('logs.index');

        // Manajemen Pengguna
        Route::get('users/export', [App\Http\Controllers\Admin\UserController::class, 'export'])->name('users.export');
        Route::post('users/bulk-destroy', [App\Http\Controllers\Admin\UserController::class, 'bulkDestroy'])->name('users.bulk-destroy');
        Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    });
});

// ─── Public Tour Routes (Clean URLs) ──────────────────────────────────────────
Route::name('tour.')->group(function () {
    Route::get('/packages', [App\Http\Controllers\PublicController::class, 'tourPackages'])->name('packages');
    Route::get('/gallery', [App\Http\Controllers\PublicController::class, 'tourGallery'])->name('gallery');
    Route::get('/blog', [App\Http\Controllers\PublicController::class, 'tourBlog'])->name('blog');
    Route::get('/package/{slug}', [App\Http\Controllers\PublicController::class, 'tourPackageDetail'])->name('package.detail');
    Route::get('/blog/{slug}', [App\Http\Controllers\PublicController::class, 'tourBlogDetail'])->name('blog.detail');
    Route::get('/search', [App\Http\Controllers\PublicController::class, 'search'])->name('search');

    // Booking (rate-limited: max 5 per minute)
    Route::post('/booking/submit', [App\Http\Controllers\PublicController::class, 'submitBooking'])
        ->middleware('throttle:5,1')
        ->name('booking.submit');
});

// ─── Other Public Pages ───────────────────────────────────────────────────────
Route::get('/about', [App\Http\Controllers\PublicController::class, 'about'])->name('about');
Route::get('/contact', [App\Http\Controllers\PublicController::class, 'contact'])->name('contact');
Route::get('/terms', [App\Http\Controllers\PublicController::class, 'terms'])->name('terms');
Route::get('/privacy', [App\Http\Controllers\PublicController::class, 'privacy'])->name('privacy');
Route::get('/sewa-mobil', [App\Http\Controllers\PublicController::class, 'cars'])->name('cars.index');
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['id', 'en'])) {
        session(['locale' => $locale]);
    }
    return back();
})->name('lang.switch');

// ─── PDF / Print ──────────────────────────────────────────────────────────────
Route::get('/invoice/{code}', [App\Http\Controllers\PdfController::class, 'streamInvoice'])->name('invoice.download');
Route::get('/itinerary/{slug}', [App\Http\Controllers\PdfController::class, 'downloadItinerary'])->name('itinerary.download');

// ─── Sitemap ──────────────────────────────────────────────────────────────────
Route::get('/sitemap.xml', [App\Http\Controllers\Admin\SettingController::class, 'generateSitemap']);
