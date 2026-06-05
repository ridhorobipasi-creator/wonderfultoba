<?php

use App\Http\Controllers\Api\PublicApiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:60,1'])->group(function () {
    Route::post('/auth/login', [PublicApiController::class, 'login']);
    Route::get('/blogs', [PublicApiController::class, 'getBlogs']);
    Route::get('/packages', [PublicApiController::class, 'getPackages']);
    Route::get('/outbound/services', [PublicApiController::class, 'getOutboundServices']);
    Route::post('/bookings', [PublicApiController::class, 'submitBooking'])->middleware('throttle:5,1');
    Route::get('/clients', [PublicApiController::class, 'getClients']);
    Route::get('/gallery', [PublicApiController::class, 'getGallery']);
    Route::get('/cities', [PublicApiController::class, 'getCities']);
    Route::get('/package-tiers', [PublicApiController::class, 'getPackageTiers']);
    Route::get('/stats', [PublicApiController::class, 'getStats']);
});

Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::get('/auth/me', [PublicApiController::class, 'getMe']);
    Route::get('/dashboard', [PublicApiController::class, 'getDashboard']);
    // Moved behind auth: exposed customer PII (names, prices, dates) and SMTP credentials.
    Route::get('/bookings', [PublicApiController::class, 'getBookings']);
    Route::get('/settings', [PublicApiController::class, 'getSettings']);
});
