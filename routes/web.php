<?php

use App\Http\Controllers\PDFController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\WebAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'index'])->name('index');
Route::get('/outbound', [PublicController::class, 'outbound'])->name('outbound');
Route::get('/outbound/packages', [PublicController::class, 'outboundPackages'])->name('outbound.packages');
Route::get('/outbound/blog', [PublicController::class, 'outboundBlog'])->name('outbound.blog');
Route::get('/cars', [PublicController::class, 'carRental'])->name('cars');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/terms', [PublicController::class, 'terms'])->name('terms');
Route::get('/privacy', [PublicController::class, 'privacy'])->name('privacy');
Route::get('/download/itinerary/{slug}', [PDFController::class, 'downloadItinerary'])->name('itinerary.download');
Route::get('/tour', [PublicController::class, 'tour'])->name('tour');

Route::get('/tour/packages', [PublicController::class, 'tourPackages'])->name('tour.packages');
Route::get('/tour/gallery', [PublicController::class, 'tourGallery'])->name('tour.gallery');
Route::get('/tour/blog', [PublicController::class, 'tourBlog'])->name('tour.blog');
Route::get('/tour/blog/{id}', [PublicController::class, 'tourBlogDetail'])->name('tour.blog.detail');
Route::get('/tour/package/{slug}', [PublicController::class, 'tourPackageDetail'])->name('tour.package.detail');

// Auth Routes
Route::get('/login', [WebAuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [WebAuthController::class, 'login'])->name('login');
Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');
Route::post('/register', [WebAuthController::class, 'register'])->name('register');

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/api/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'api'])->name('dashboard.api');
    
    // Bookings
    Route::resource('bookings', App\Http\Controllers\Admin\BookingController::class);
    Route::patch('bookings/{booking}/status', [App\Http\Controllers\Admin\BookingController::class, 'updateStatus'])->name('bookings.status');
    
    // Packages
    Route::resource('packages', App\Http\Controllers\Admin\PackageController::class);
    
    // Cars
    Route::resource('cars', App\Http\Controllers\Admin\CarController::class);
    
    // Users
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);

    // Blogs
    Route::resource('blogs', App\Http\Controllers\Admin\BlogController::class);

    // Cities
    Route::resource('cities', App\Http\Controllers\Admin\CityController::class);

    // Gallery
    Route::resource('gallery', App\Http\Controllers\Admin\GalleryController::class);

    // Clients
    Route::resource('clients', App\Http\Controllers\Admin\ClientController::class);

    // Outbound
    Route::prefix('outbound')->name('outbound.')->group(function () {
        Route::resource('services', App\Http\Controllers\Admin\OutboundServiceController::class);
        Route::resource('videos', App\Http\Controllers\Admin\OutboundVideoController::class);
        Route::resource('locations', App\Http\Controllers\Admin\OutboundLocationController::class);
    });

    // Settings
    Route::get('/settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
});
