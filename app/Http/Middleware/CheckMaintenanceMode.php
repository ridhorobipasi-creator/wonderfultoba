<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip for admin, auth, and setup/diagnostic routes
        if ($request->is('admin*') || $request->is('login*') || $request->is('logout*') || $request->is('*sujai*')) {
            return $next($request);
        }

        try {
            $setting = Setting::where('key', 'system')->first();
            if ($setting) {
                $maintenance = $setting->value['maintenance_mode'] ?? '0';

                if ($maintenance === '1') {
                    return response()->view('errors.maintenance', [], 503);
                }
            }
        } catch (\Exception $e) {
            // Silently skip if database or settings table is not ready yet
        }

        return $next($request);
    }
}
