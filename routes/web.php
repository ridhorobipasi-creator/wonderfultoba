<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CMSController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Api\SyncController;
use App\Http\Controllers\PublicController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [App\Http\Controllers\PublicController::class, 'tour'])->name('index');
Route::get('/home', function () {
    return redirect()->route('index');
})->name('home');

// Auth routes
Route::get('/login', [App\Http\Controllers\WebAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\WebAuthController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\WebAuthController::class, 'logout'])->name('logout');
Route::post('/register', [App\Http\Controllers\WebAuthController::class, 'register'])->name('register');

// API Sync (Realtime without Supabase)
Route::get('/api/sync/version', [SyncController::class, 'getVersion'])->name('api.sync.version');

// Admin Group
Route::middleware(['auth', 'role:superadmin,admin_tour,admin_outbound,admin_umum'])->prefix('admin')->name('admin.')->group(function() {
    
    // Dashboard
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index']);
    Route::get('/dashboard/stats', [App\Http\Controllers\Admin\DashboardController::class, 'stats'])->name('dashboard.stats');

    // Profile
    Route::get('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');

    // Packages
    Route::get('packages/export', [PackageController::class, 'export'])->name('packages.export');
    Route::post('packages/bulk-destroy', [PackageController::class, 'bulkDestroy'])->name('packages.bulk-destroy');
    Route::post('packages/{package}/toggle-status', [PackageController::class, 'toggleStatus'])->name('packages.toggle-status');
    Route::resource('packages', PackageController::class);

    // Bookings
    Route::get('bookings/export', [App\Http\Controllers\Admin\BookingController::class, 'export'])->name('bookings.export');
    Route::post('bookings/bulk-destroy', [App\Http\Controllers\Admin\BookingController::class, 'bulkDestroy'])->name('bookings.bulk-destroy');
    Route::get('bookings/{booking}/invoice', [App\Http\Controllers\PdfController::class, 'streamInvoice'])->name('bookings.invoice');
    Route::get('bookings/{booking}/invoice/download', [App\Http\Controllers\PdfController::class, 'downloadInvoice'])->name('bookings.invoice.download');
    Route::patch('bookings/{booking}/status', [App\Http\Controllers\Admin\BookingController::class, 'updateStatus'])->name('bookings.status');
    Route::resource('bookings', App\Http\Controllers\Admin\BookingController::class);

    // Blogs
    Route::get('blogs/export', [BlogController::class, 'export'])->name('blogs.export');
    Route::post('blogs/bulk-destroy', [BlogController::class, 'bulkDestroy'])->name('blogs.bulk-destroy');
    Route::resource('blogs', BlogController::class);

    // Gallery (Public Photo Gallery)
    Route::get('gallery/export', [GalleryController::class, 'export'])->name('gallery.export');
    Route::post('gallery/bulk-destroy', [GalleryController::class, 'bulkDestroy'])->name('gallery.bulk-destroy');
    Route::post('gallery/store-from-media', [GalleryController::class, 'storeFromMedia'])->name('gallery.store-from-media');
    Route::post('gallery/{gallery}/toggle-status', [GalleryController::class, 'toggleStatus'])->name('gallery.toggle-status');
    Route::get('galleries', [GalleryController::class, 'index'])->name('galleries.index');
    Route::resource('gallery', GalleryController::class);

    // Car Management
    Route::resource('cars', App\Http\Controllers\Admin\CarController::class);

    // CMS Management
    Route::get('/cms-halaman-utama', [CMSController::class, 'index'])->name('cms.index');
    Route::get('/cms-beranda-tour', [CMSController::class, 'tour'])->name('cms.tour');
    Route::get('/cms-halaman-statis', [CMSController::class, 'pages'])->name('cms.pages');
    Route::get('/cms-outbound', [CMSController::class, 'outbound'])->name('outbound.cms');
    Route::post('/cms-save/{key}', [CMSController::class, 'save'])->name('cms.save');

    // Outbound Services (Restricted)
    Route::middleware('role:superadmin,admin_umum')->group(function() {
        Route::prefix('outbound')->name('outbound.')->group(function () {
            Route::resource('services', App\Http\Controllers\Admin\OutboundServiceController::class);
            Route::resource('videos', App\Http\Controllers\Admin\OutboundVideoController::class);
            Route::resource('locations', App\Http\Controllers\Admin\OutboundLocationController::class);
            Route::resource('tiers', App\Http\Controllers\Admin\OutboundTierController::class);
        });
        
        Route::get('/finance', [App\Http\Controllers\Admin\FinanceController::class, 'index'])->name('finance.index');
        Route::get('/finance/export', [App\Http\Controllers\Admin\FinanceController::class, 'export'])->name('finance.export');
        
        Route::get('/reports/financial', [App\Http\Controllers\Admin\ReportController::class, 'financial'])->name('reports.financial');
        Route::get('/reports/financial/export', [App\Http\Controllers\Admin\ReportController::class, 'export'])->name('reports.financial.export');
    });

    // Media Library (Global Storage)
    Route::post('media/sync', [MediaController::class, 'sync'])->name('media.sync');
    Route::post('media/move', [MediaController::class, 'move'])->name('media.move');
    Route::post('media/rename-folder', [MediaController::class, 'renameFolder'])->name('media.rename-folder');
    Route::post('media/{media}/rename', [MediaController::class, 'rename'])->name('media.rename');
    Route::post('media/bulk-delete', [MediaController::class, 'bulkDestroy'])->name('media.bulk-delete');
    Route::post('media/bulk-download', [MediaController::class, 'bulkDownload'])->name('media.bulk-download');
    Route::resource('media', MediaController::class);

    // Region Data
    Route::get('cities/regencies', [App\Http\Controllers\Admin\CityController::class, 'getRegencies'])->name('cities.regencies');
    Route::resource('cities', App\Http\Controllers\Admin\CityController::class);
    Route::resource('regencies', App\Http\Controllers\Admin\RegencyController::class)->only(['index', 'edit', 'update']);

    // Customers & Clients
    Route::get('customers/export', [App\Http\Controllers\Admin\CustomerController::class, 'export'])->name('customers.export');
    Route::post('customers/bulk-destroy', [App\Http\Controllers\Admin\CustomerController::class, 'bulkDestroy'])->name('customers.bulk-destroy');
    Route::resource('customers', App\Http\Controllers\Admin\CustomerController::class);
    Route::resource('clients', App\Http\Controllers\Admin\ClientController::class);



    // System Settings (Superadmin Only)
    Route::middleware('role:superadmin,admin_umum')->group(function() {
        Route::get('/settings/general', [App\Http\Controllers\Admin\GeneralSettingsController::class, 'index'])->name('settings.general.index');
        Route::post('/settings/general', [App\Http\Controllers\Admin\GeneralSettingsController::class, 'update'])->name('settings.general.update');
        Route::get('/settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
        Route::post('/settings/sitemap', [App\Http\Controllers\Admin\SettingController::class, 'generateSitemap'])->name('settings.sitemap');
        Route::get('logs', [App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('logs.index');
        Route::get('users/export', [App\Http\Controllers\Admin\UserController::class, 'export'])->name('users.export');
        Route::post('users/bulk-destroy', [App\Http\Controllers\Admin\UserController::class, 'bulkDestroy'])->name('users.bulk-destroy');
        Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    });
});

// Public Tour Routes
Route::prefix('tour')->name('tour.')->group(function() {
    Route::get('/', function() {
        return redirect()->route('index');
    })->name('index');
    Route::get('/packages', [App\Http\Controllers\PublicController::class, 'tourPackages'])->name('packages');
    Route::get('/gallery', [App\Http\Controllers\PublicController::class, 'tourGallery'])->name('gallery');
    Route::get('/blog', [App\Http\Controllers\PublicController::class, 'tourBlog'])->name('blog');
    Route::get('/package/{slug}', [App\Http\Controllers\PublicController::class, 'tourPackageDetail'])->name('package.detail');
    Route::get('/blog/{slug}', [App\Http\Controllers\PublicController::class, 'tourBlogDetail'])->name('blog.detail');
    
    // Booking with Rate Limiting
    Route::post('/booking/submit', [App\Http\Controllers\PublicController::class, 'submitBooking'])
        ->middleware('throttle:5,1')
        ->name('booking.submit');
});

// Outbound Routes (Redirected)
Route::prefix('outbound')->name('outbound.')->group(function() {
    Route::get('/', function() { return redirect()->route('index'); })->name('index');
    Route::get('/packages', function() { return redirect()->route('index'); })->name('packages');
    Route::get('/blog', function() { return redirect()->route('index'); })->name('blog');
});

// Other Public Pages
Route::get('/about', [App\Http\Controllers\PublicController::class, 'about'])->name('about');
Route::get('/terms', [App\Http\Controllers\PublicController::class, 'terms'])->name('terms');
Route::get('/privacy', [App\Http\Controllers\PublicController::class, 'privacy'])->name('privacy');
Route::get('/sewa-mobil', [PublicController::class, 'cars'])->name('cars.index');


// Invoice & Itinerary
Route::get('/invoice/{code}', [App\Http\Controllers\PdfController::class, 'streamInvoice'])->name('invoice.download');
Route::get('/itinerary/{slug}', [App\Http\Controllers\PdfController::class, 'downloadItinerary'])->name('itinerary.download');

// Sitemap
Route::get('/sitemap.xml', [App\Http\Controllers\Admin\SettingController::class, 'generateSitemap']);

