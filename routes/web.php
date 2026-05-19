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

// Temporary route to clear cache on Hostinger
Route::get('/clear-cache-sujai', function() {
    try {
        \Artisan::call('config:clear');
        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        \Artisan::call('route:clear');
        return "
            <div style='font-family:sans-serif; padding: 2rem; background:#0f172a; color:#f1f5f9; min-height:100vh;'>
                <h1 style='color:#fbbf24;'>Cache Cleared Successfully!</h1>
                <p>Laravel configuration, cache, views, and routes have been cleared from memory and disk.</p>
                <p style='margin-top: 1.5rem;'>
                    <a href='/diagnose-sujai' style='display:inline-block; background:#38bdf8; color:#0f172a; padding: 0.5rem 1rem; border-radius: 4px; font-weight:bold; text-decoration:none; margin-right:0.5rem;'>Go to Diagnostics Page</a>
                    <a href='/setup-database-sujai' style='display:inline-block; background:#4ade80; color:#0f172a; padding: 0.5rem 1rem; border-radius: 4px; font-weight:bold; text-decoration:none; margin-right:0.5rem;'>Go to Database Setup</a>
                    <a href='/' style='display:inline-block; background:#64748b; color:white; padding: 0.5rem 1rem; border-radius: 4px; font-weight:bold; text-decoration:none;'>Ke Halaman Utama</a>
                </p>
            </div>
        ";
    } catch (\Exception $e) {
        return "Gagal membersihkan cache: " . $e->getMessage();
    }
});

// Temporary route to diagnose environment and database connection
Route::get('/diagnose-sujai', function() {
    $diagnostics = [];
    
    // 1. System Info
    $diagnostics['php_version'] = PHP_VERSION;
    $diagnostics['laravel_version'] = app()->version();
    
    // 2. Env Info
    $diagnostics['app_env'] = config('app.env');
    $diagnostics['app_debug'] = config('app.debug') ? 'true' : 'false';
    $diagnostics['app_key'] = config('app.key') ? (strlen(config('app.key')) . ' chars (' . substr(config('app.key'), 0, 15) . '...)') : 'NOT SET';
    
    // 3. Database Configurations
    $diagnostics['db_connection'] = config('database.default');
    $connectionInfo = config("database.connections.{$diagnostics['db_connection']}");
    $diagnostics['db_host'] = $connectionInfo['host'] ?? 'N/A';
    $diagnostics['db_port'] = $connectionInfo['port'] ?? 'N/A';
    $diagnostics['db_database'] = $connectionInfo['database'] ?? 'N/A';
    $diagnostics['db_username'] = $connectionInfo['username'] ?? 'N/A';
    $diagnostics['db_password_set'] = !empty($connectionInfo['password']) ? 'Yes' : 'No';
    
    // 4. Test DB Connection
    $dbStatus = "Not Connected";
    $dbError = null;
    try {
        \DB::connection()->getPdo();
        $dbStatus = "Connected Successfully!";
    } catch (\Exception $e) {
        $dbStatus = "Connection Failed";
        $dbError = $e->getMessage();
    }
    
    // 5. Folder Permissions
    $permissions = [];
    $folders = ['storage', 'storage/framework', 'storage/logs', 'bootstrap/cache'];
    foreach ($folders as $folder) {
        $path = base_path($folder);
        $permissions[$folder] = [
            'exists' => file_exists($path) ? 'Yes' : 'No',
            'writable' => is_writable($path) ? 'Yes' : 'No',
            'perms' => file_exists($path) ? substr(sprintf('%o', fileperms($path)), -4) : 'N/A'
        ];
    }
    
    // Output beautiful HTML report
    $html = "
    <div style='font-family: sans-serif; background: #0f172a; color: #f1f5f9; padding: 2rem; min-height:100vh; box-sizing:border-box;'>
    <style>
        .card { background: #1e293b; border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem; border: 1px solid #334155; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.3); }
        h1, h2 { color: #38bdf8; margin-top: 0; }
        h1 { font-size: 1.8rem; margin-bottom: 1.5rem; border-bottom: 2px solid #334155; padding-bottom: 0.5rem; }
        h2 { font-size: 1.3rem; border-bottom: 1px solid #334155; padding-bottom: 0.5rem; margin-bottom: 1rem; }
        table { width: 100%; border-collapse: collapse; margin-top: 0.5rem; }
        th, td { text-align: left; padding: 10px; border-bottom: 1px solid #334155; font-size: 14px; }
        th { color: #94a3b8; font-weight: 600; width: 30%; }
        .success { color: #4ade80; font-weight: bold; }
        .error { color: #f87171; font-weight: bold; }
        .warning { color: #fbbf24; font-weight: bold; }
        .btn { display: inline-block; background: #38bdf8; color: #0f172a; padding: 0.6rem 1.2rem; text-decoration: none; border-radius: 6px; font-weight: bold; margin-right: 0.5rem; transition: all 0.2s; font-size: 14px; }
        .btn:hover { opacity: 0.9; transform: translateY(-1px); }
        .btn-warning { background: #fbbf24; }
        .btn-danger { background: #f87171; color: white; }
        pre { background: #0b0f19; padding: 1rem; border-radius: 6px; border: 1px solid #1e293b; overflow-x: auto; font-family: monospace; font-size: 13px; }
    </style>
    <h1>🔧 Laravel Diagnostic & Troubleshooting Tool</h1>
    <p style='color:#94a3b8; margin-top:-1rem; margin-bottom: 2rem;'>Use this tool to pinpoint configuration, cache, and database issues on Hostinger hosting.</p>
    
    <div class='card'>
        <h2>⚡ Diagnostic Actions</h2>
        <div style='display:flex; flex-wrap:wrap; gap: 0.75rem;'>
            <a href='/clear-cache-sujai' class='btn btn-warning'>1. Clear Configuration & Cache</a>
            <a href='/setup-database-sujai' class='btn' style='background:#4ade80;'>2. Run Migrations & Seed</a>
            <a href='/' class='btn' style='background:#64748b; color:white;'>Ke Beranda Utama</a>
        </div>
    </div>
    
    <div class='card'>
        <h2>⚙️ System & Environment Settings</h2>
        <table>
            <tr><th>PHP Version</th><td>{$diagnostics['php_version']}</td></tr>
            <tr><td>Laravel Version</td><td>{$diagnostics['laravel_version']}</td></tr>
            <tr><td>APP_ENV</td><td>{$diagnostics['app_env']}</td></tr>
            <tr><td>APP_DEBUG</td><td>" . ($diagnostics['app_debug'] === 'true' ? "<span class='warning'>true</span> (Detailed error reporting enabled)" : "<span class='success'>false</span> (Production mode, errors hidden)") . "</td></tr>
            <tr><td>APP_KEY Status</td><td>" . (str_contains(config('app.key', ''), 'base64') ? "<span class='success'>Valid Key Configured</span>" : "<span class='error'>Invalid/No Key</span>") . " ({$diagnostics['app_key']})</td></tr>
        </table>
    </div>

    <div class='card'>
        <h2>🗄️ Database Connection Diagnostics</h2>
        <table>
            <tr><th>Driver Default</th><td>{$diagnostics['db_connection']}</td></tr>
            <tr><td>DB Host</td><td>{$diagnostics['db_host']}</td></tr>
            <tr><td>DB Port</td><td>{$diagnostics['db_port']}</td></tr>
            <tr><td>Database Name</td><td>{$diagnostics['db_database']}</td></tr>
            <tr><td>DB Username</td><td>{$diagnostics['db_username']}</td></tr>
            <tr><td>DB Password Set?</td><td>" . ($diagnostics['db_password_set'] === 'Yes' ? "<span class='success'>Yes</span>" : "<span class='error'>No</span>") . "</td></tr>
            <tr><td>PDO Connection Status</td><td>" . ($dbStatus === 'Connected Successfully!' ? "<span class='success'>$dbStatus</span>" : "<span class='error'>$dbStatus</span>") . "</td></tr>
        </table>
        " . ($dbError ? "<div style='margin-top: 1.25rem;'><strong class='error'>PDO Exception Error:</strong><pre style='color:#fca5a5; border-color:#78350f; background:#451a03;'>" . htmlspecialchars($dbError) . "</pre></div>" : "") . "
    </div>
    
    <div class='card'>
        <h2>📂 Directory & Permissions</h2>
        <table>
            <thead>
                <tr style='border-bottom:2px solid #334155; color:#cbd5e1;'>
                    <th style='width:40%; padding-bottom:5px;'>Directory</th>
                    <th style='padding-bottom:5px;'>Exists?</th>
                    <th style='padding-bottom:5px;'>Writable?</th>
                    <th style='padding-bottom:5px;'>Permissions</th>
                </tr>
            </thead>
            <tbody>";
    foreach ($permissions as $folder => $status) {
        $html .= "<tr>
            <td>$folder</td>
            <td>" . ($status['exists'] === 'Yes' ? "<span class='success'>Yes</span>" : "<span class='error'>No</span>") . "</td>
            <td>" . ($status['writable'] === 'Yes' ? "<span class='success'>Yes</span>" : "<span class='error'>No</span>") . "</td>
            <td><code>{$status['perms']}</code></td>
        </tr>";
    }
    $html .= "</tbody></table>
    </div>
    </div>
    ";
    
    return $html;
});

// Temporary route to migrate and seed database on Hostinger
Route::get('/setup-database-sujai', function() {
    try {
        // Run migrations and seed
        \Artisan::call('migrate:fresh', ['--seed' => true]);
        $output = \Artisan::output();
        return "
            <div style='font-family:sans-serif; padding: 2rem; background:#0f172a; color:#f1f5f9; min-height:100vh; box-sizing:border-box;'>
                <div style='max-w: 800px; margin: 0 auto; background: #1e293b; border-radius: 12px; padding: 2rem; border: 1px solid #334155; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.3);'>
                    <h1 style='color:#4ade80; margin-top:0; border-bottom: 2px solid #334155; padding-bottom:0.5rem;'>🎉 Sukses! Database berhasil disetup</h1>
                    <p style='color:#94a3b8;'>Database dan tabel telah berhasil dimigrasi dan di-seed dengan data paket wisata terbaru.</p>
                    
                    <h2 style='color:#38bdf8; font-size:1.2rem; margin-top:1.5rem;'>Output Konsol Artisan:</h2>
                    <pre style='background:#0b0f19; padding: 1.25rem; border-radius: 6px; border: 1px solid #334155; color:#4ade80; font-family:monospace; white-space:pre-wrap; font-size:13px; overflow-x:auto;'>" . htmlspecialchars($output) . "</pre>
                    
                    <p style='margin-top: 2rem;'>
                        <a href='/' style='display:inline-block; background:#38bdf8; color:#0f172a; padding: 0.6rem 1.2rem; border-radius: 6px; font-weight:bold; text-decoration:none; font-size:14px;'>Ke Beranda Utama</a>
                        <a href='/diagnose-sujai' style='display:inline-block; background:#64748b; color:white; padding: 0.6rem 1.2rem; border-radius: 6px; font-weight:bold; text-decoration:none; font-size:14px; margin-left:0.5rem;'>Kembali ke Diagnostik</a>
                    </p>
                </div>
            </div>
        ";
    } catch (\Exception $e) {
        return "
            <div style='font-family:sans-serif; padding: 2rem; background:#0f172a; color:#f1f5f9; min-height:100vh; box-sizing:border-box;'>
                <div style='max-w: 800px; margin: 0 auto; background: #1e293b; border-radius: 12px; padding: 2rem; border: 1px solid #334155; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.3);'>
                    <h1 style='color:#f87171; margin-top:0; border-bottom: 2px solid #334155; padding-bottom:0.5rem;'>❌ Gagal Setup Database!</h1>
                    <p style='color:#94a3b8;'>Terjadi error saat menjalankan migrasi dan seeding database. Hubungkan database dengan benar pada panel hosting terlebih dahulu.</p>
                    
                    <div style='margin-top: 1.5rem; background: #451a03; padding: 1.5rem; border-radius: 8px; border: 1px solid #78350f; color:#fca5a5;'>
                        <strong style='color:#fca5a5;'>Error Message:</strong>
                        <pre style='white-space:pre-wrap; font-family:monospace; margin-top:0.5rem; background:transparent; border:none; padding:0; color:#f87171; font-size:14px; font-weight:bold;'>" . htmlspecialchars($e->getMessage()) . "</pre>
                        
                        <strong style='display:block; margin-top:1.5rem; color:#fca5a5;'>Stack Trace (Alur Error):</strong>
                        <pre style='white-space:pre-wrap; font-family:monospace; margin-top:0.5rem; font-size:11px; color:#fca5a5; opacity:0.8; height: 250px; overflow-y: scroll; background:#1e1b1b; padding:10px; border-radius:4px; border: 1px solid #3a1505;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>
                    </div>
                    
                    <p style='margin-top: 2rem;'>
                        <a href='/diagnose-sujai' style='display:inline-block; background:#38bdf8; color:#0f172a; padding: 0.6rem 1.2rem; border-radius: 6px; font-weight:bold; text-decoration:none; font-size:14px; margin-right:0.5rem;'>🔧 Masuk ke Diagnostik</a>
                        <a href='/clear-cache-sujai' style='display:inline-block; background:#fbbf24; color:#0f172a; padding: 0.6rem 1.2rem; border-radius: 6px; font-weight:bold; text-decoration:none; font-size:14px;'>⚡ Hapus Cache & Coba Lagi</a>
                    </p>
                </div>
            </div>
        ";
    }
});
