<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
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

        $credentials = $request->only('username', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            $admin = Auth::guard('admin')->user();
            
            // Log login
            AuditLogService::logLogin($admin, $request);
            
            if (!$admin->is_active) {
                Auth::guard('admin')->logout();
                return back()->withErrors(['username' => 'Akun Anda tidak aktif.']);
            }

            \App\Models\Admin::where('id', $admin->id)->update(['last_login' => now()]);
            
            return redirect()->intended(route('dashboard'))
                ->with('success', 'Selamat datang, ' . $admin->nama_admin . '!');
        }

        return back()->withErrors(['username' => 'Username atau password salah.']);
    }

    public function logout(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        // Log logout
        if ($admin) {
            AuditLogService::logLogout($admin, $request);
        }
        
        Auth::guard('admin')->logout();
        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }
}
