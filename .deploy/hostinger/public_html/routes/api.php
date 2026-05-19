<?php

use App\Http\Controllers\Api\PublicApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — Sujailake Toba Tour Platform
|--------------------------------------------------------------------------
*/

Route::middleware(['throttle:60,1'])->group(function () {
    // Auth
    Route::post('/auth/login', [PublicApiController::class, 'login']);

    // Tour Content
    Route::get('/blogs', [PublicApiController::class, 'getBlogs']);
    Route::get('/packages', [PublicApiController::class, 'getPackages']);
    Route::get('/gallery', [PublicApiController::class, 'getGallery']);
    Route::get('/cities', [PublicApiController::class, 'getCities']);
    Route::get('/clients', [PublicApiController::class, 'getClients']);
    Route::get('/outbound/services', [PublicApiController::class, 'getOutboundServices']);
    Route::get('/settings', [PublicApiController::class, 'getSettings']);
    Route::get('/stats', [PublicApiController::class, 'getStats']);

    // Bookings
    Route::get('/bookings', [PublicApiController::class, 'getBookings']);
    Route::post('/bookings', [PublicApiController::class, 'submitBooking'])->middleware('throttle:5,1');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/me', [PublicApiController::class, 'getMe']);
    Route::get('/dashboard', [PublicApiController::class, 'getDashboard']);
});
