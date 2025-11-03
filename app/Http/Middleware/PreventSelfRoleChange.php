<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class PreventSelfRoleChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $currentUser = Auth::guard('admin')->user();
        $targetAdminId = $request->route('admin') ?? $request->route('id');

        // Cek apakah user mencoba edit dirinya sendiri
        if ($currentUser && $targetAdminId && $currentUser->id == $targetAdminId) {
            // Cek apakah mencoba ubah role ke super_admin
            if ($request->has('role') && $request->role === 'super_admin' && $currentUser->role !== 'super_admin') {
                Log::warning('Admin attempted to elevate their own role', [
                    'user_id' => $currentUser->id,
                    'current_role' => $currentUser->role,
                    'attempted_role' => $request->role,
                    'ip' => $request->ip()
                ]);

                return redirect()->back()
                    ->with('error', 'Anda tidak dapat mengubah role diri sendiri menjadi Super Admin!')
                    ->with('toast', [
                        'type' => 'error',
                        'title' => 'Akses Ditolak',
                        'message' => 'Anda tidak dapat mengubah role diri sendiri menjadi Super Admin!'
                    ]);
            }
        }

        return $next($request);
    }
}