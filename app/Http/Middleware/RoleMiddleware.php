<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Cek apakah user sudah login
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu!');
        }

        $user = Auth::guard('admin')->user();

        // Dukung beberapa role yang diizinkan (variadic dari route middleware param)
        $allowedRoles = array_map('trim', $roles);

        // Cek apakah user punya salah satu role yang dibutuhkan
        if (!in_array($user->role, $allowedRoles, true)) {
            // Log percobaan akses tidak sah
            Log::warning('Unauthorized access attempt', [
                'user_id' => $user->id,
                'user_role' => $user->role,
                'required_roles' => $allowedRoles,
                'url' => $request->url(),
                'ip' => $request->ip()
            ]);

            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk fitur ini!');
        }

        return $next($request);
    }
}