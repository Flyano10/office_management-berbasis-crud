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
        // Optimize: Select specific columns & eager load only needed relationships
        $query = Admin::with([
            'bidang:id,nama_bidang',
            'kantor:id,nama_kantor'
        ])->select('admin.*');

        // Fungsi pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_admin', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Region tidak lagi digunakan sebagai filter utama

        // Filter berdasarkan bidang (untuk staf)
        if ($request->filled('bidang_id')) {
            $query->where('bidang_id', $request->bidang_id);
        }

        // Filter berdasarkan kantor
        if ($request->filled('kantor_id')) {
            $query->where('kantor_id', $request->kantor_id);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Filter berdasarkan role current user
        $currentUser = auth('admin')->user();
        if ($currentUser->role === 'admin_regional') {
            // Lihat semua manager_bidang dan staf di kantornya + akun dirinya
            $query->where(function($q) use ($currentUser) {
                $q->where('id', $currentUser->id)
                  ->orWhere(function($q2) use ($currentUser) {
                      $q2->where('kantor_id', $currentUser->kantor_id)
                         ->whereIn('role', ['manager_bidang','staf']);
                  });
            });
        } elseif ($currentUser->role === 'manager_bidang') {
            // Lihat semua staf pada kantor+bidangnya + akun dirinya
            $query->where(function($q) use ($currentUser) {
                $q->where('id', $currentUser->id)
                  ->orWhere(function($q2) use ($currentUser) {
                      $q2->where('kantor_id', $currentUser->kantor_id)
                         ->where('role','staf')
                         ->where('bidang_id', $currentUser->bidang_id);
                  });
            });
        } elseif ($currentUser->role === 'staf') {
            // Staf hanya melihat dirinya sendiri
            $query->where('id', $currentUser->id);
        }

        // Fungsi sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        if (in_array($sortBy, ['nama_admin', 'email', 'username', 'role', 'is_active', 'last_login', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $admins = $query->paginate(10)->appends(request()->query());

        // Load data untuk filter dropdown dengan cache & select specific columns
        $regions = \Illuminate\Support\Facades\Cache::remember('admin.filter.regions', 600, function () {
            return \App\Models\Provinsi::select('id', 'nama_provinsi')->orderBy('nama_provinsi')->get();
        });
        
        $bidangs = \Illuminate\Support\Facades\Cache::remember('admin.filter.bidangs', 600, function () {
            return \App\Models\Bidang::select('id', 'nama_bidang')->orderBy('nama_bidang')->get();
        });
        
        $kantors = \Illuminate\Support\Facades\Cache::remember('admin.filter.kantors', 600, function () {
            return \App\Models\Kantor::select('id', 'nama_kantor')->orderBy('nama_kantor')->get();
        });

        return view('admin.index', compact('admins', 'regions', 'bidangs', 'kantors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $actor = auth('admin')->user();
        if ($actor && $actor->role === 'staf') {
            return redirect()->route('admin.index')
                ->with('error', 'Anda tidak memiliki akses untuk membuat admin!')
                ->with('toast', [
                    'type' => 'error',
                    'title' => 'Akses Ditolak',
                    'message' => 'Staf tidak diizinkan membuat admin.'
                ]);
        }
        // Load data untuk dropdown dengan cache & select specific columns
        $regions = \Illuminate\Support\Facades\Cache::remember('admin.filter.regions', 600, function () {
            return \App\Models\Provinsi::select('id', 'nama_provinsi')->orderBy('nama_provinsi')->get();
        });
        
        $bidangs = \Illuminate\Support\Facades\Cache::remember('admin.filter.bidangs', 600, function () {
            return \App\Models\Bidang::select('id', 'nama_bidang')->orderBy('nama_bidang')->get();
        });
        
        $kantors = \Illuminate\Support\Facades\Cache::remember('admin.filter.kantors', 600, function () {
            return \App\Models\Kantor::select('id', 'nama_kantor')->orderBy('nama_kantor')->get();
        });

        return view('admin.create', compact('regions', 'bidangs', 'kantors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $actor = auth('admin')->user();

            $request->validate([
                'nama_admin' => 'required|string|max:255',
                'email' => 'required|email|unique:admin,email',
                'username' => 'required|string|unique:admin,username|min:3|max:50',
                'password' => ['required', 'string', 'confirmed', new StrongPasswordRule()],
                'role' => 'required|in:super_admin,admin_regional,manager_bidang,staf',
                'bidang_id' => 'nullable|exists:bidang,id',
                'kantor_id' => 'nullable|exists:kantor,id',
                'is_active' => 'boolean'
            ]);

            // Validasi tambahan berdasarkan role yang akan dibuat
            // Catatan: bidang_id wajib saat membuat manager_bidang (dipilih oleh admin_regional/super_admin). 
            // Untuk staf yang dibuat oleh manager_bidang, bidang_id akan diisi otomatis dari aktor (tidak perlu input manual).
            if ($request->role === 'manager_bidang' && !$request->filled('bidang_id')) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Role ini harus memiliki bidang!')
                    ->with('toast', [
                        'type' => 'error',
                        'title' => 'Validasi Error',
                        'message' => 'Role ini harus memiliki bidang!'
                    ]);
            }

            // Otorisasi create
            if ($actor->role === 'admin_regional') {
                // admin_regional hanya boleh buat manager_bidang di kantornya
                if ($request->role !== 'manager_bidang') {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Admin Regional hanya dapat membuat akun Manager Bidang!')
                        ->with('toast', [
                            'type' => 'error',
                            'title' => 'Akses Ditolak',
                            'message' => 'Admin Regional hanya dapat membuat akun Manager Bidang!'
                        ]);
                }
                // Paksa kantor_id ke kantor admin_regional
                $request->merge(['kantor_id' => $actor->kantor_id]);
                // bidang_id wajib diisi pada manager_bidang (divalidasi di atas)
            } elseif ($actor->role === 'manager_bidang') {
                // manager_bidang hanya boleh buat staf dalam bidang+kantornya
                if ($request->role !== 'staf') {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Manager Bidang hanya dapat membuat akun Staf!')
                        ->with('toast', [
                            'type' => 'error',
                            'title' => 'Akses Ditolak',
                            'message' => 'Manager Bidang hanya dapat membuat akun Staf!'
                        ]);
                }
                $request->merge([
                    'kantor_id' => $actor->kantor_id,
                    'bidang_id' => $actor->bidang_id,
                ]);
            } elseif ($actor->role === 'admin') {
                // admin hanya boleh membuat admin_regional dan harus memilih kantor
                if ($request->role !== 'admin_regional') {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Admin hanya dapat membuat akun Admin Regional!')
                        ->with('toast', [
                            'type' => 'error',
                            'title' => 'Akses Ditolak',
                            'message' => 'Admin hanya dapat membuat akun Admin Regional!'
                        ]);
                }
                if (!$request->filled('kantor_id')) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Admin Regional harus memiliki kantor!')
                        ->with('toast', [
                            'type' => 'error',
                            'title' => 'Validasi Error',
                            'message' => 'Admin Regional harus memiliki kantor!'
                        ]);
                }
            } elseif ($actor->role !== 'super_admin') {
                return redirect()->back()
                    ->with('error', 'Anda tidak memiliki akses untuk membuat admin!')
                    ->with('toast', [
                        'type' => 'error',
                        'title' => 'Akses Ditolak',
                        'message' => 'Hanya Super Admin yang dapat membuat admin baru!'
                    ]);
            } else {
                // super_admin: hanya boleh membuat admin_regional
                if ($request->role !== 'admin_regional') {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Super Admin hanya dapat membuat akun Admin Regional!')
                        ->with('toast', [
                            'type' => 'error',
                            'title' => 'Akses Ditolak',
                            'message' => 'Super Admin hanya dapat membuat akun Admin Regional!'
                        ]);
                }
                if (!$request->filled('kantor_id')) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Admin Regional harus memiliki kantor!')
                        ->with('toast', [
                            'type' => 'error',
                            'title' => 'Validasi Error',
                            'message' => 'Admin Regional harus memiliki kantor!'
                        ]);
                }
            }

            $hashedPassword = Hash::make($request->password);

            $admin = Admin::create([
                'nama_admin' => $request->nama_admin,
                'email' => $request->email,
                'username' => $request->username,
                'password' => $hashedPassword,
                'role' => $request->role,
                'bidang_id' => $request->bidang_id,
                'kantor_id' => $request->kantor_id,
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
        $actor = auth('admin')->user();
        // Optimize: Select specific columns & eager load only needed relationships
        $admin = Admin::with([
            'bidang:id,nama_bidang',
            'kantor:id,nama_kantor'
        ])->select('admin.*')->findOrFail($id);

        if ($actor && !in_array($actor->role, ['super_admin','admin'], true)) {
            if ($actor->role === 'staf') {
                if ((int)$actor->id !== (int)$admin->id) {
                    return redirect()->route('admin.index')
                        ->with('error', 'Anda tidak memiliki akses untuk melihat akun ini!')
                        ->with('toast', [
                            'type' => 'error',
                            'title' => 'Akses Ditolak',
                            'message' => 'Anda tidak memiliki akses untuk melihat akun ini!'
                        ]);
                }
            } elseif ($actor->role === 'manager_bidang') {
                $canView = ((int)$admin->id === (int)$actor->id) || (
                    $admin->role === 'staf' &&
                    (int)$admin->kantor_id === (int)$actor->kantor_id &&
                    (int)$admin->bidang_id === (int)$actor->bidang_id
                );
                if (!$canView) {
                    return redirect()->route('admin.index')
                        ->with('error', 'Anda tidak memiliki akses untuk melihat akun ini!')
                        ->with('toast', [
                            'type' => 'error',
                            'title' => 'Akses Ditolak',
                            'message' => 'Anda tidak memiliki akses untuk melihat akun ini!'
                        ]);
                }
            } elseif ($actor->role === 'admin_regional') {
                $canView = ((int)$admin->id === (int)$actor->id) || (
                    in_array($admin->role, ['manager_bidang','staf'], true) &&
                    (int)$admin->kantor_id === (int)$actor->kantor_id
                );
                if (!$canView) {
                    return redirect()->route('admin.index')
                        ->with('error', 'Anda tidak memiliki akses untuk melihat akun ini!')
                        ->with('toast', [
                            'type' => 'error',
                            'title' => 'Akses Ditolak',
                            'message' => 'Anda tidak memiliki akses untuk melihat akun ini!'
                        ]);
                }
            }
        }

        AuditLogService::logView($admin, $request, "Melihat detail admin: {$admin->nama_admin} ({$admin->role})");
        return view('admin.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $admin = Admin::findOrFail($id);

        // Cek akses berdasarkan role
        $currentUser = auth('admin')->user();
        if (in_array($currentUser->role, ['admin_regional','manager_bidang','staf'], true) && $admin->id !== $currentUser->id) {
            return redirect()->route('admin.index')
                ->with('error', 'Anda hanya dapat mengedit profil Anda sendiri!')
                ->with('toast', [
                    'type' => 'error',
                    'title' => 'Akses Ditolak',
                    'message' => 'Anda hanya dapat mengedit profil Anda sendiri!'
                ]);
        }

        // Load data untuk dropdown dengan cache & select specific columns
        $regions = \Illuminate\Support\Facades\Cache::remember('admin.filter.regions', 600, function () {
            return \App\Models\Provinsi::select('id', 'nama_provinsi')->orderBy('nama_provinsi')->get();
        });
        
        $bidangs = \Illuminate\Support\Facades\Cache::remember('admin.filter.bidangs', 600, function () {
            return \App\Models\Bidang::select('id', 'nama_bidang')->orderBy('nama_bidang')->get();
        });
        
        $kantors = \Illuminate\Support\Facades\Cache::remember('admin.filter.kantors', 600, function () {
            return \App\Models\Kantor::select('id', 'nama_kantor')->orderBy('nama_kantor')->get();
        });

        return view('admin.edit', compact('admin', 'regions', 'bidangs', 'kantors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $actor = auth('admin')->user();
            $admin = Admin::findOrFail($id);

            // Store old values for audit
            $oldValues = $admin->toArray();

            $request->validate([
                'nama_admin' => 'required|string|max:255',
                'email' => ['required', 'email', Rule::unique('admin', 'email')->ignore($id)],
                'username' => ['required', 'string', Rule::unique('admin', 'username')->ignore($id), 'min:3', 'max:50'],
                'password' => 'nullable|string|min:8|confirmed',
                'role' => 'required|in:super_admin,admin_regional,manager_bidang,staf',
                'bidang_id' => 'nullable|exists:bidang,id',
                'kantor_id' => 'nullable|exists:kantor,id',
                'is_active' => 'boolean'
            ]);

            // Validasi tambahan berdasarkan role

            if ($request->role === 'staf' && !$request->filled('bidang_id')) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Staf harus memiliki bidang!')
                    ->with('toast', [
                        'type' => 'error',
                        'title' => 'Validasi Error',
                        'message' => 'Staf harus memiliki bidang!'
                    ]);
            }

            $updateData = [
                'nama_admin' => $request->nama_admin,
                'email' => $request->email,
                'username' => $request->username,
                'role' => $request->role,
                'bidang_id' => $request->bidang_id,
                'kantor_id' => $request->kantor_id,
                'is_active' => $request->boolean('is_active', true)
            ];

            // RBAC: super_admin full; admin_regional/manager_bidang/staf hanya boleh edit dirinya sendiri dan hanya ganti password
            if (in_array($actor->role, ['admin_regional','manager_bidang','staf'], true)) {
                if ((int)$actor->id !== (int)$admin->id) {
                    return redirect()->back()
                        ->with('error', 'Anda hanya dapat mengedit profil Anda sendiri!')
                        ->with('toast', [
                            'type' => 'error',
                            'title' => 'Akses Ditolak',
                            'message' => 'Anda hanya dapat mengedit profil Anda sendiri!'
                        ]);
                }
                // Hanya izinkan perubahan password bagi role ini
                $updateData = [];
            } elseif ($actor->role !== 'super_admin') {
                return redirect()->back()
                    ->with('error', 'Anda tidak memiliki akses untuk mengedit admin!')
                    ->with('toast', [
                        'type' => 'error',
                        'title' => 'Akses Ditolak',
                        'message' => 'Hanya Super Admin yang dapat mengedit admin!'
                    ]);
            }

            // Only update password if provided
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            // Untuk role non-super_admin yang hanya boleh ganti password, cegah perubahan field lainnya
            if (in_array($actor->role, ['admin_regional','manager_bidang','staf'], true)) {
                if (!array_key_exists('password', $updateData)) {
                    return redirect()->back()
                        ->with('error', 'Tidak ada perubahan yang diizinkan selain password!')
                        ->with('toast', [
                            'type' => 'error',
                            'title' => 'Validasi',
                            'message' => 'Hanya password yang boleh diubah.'
                        ]);
                }
                $admin->update($updateData);
            } else {
                $admin->update($updateData);
            }

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
            $actor = auth('admin')->user();
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

            // Cek akses berdasarkan role
            if ($actor->role === 'staf') {
                return redirect()->route('admin.index')
                    ->with('error', 'Staf tidak dapat menghapus admin!')
                    ->with('toast', [
                        'type' => 'error',
                        'title' => 'Akses Ditolak',
                        'message' => 'Staf tidak dapat menghapus admin!'
                    ]);
            }

            if ($actor->role === 'admin_regional') {
                $inScope = (in_array($admin->role, ['manager_bidang','staf'], true)) && ($admin->kantor_id == $actor->kantor_id) && (!$actor->bidang_id || ($admin->bidang_id == $actor->bidang_id));
                if (!$inScope) {
                    return redirect()->route('admin.index')
                        ->with('error', 'Anda tidak memiliki akses untuk menghapus akun ini!')
                        ->with('toast', [
                            'type' => 'error',
                            'title' => 'Akses Ditolak',
                            'message' => 'Anda tidak memiliki akses untuk menghapus akun ini!'
                        ]);
                }
            } elseif ($actor->role === 'manager_bidang') {
                $inScope = ($admin->role === 'staf') && ($admin->kantor_id == $actor->kantor_id) && ($admin->bidang_id == $actor->bidang_id);
                if (!$inScope) {
                    return redirect()->route('admin.index')
                        ->with('error', 'Anda tidak memiliki akses untuk menghapus akun ini!')
                        ->with('toast', [
                            'type' => 'error',
                            'title' => 'Akses Ditolak',
                            'message' => 'Anda tidak memiliki akses untuk menghapus akun ini!'
                        ]);
                }
            } else if ($actor->role !== 'super_admin') {
                return redirect()->back()
                    ->with('error', 'Anda tidak memiliki akses untuk menghapus admin!')
                    ->with('toast', [
                        'type' => 'error',
                        'title' => 'Akses Ditolak',
                        'message' => 'Hanya Super Admin yang dapat menghapus admin!'
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
            $actor = auth('admin')->user();
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

            // Cek akses berdasarkan role
            if ($actor->role === 'staf') {
                return redirect()->route('admin.index')
                    ->with('error', 'Staf tidak dapat mengubah status admin!')
                    ->with('toast', [
                        'type' => 'error',
                        'title' => 'Akses Ditolak',
                        'message' => 'Staf tidak dapat mengubah status admin!'
                    ]);
            }

            if ($actor->role === 'admin_regional') {
                $inScope = (in_array($admin->role, ['manager_bidang','staf'], true)) && ($admin->kantor_id == $actor->kantor_id) && (!$actor->bidang_id || ($admin->bidang_id == $actor->bidang_id));
                if (!$inScope) {
                    return redirect()->route('admin.index')
                        ->with('error', 'Anda tidak memiliki akses untuk mengubah status akun ini!')
                        ->with('toast', [
                            'type' => 'error',
                            'title' => 'Akses Ditolak',
                            'message' => 'Anda tidak memiliki akses untuk mengubah status akun ini!'
                        ]);
                }
            } elseif ($actor->role === 'manager_bidang') {
                $inScope = ($admin->role === 'staf') && ($admin->kantor_id == $actor->kantor_id) && ($admin->bidang_id == $actor->bidang_id);
                if (!$inScope) {
                    return redirect()->route('admin.index')
                        ->with('error', 'Anda tidak memiliki akses untuk mengubah status akun ini!')
                        ->with('toast', [
                            'type' => 'error',
                            'title' => 'Akses Ditolak',
                            'message' => 'Anda tidak memiliki akses untuk mengubah status akun ini!'
                        ]);
                }
            } else if ($actor->role !== 'super_admin') {
                return redirect()->back()
                    ->with('error', 'Anda tidak memiliki akses untuk mengubah status admin!')
                    ->with('toast', [
                        'type' => 'error',
                        'title' => 'Akses Ditolak',
                        'message' => 'Hanya Super Admin yang dapat mengubah status admin!'
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
