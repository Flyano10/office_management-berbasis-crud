<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PublicApiMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Allow requests from browser (GET requests) dan API requests
        // Hanya block jika request explicitly tidak ingin JSON dan bukan GET request
        if (!$request->isMethod('GET') && !$request->expectsJson() && !$request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Public API hanya menerima permintaan JSON.',
            ], 406);
        }

        $response = $next($request);

        // Tambahkan beberapa header keamanan dasar untuk response API publik
        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Logging ringkas untuk monitoring akses publik (tanpa spam berlebihan)
        Log::info('Public API accessed', [
            'path' => $request->path(),
            'ip' => $request->ip(),
            'user_agent' => substr($request->userAgent(), 0, 200),
        ]);

        return $response;
    }
}

