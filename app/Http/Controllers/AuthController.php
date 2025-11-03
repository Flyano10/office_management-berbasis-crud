<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\Admin;
use App\Models\AdminSession;
use App\Services\AuditLogService;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Check rate limiting for login attempts
        $key = 'rate_limit:login:' . $request->ip() . ':' . $request->input('username');
        $maxAttempts = config('rate_limiting.login.max_attempts', 5);
        $decayMinutes = config('rate_limiting.login.decay_minutes', 15);

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            $message = config('rate_limiting.messages.login_too_many_attempts', 'Terlalu banyak percobaan login.');
            $message = str_replace(':seconds', $seconds, $message);
            
            // Log rate limiting violation
            Log::warning('Login rate limiting violation', [
                'ip' => $request->ip(),
                'username' => $request->input('username'),
                'attempts' => RateLimiter::attempts($key),
                'retry_after' => $seconds,
            ]);
            
            return back()->withErrors(['username' => $message]);
        }

        $credentials = $request->only('username', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            $admin = Auth::guard('admin')->user();
            
            if (!$admin->is_active) {
                Auth::guard('admin')->logout();
                // Record failed attempt for inactive account
                RateLimiter::hit($key, $decayMinutes * 60);
                return back()->withErrors(['username' => 'Akun Anda tidak aktif.']);
            }

            // Clear rate limiting on successful login
            RateLimiter::clear($key);
            
            // Log successful login
            AuditLogService::logLogin($admin, $request);
            
            // Update last login
            Admin::where('id', $admin->id)->update(['last_login' => now()]);
            
            // Handle concurrent session limit
            $this->handleConcurrentSessionLimit($admin, $request);
            
            return redirect()->intended(route('dashboard'))
                ->with('success', 'Login berhasil! Selamat datang, ' . $admin->nama_admin . '!');
        }

        // Record failed attempt
        RateLimiter::hit($key, $decayMinutes * 60);
        
        // Log failed login attempt
        Log::info('Failed login attempt', [
            'ip' => $request->ip(),
            'username' => $request->input('username'),
            'attempts' => RateLimiter::attempts($key),
        ]);

        return back()->withErrors(['username' => 'Username atau password salah.']);
    }

    /**
     * Handle concurrent session limit
     */
    private function handleConcurrentSessionLimit($admin, $request)
    {
        // Get session limits based on role
        $sessionLimit = $admin->role === 'super_admin' ? 1 : 2;
        
        // Get current active sessions
        $activeSessions = AdminSession::getActiveSessions($admin->id)->get();
        
        // If at or over limit, cleanup old sessions
        if ($activeSessions->count() >= $sessionLimit) {
            // Keep only the newest sessions (FIFO)
            $sessionsToKeep = $sessionLimit - 1; // Keep space for new session
            AdminSession::cleanupOldSessions($admin->id, $sessionsToKeep);
            
            // Log session cleanup
            Log::info('Session cleanup for admin', [
                'admin_id' => $admin->id,
                'role' => $admin->role,
                'session_limit' => $sessionLimit,
                'cleaned_sessions' => $activeSessions->count() - $sessionsToKeep
            ]);
        }
        
        // Create new session record
        AdminSession::create([
            'admin_id' => $admin->id,
            'session_id' => session()->getId(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'last_activity' => now(),
            'is_active' => true
        ]);
    }

    public function logout(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $isTimeout = $request->get('timeout', false);
        
        // Log logout
        if ($admin) {
            $reason = $isTimeout ? 'Session timeout' : 'Manual logout';
            AuditLogService::logLogout($admin, $request, $reason);
            
            // Cleanup session record
            AdminSession::where('admin_id', $admin->id)
                ->where('session_id', session()->getId())
                ->update(['is_active' => false]);
        }
        
        Auth::guard('admin')->logout();
        
        if ($isTimeout) {
            return redirect()->route('login')
                ->with('error', 'Sesi Anda telah berakhir. Silakan login kembali.')
                ->with('timeout', true);
        }
        
        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }

}
