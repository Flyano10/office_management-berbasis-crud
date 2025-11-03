@extends('layouts.app')

@section('title', 'Admin Management')
@section('page-title', 'Admin Management')
@section('page-subtitle', 'Kelola data administrator sistem')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Admin Management</h2>
            <p class="text-muted mb-0">Kelola admin dan role-based access control</p>
        </div>
        <a href="{{ route('admin.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Admin
        </a>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Cari Admin</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Nama, email, atau username...">
                </div>
                <div class="col-md-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" id="role" name="role">
                        <option value="">Semua Role</option>
                        <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="sort_by" class="form-label">Urutkan</label>
                    <select class="form-select" id="sort_by" name="sort_by">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Tanggal Dibuat</option>
                        <option value="nama_admin" {{ request('sort_by') == 'nama_admin' ? 'selected' : '' }}>Nama</option>
                        <option value="email" {{ request('sort_by') == 'email' ? 'selected' : '' }}>Email</option>
                        <option value="role" {{ request('sort_by') == 'role' ? 'selected' : '' }}>Role</option>
                        <option value="last_login" {{ request('sort_by') == 'last_login' ? 'selected' : '' }}>Login Terakhir</option>
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search me-1"></i>Filter
                    </button>
                    <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Admin Table -->
    <div class="card">
        <div class="card-body">
            @if($admins->count() > 0)
                <div class="table-responsive" style="overflow-x: auto; width: 100%;">
                    <table class="table table-hover" style="min-width: 1200px; table-layout: fixed;">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Admin</th>
                                <th>Email</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Login Terakhir</th>
                                <th>Dibuat</th>
                                <th style="width: 200px; min-width: 200px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($admins as $admin)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                {{ strtoupper(substr($admin->nama_admin, 0, 2)) }}
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $admin->nama_admin }}</h6>
                                                <small class="text-muted">ID: {{ $admin->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $admin->email }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $admin->username }}</span>
                                    </td>
                                    <td>
                                        @if($admin->role === 'super_admin')
                                            <span class="badge bg-danger">Super Admin</span>
                                        @else
                                            <span class="badge bg-info">Admin</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($admin->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($admin->last_login)
                                            <small class="text-muted">
                                                {{ $admin->last_login->format('d/m/Y H:i') }}
                                            </small>
                                        @else
                                            <small class="text-muted">Belum pernah login</small>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $admin->created_at->format('d/m/Y H:i') }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            <!-- View Button -->
                                            <a href="{{ route('admin.show', $admin->id) }}" 
                                               class="btn btn-sm btn-info text-white" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            <!-- Edit Button - Always visible -->
                                            <a href="{{ route('admin.edit', $admin->id) }}" 
                                               class="btn btn-sm btn-warning text-white" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <!-- Toggle Status Button - Only for non-super-admin -->
                                            @if($admin->role !== 'super_admin')
                                                <form action="{{ route('admin.toggle-status', $admin->id) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-sm {{ $admin->is_active ? 'btn-secondary' : 'btn-success' }} text-white"
                                                            title="{{ $admin->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                                            data-action="{{ $admin->is_active ? 'menonaktifkan' : 'mengaktifkan' }}"
                                                            onclick="return confirmToggle(this)">
                                                        <i class="fas {{ $admin->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                                    </button>
                                                </form>
                                                
                                                <!-- Delete Button - Only for non-super-admin -->
                                                <form action="{{ route('admin.destroy', $admin->id) }}" 
                                                      method="POST" class="d-inline"
                                                      onsubmit="return confirm('Yakin ingin menghapus admin ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger text-white" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <!-- Super Admin Info -->
                                                <span class="btn btn-sm btn-secondary text-white disabled" title="Super Admin tidak dapat dihapus">
                                                    <i class="fas fa-shield-alt"></i>
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Menampilkan {{ $admins->firstItem() }} - {{ $admins->lastItem() }} 
                        dari {{ $admins->total() }} admin
                    </div>
                    <div>
                        {{ $admins->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Tidak ada admin ditemukan</h5>
                    <p class="text-muted">Coba ubah filter atau tambah admin baru</p>
                    <a href="{{ route('admin.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Tambah Admin Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 12px;
    font-weight: 600;
}

/* Styling button untuk aksi admin */
.d-flex.gap-1 .btn {
    font-size: 10px;
    padding: 2px 6px;
    min-width: 24px;
    height: 24px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

/* Pastikan tabel responsive */
.table-responsive {
    overflow-x: auto;
}

/* Pastikan kolom aksi cukup lebar */
th[width="200"] {
    min-width: 200px !important;
}

/* Styling button yang compact */
.btn-sm {
    font-size: 10px !important;
    padding: 2px 6px !important;
    line-height: 1.2 !important;
}

/* Paksa kolom aksi jadi visible */
td:last-child {
    width: 200px !important;
    min-width: 200px !important;
    max-width: 200px !important;
    white-space: nowrap !important;
}

/* Pastikan button muat di kolom aksi */
td:last-child .d-flex {
    flex-wrap: wrap;
    gap: 2px;
}
</style>

<script>
function confirmToggle(button) {
    const action = button.getAttribute('data-action');
    return confirm('Yakin ingin ' + action + ' admin ini?');
}
</script>
@endsection
