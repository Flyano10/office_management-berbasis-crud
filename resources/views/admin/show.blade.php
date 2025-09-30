@extends('layouts.app')

@section('title', 'Detail Admin')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Detail Admin</h2>
            <p class="text-muted mb-0">Informasi lengkap admin: {{ $admin->nama_admin }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.edit', $admin->id) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
            <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Admin Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user me-2"></i>Informasi Admin
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Nama Admin</label>
                                <p class="h6 mb-0">{{ $admin->nama_admin }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Email</label>
                                <p class="mb-0">{{ $admin->email }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Username</label>
                                <p class="mb-0">
                                    <span class="badge bg-light text-dark">{{ $admin->username }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Role</label>
                                <p class="mb-0">
                                    @if($admin->role === 'super_admin')
                                        <span class="badge bg-danger">Super Admin</span>
                                    @else
                                        <span class="badge bg-info">Admin</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Status</label>
                                <p class="mb-0">
                                    @if($admin->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">ID Admin</label>
                                <p class="mb-0">{{ $admin->id }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Information -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock me-2"></i>Informasi Aktivitas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Dibuat</label>
                                <p class="mb-0">{{ $admin->created_at->format('d/m/Y H:i') }}</p>
                                <small class="text-muted">{{ $admin->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Terakhir Diupdate</label>
                                <p class="mb-0">{{ $admin->updated_at->format('d/m/Y H:i') }}</p>
                                <small class="text-muted">{{ $admin->updated_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Login Terakhir</label>
                                @if($admin->last_login)
                                    <p class="mb-0">{{ $admin->last_login->format('d/m/Y H:i') }}</p>
                                    <small class="text-muted">{{ $admin->last_login->diffForHumans() }}</small>
                                @else
                                    <p class="mb-0 text-muted">Belum pernah login</p>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Durasi Akun</label>
                                <p class="mb-0">{{ $admin->created_at->diffInDays(now()) }} hari</p>
                                <small class="text-muted">Sejak akun dibuat</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>Aksi Cepat
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.edit', $admin->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Edit Admin
                        </a>
                        @if($admin->role !== 'super_admin')
                            <form action="{{ route('admin.toggle-status', $admin->id) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="btn {{ $admin->is_active ? 'btn-secondary' : 'btn-success' }} w-100"
                                        data-action="{{ $admin->is_active ? 'menonaktifkan' : 'mengaktifkan' }}"
                                        onclick="return confirmToggle(this)">
                                    <i class="fas {{ $admin->is_active ? 'fa-ban' : 'fa-check' }} me-2"></i>
                                    {{ $admin->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                            <form action="{{ route('admin.destroy', $admin->id) }}" method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus admin ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fas fa-trash me-2"></i>Hapus Admin
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Role Information -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-shield-alt me-2"></i>Informasi Role
                    </h5>
                </div>
                <div class="card-body">
                    @if($admin->role === 'super_admin')
                        <div class="alert alert-danger">
                            <i class="fas fa-crown me-2"></i>
                            <strong>Super Admin</strong>
                            <ul class="list-unstyled small mt-2 mb-0">
                                <li>• Akses penuh ke semua fitur</li>
                                <li>• Dapat mengelola admin lain</li>
                                <li>• Tidak dapat dihapus</li>
                                <li>• Tidak dapat dinonaktifkan</li>
                            </ul>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-user me-2"></i>
                            <strong>Admin</strong>
                            <ul class="list-unstyled small mt-2 mb-0">
                                <li>• Akses ke semua fitur</li>
                                <li>• Dapat mengelola data</li>
                                <li>• Tidak dapat mengelola admin lain</li>
                                <li>• Dapat dihapus atau dinonaktifkan</li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Security Information -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-lock me-2"></i>Informasi Keamanan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Password</label>
                        <p class="mb-0">
                            <span class="badge bg-warning">Terenskripsi</span>
                        </p>
                        <small class="text-muted">Password dienkripsi menggunakan Hash</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Status Akun</label>
                        <p class="mb-0">
                            @if($admin->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Tidak Aktif</span>
                            @endif
                        </p>
                    </div>
                    @if($admin->last_login)
                        <div class="mb-3">
                            <label class="form-label text-muted">Aktivitas Terakhir</label>
                            <p class="mb-0">{{ $admin->last_login->format('d/m/Y H:i') }}</p>
                            <small class="text-muted">{{ $admin->last_login->diffForHumans() }}</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmToggle(button) {
    const action = button.getAttribute('data-action');
    return confirm('Yakin ingin ' + action + ' admin ini?');
}
</script>
@endsection
