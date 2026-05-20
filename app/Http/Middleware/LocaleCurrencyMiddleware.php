<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LocaleCurrencyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Default locale is 'my' (Malaysia) as requested by USER
        if (!session()->has('locale')) {
            session(['locale' => 'my']);
        }

        // Set Laravel application locale
        app()->setLocale(session('locale'));

        return $next($request);
    }
}
