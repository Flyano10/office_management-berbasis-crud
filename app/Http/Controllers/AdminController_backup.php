<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Services\AuditLogService;
use App\Rules\StrongPasswordRule;
use App\Rules\PasswordHistoryRule;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Admin::query();

        // Fungsi pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_admin', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Fungsi sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if (in_array($sortBy, ['nama_admin', 'email', 'username', 'role', 'is_active', 'last_login', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $admins = $query->paginate(10)->appends(request()->query());

        return view('admin.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Cek apakah user saat ini super admin
            if (auth('admin')->user()->role !== 'super_admin') {
                return redirect()->back()
                    ->with('error', 'Anda tidak memiliki akses untuk membuat admin!')
                    ->with('toast', [
                        'type' => 'error',
                        'title' => 'Akses Ditolak',
                        'message' => 'Hanya Super Admin yang dapat membuat admin baru!'
                    ]);
            }

            $request->validate([
                'nama_admin' => 'required|string|max:255',
                'email' => 'required|email|unique:admin,email',
                'username' => 'required|string|unique:admin,username|min:3|max:50',
                'password' => ['required', 'string', 'confirmed', new StrongPasswordRule()],
                'role' => 'required|in:super_admin,admin',
                'is_active' => 'boolean'
            ]);

            $hashedPassword = Hash::make($request->password);
            
            $admin = Admin::create([
                'nama_admin' => $request->nama_admin,
                'email' => $request->email,
                'username' => $request->username,
                'password' => $hashedPassword,
                'role' => $request->role,
                'is_active' => $request->boolean('is_active', true),
                'password_history' => [$hashedPassword],
                'password_changed_at' => now()
            ]);

            Log::info('Admin Created:', $admin->toArray());

            // Catat log audit
            AuditLogService::logCreate($admin, $request, "Membuat admin baru: {$admin->nama_admin} ({$admin->role})");

            return redirect()->route('admin.index')
                ->with('success', 'Admin berhasil dibuat!')
                ->with('toast', [
                    'type' => 'success',
                    'title' => 'Admin Dibuat',
                    'message' => 'Admin ' . $admin->nama_admin . ' berhasil dibuat!'
                ]);

        } catch (\Exception $e) {
            Log::error('Admin Creation Error:', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal membuat admin: ' . $e->getMessage())
                ->with('toast', [
                    'type' => 'error',
                    'title' => 'Error',
                    'message' => 'Gagal membuat admin: ' . $e->getMessage()
                ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $admin = Admin::findOrFail($id);
        
        // Log audit for view
        AuditLogService::logView($admin, $request, "Melihat detail admin: {$admin->nama_admin} ({$admin->role})");
        
        return view('admin.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $admin = Admin::findOrFail($id);
        return view('admin.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Cek apakah user saat ini super admin
            if (auth('admin')->user()->role !== 'super_admin') {
                return redirect()->back()
                    ->with('error', 'Anda tidak memiliki akses untuk mengedit admin!')
                    ->with('toast', [
                        'type' => 'error',
                        'title' => 'Akses Ditolak',
                        'message' => 'Hanya Super Admin yang dapat mengedit admin!'
                    ]);
            }

            $admin = Admin::findOrFail($id);

            // Store old values for audit
            $oldValues = $admin->toArray();

            $request->validate([
                'nama_admin' => 'required|string|max:255',
                'email' => ['required', 'email', Rule::unique('admin', 'email')->ignore($id)],
                'username' => ['required', 'string', Rule::unique('admin', 'username')->ignore($id), 'min:3', 'max:50'],
                'password' => 'nullable|string|min:8|confirmed',
                'role' => 'required|in:super_admin,admin',
                'is_active' => 'boolean'
            ]);

            $updateData = [
                'nama_admin' => $request->nama_admin,
                'email' => $request->email,
                'username' => $request->username,
                'role' => $request->role,
                'is_active' => $request->boolean('is_active', true)
            ];

            // Only update password if provided
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $admin->update($updateData);

            Log::info('Admin Updated:', $admin->toArray());

            // Catat log audit
            AuditLogService::logUpdate($admin, $oldValues, $request, "Mengubah admin: {$admin->nama_admin} ({$admin->role})");

            return redirect()->route('admin.index')
                ->with('success', 'Admin berhasil diperbarui!')
                ->with('toast', [
                    'type' => 'success',
                    'title' => 'Update Berhasil',
                    'message' => 'Admin ' . $admin->nama_admin . ' berhasil diperbarui!'
                ]);

        } catch (\Exception $e) {
            Log::error('Admin Update Error:', [
                'error' => $e->getMessage(),
                'request' => $request->all(),
                'id' => $id
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui admin: ' . $e->getMessage())
                ->with('toast', [
                    'type' => 'error',
                    'title' => 'Error',
                    'message' => 'Gagal memperbarui admin: ' . $e->getMessage()
                ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        try {
            // Cek apakah user saat ini super admin
            if (auth('admin')->user()->role !== 'super_admin') {
                return redirect()->back()
                    ->with('error', 'Anda tidak memiliki akses untuk menghapus admin!')
                    ->with('toast', [
                        'type' => 'error',
                        'title' => 'Akses Ditolak',
                        'message' => 'Hanya Super Admin yang dapat menghapus admin!'
                    ]);
            }

            $admin = Admin::findOrFail($id);
            
            // Prevent deleting super admin
            if ($admin->role === 'super_admin') {
                return redirect()->route('admin.index')
                    ->with('error', 'Tidak dapat menghapus Super Admin!')
                    ->with('toast', [
                        'type' => 'error',
                        'title' => 'Error',
                        'message' => 'Tidak dapat menghapus Super Admin!'
                    ]);
            }

            $adminName = $admin->nama_admin;
            
            // Catat log audit before deletion
            AuditLogService::logDelete($admin, $request, "Menghapus admin: {$adminName} ({$admin->role})");
            
            $admin->delete();

            Log::info('Admin Deleted:', ['id' => $id, 'nama_admin' => $adminName]);

            return redirect()->route('admin.index')
                ->with('success', 'Admin berhasil dihapus!')
                ->with('toast', [
                    'type' => 'success',
                    'title' => 'Admin Dihapus',
                    'message' => 'Admin ' . $adminName . ' berhasil dihapus!'
                ]);

        } catch (\Exception $e) {
            Log::error('Admin Delete Error:', [
                'error' => $e->getMessage(),
                'id' => $id
            ]);

            return redirect()->route('admin.index')
                ->with('error', 'Gagal menghapus admin: ' . $e->getMessage())
                ->with('toast', [
                    'type' => 'error',
                    'title' => 'Error',
                    'message' => 'Gagal menghapus admin: ' . $e->getMessage()
                ]);
        }
    }

    /**
     * Toggle admin status (active/inactive)
     */
    public function toggleStatus(string $id)
    {
        try {
            // Cek apakah user saat ini super admin
            if (auth('admin')->user()->role !== 'super_admin') {
                return redirect()->back()
                    ->with('error', 'Anda tidak memiliki akses untuk mengubah status admin!')
                    ->with('toast', [
                        'type' => 'error',
                        'title' => 'Akses Ditolak',
                        'message' => 'Hanya Super Admin yang dapat mengubah status admin!'
                    ]);
            }

            $admin = Admin::findOrFail($id);
            
            // Prevent deactivating super admin
            if ($admin->role === 'super_admin' && $admin->is_active) {
                return redirect()->route('admin.index')
                    ->with('error', 'Tidak dapat menonaktifkan Super Admin!')
                    ->with('toast', [
                        'type' => 'error',
                        'title' => 'Error',
                        'message' => 'Tidak dapat menonaktifkan Super Admin!'
                    ]);
            }

            $admin->update(['is_active' => !$admin->is_active]);

            $status = $admin->is_active ? 'diaktifkan' : 'dinonaktifkan';
            
            Log::info('Admin Status Toggled:', [
                'id' => $id,
                'status' => $admin->is_active,
                'nama_admin' => $admin->nama_admin
            ]);

            return redirect()->route('admin.index')
                ->with('success', 'Admin berhasil ' . $status . '!')
                ->with('toast', [
                    'type' => 'success',
                    'title' => 'Status Diubah',
                    'message' => 'Admin ' . $admin->nama_admin . ' berhasil ' . $status . '!'
                ]);

        } catch (\Exception $e) {
            Log::error('Admin Status Toggle Error:', [
                'error' => $e->getMessage(),
                'id' => $id
            ]);

            return redirect()->route('admin.index')
                ->with('error', 'Gagal mengubah status admin: ' . $e->getMessage())
                ->with('toast', [
                    'type' => 'error',
                    'title' => 'Error',
                    'message' => 'Gagal mengubah status admin: ' . $e->getMessage()
                ]);
        }
    }
}