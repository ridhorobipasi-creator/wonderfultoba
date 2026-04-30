<?php

use App\Http\Controllers\Api\PublicApiController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/login', [PublicApiController::class, 'login']);
Route::get('/blogs', [PublicApiController::class, 'getBlogs']);
Route::get('/packages', [PublicApiController::class, 'getPackages']);
Route::get('/bookings', [PublicApiController::class, 'getBookings']);
Route::get('/cars', [PublicApiController::class, 'getCars']);
Route::get('/outbound/services', [PublicApiController::class, 'getOutboundServices']);
Route::get('/outbound/videos', [PublicApiController::class, 'getOutboundVideos']);
Route::get('/outbound/locations', [PublicApiController::class, 'getOutboundLocations']);
Route::get('/clients', [PublicApiController::class, 'getClients']);
Route::get('/gallery', [PublicApiController::class, 'getGallery']);
Route::get('/cities', [PublicApiController::class, 'getCities']);
Route::get('/package-tiers', [PublicApiController::class, 'getPackageTiers']);
Route::get('/settings', [PublicApiController::class, 'getSettings']);
Route::get('/stats', [PublicApiController::class, 'getStats']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/me', [PublicApiController::class, 'getMe']);
    Route::get('/dashboard', [PublicApiController::class, 'getDashboard']);
});
