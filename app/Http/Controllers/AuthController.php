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
use App\Services\MFAService;

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
            
            // Check if MFA is enabled
            if ($admin->mfa_enabled) {
                // Store admin ID in session for MFA verification
                session(['mfa_pending_admin_id' => $admin->id]);
                Auth::guard('admin')->logout(); // Logout temporary until MFA verified
                
                return redirect()->route('mfa.verify')
                    ->with('info', 'Masukkan kode autentikasi dari aplikasi authenticator Anda.');
            }
            
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

    /**
     * Show MFA verification page
     */
    public function showMFAVerify()
    {
        if (!session('mfa_pending_admin_id')) {
            return redirect()->route('login')
                ->with('error', 'Sesi verifikasi MFA tidak ditemukan. Silakan login kembali.');
        }

        return view('auth.mfa-verify');
    }

    /**
     * Verify MFA code
     */
    public function verifyMFA(Request $request, MFAService $mfaService)
    {
        $request->validate([
            'mfa_code' => 'required|string|size:6',
        ]);

        $adminId = session('mfa_pending_admin_id');
        
        if (!$adminId) {
            return redirect()->route('login')
                ->with('error', 'Sesi verifikasi MFA tidak ditemukan. Silakan login kembali.');
        }

        $admin = Admin::find($adminId);
        
        if (!$admin || !$admin->mfa_enabled) {
            session()->forget('mfa_pending_admin_id');
            return redirect()->route('login')
                ->with('error', 'Admin tidak ditemukan atau MFA tidak aktif.');
        }

        // Check rate limiting for MFA attempts
        $key = 'rate_limit:mfa:' . $request->ip() . ':' . $adminId;
        $maxAttempts = 5;
        $decayMinutes = 15;

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'mfa_code' => "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik."
            ]);
        }

        // Verify MFA code
        if ($mfaService->verifyOTP($admin, $request->mfa_code)) {
            // Clear rate limiting
            RateLimiter::clear($key);
            
            // Clear MFA pending session
            session()->forget('mfa_pending_admin_id');
            
            // Login admin
            Auth::guard('admin')->login($admin);
            
            // Log successful login
            AuditLogService::logLogin($admin, $request);
            
            // Update last login
            $admin->update(['last_login' => now()]);
            
            // Handle concurrent session limit
            $this->handleConcurrentSessionLimit($admin, $request);
            
            return redirect()->intended(route('dashboard'))
                ->with('success', 'Login berhasil! Selamat datang, ' . $admin->nama_admin . '!');
        }

        // Record failed attempt
        RateLimiter::hit($key, $decayMinutes * 60);
        
        Log::warning('MFA verification failed', [
            'admin_id' => $adminId,
            'ip' => $request->ip(),
            'attempts' => RateLimiter::attempts($key),
        ]);

        return back()->withErrors(['mfa_code' => 'Kode autentikasi tidak valid.']);
    }

    /**
     * Show MFA setup page
     */
    public function showMFASetup()
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin) {
            return redirect()->route('login');
        }

        $mfaService = app(MFAService::class);
        $qrCodeInline = null;
        $secret = null;
        $backupCodes = [];

        // If MFA not enabled, generate new secret
        if (!$admin->mfa_enabled) {
            $secret = $mfaService->generateSecret();
            $backupCodes = $mfaService->generateBackupCodes();
            $qrCodeInline = $mfaService->getQRCodeInline($admin, $secret);
        }

        return view('auth.mfa-setup', [
            'admin' => $admin,
            'qrCodeInline' => $qrCodeInline,
            'secret' => $secret,
            'backupCodes' => $backupCodes,
        ]);
    }

    /**
     * Enable MFA
     */
    public function enableMFA(Request $request, MFAService $mfaService)
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin) {
            return redirect()->route('login');
        }

        $request->validate([
            'mfa_code' => 'required|string|size:6',
            'secret' => 'required|string',
            'backup_codes' => 'required|string', // JSON string dari form
        ]);

        // Decode backup codes dari JSON
        $backupCodes = json_decode($request->backup_codes, true);
        
        if (!is_array($backupCodes) || empty($backupCodes)) {
            return back()->withErrors(['mfa_code' => 'Backup codes tidak valid. Silakan refresh halaman dan coba lagi.']);
        }

        // Verify the code before enabling
        $tempAdmin = clone $admin;
        $tempAdmin->mfa_secret = $request->secret;
        $tempAdmin->mfa_enabled = false; // Pastikan false untuk setup
        
        // Clean code
        $cleanCode = preg_replace('/[^0-9]/', '', $request->mfa_code);
        
        // Log untuk debugging
        Log::info('MFA setup verification attempt', [
            'admin_id' => $admin->id,
            'username' => $admin->username,
            'secret_length' => strlen($request->secret),
            'secret_preview' => substr($request->secret, 0, 4) . '...',
            'code_length' => strlen($cleanCode),
            'original_code' => $request->mfa_code,
            'clean_code' => $cleanCode,
        ]);
        
        // Skip enabled check karena sedang setup
        if (!$mfaService->verifyOTP($tempAdmin, $cleanCode, true)) {
            Log::error('MFA setup verification failed', [
                'admin_id' => $admin->id,
                'username' => $admin->username,
                'secret_length' => strlen($request->secret),
                'code' => $cleanCode,
            ]);
            return back()->withErrors(['mfa_code' => 'Kode autentikasi tidak valid. Pastikan: 1) Kode 6 digit dari aplikasi authenticator, 2) Kode belum expired (berubah setiap 30 detik), 3) Waktu HP dan server sudah sinkron.']);
        }
        
        Log::info('MFA setup verification successful', [
            'admin_id' => $admin->id,
            'username' => $admin->username,
        ]);

        // Enable MFA
        $mfaService->enableMFA($admin, $request->secret, $backupCodes);

        return redirect()->route('mfa.setup')
            ->with('success', 'MFA berhasil diaktifkan! Simpan backup codes Anda dengan aman.');
    }

    /**
     * Disable MFA
     */
    public function disableMFA(Request $request, MFAService $mfaService)
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin) {
            return redirect()->route('login');
        }

        $request->validate([
            'password' => 'required|string',
        ]);

        // Verify password before disabling MFA
        if (!Hash::check($request->password, $admin->password)) {
            return back()->withErrors(['password' => 'Password tidak valid.']);
        }

        $mfaService->disableMFA($admin);

        return redirect()->route('mfa.setup')
            ->with('success', 'MFA berhasil dinonaktifkan.');
    }

    /**
     * Regenerate backup codes
     */
    public function regenerateBackupCodes(Request $request, MFAService $mfaService)
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin || !$admin->mfa_enabled) {
            return redirect()->route('login');
        }

        $request->validate([
            'password' => 'required|string',
        ]);

        // Verify password
        if (!Hash::check($request->password, $admin->password)) {
            return back()->withErrors(['password' => 'Password tidak valid.']);
        }

        $backupCodes = $mfaService->regenerateBackupCodes($admin);

        return back()->with('backup_codes', $backupCodes)
            ->with('success', 'Backup codes berhasil dibuat ulang. Simpan dengan aman!');
    }

}
