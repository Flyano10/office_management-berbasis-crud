@extends('layouts.app')

@section('title', 'Detail Admin')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    @php($actor = Auth::guard('admin')->user())
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Detail Admin</h2>
            <p class="text-muted mb-0">Informasi lengkap admin: {{ $admin->nama_admin }}</p>
        </div>
        <div class="d-flex gap-2">
            @if(($actor && in_array($actor->role, ['super_admin','admin'])) || ($actor && (int)$actor->id === (int)$admin->id))
            <a href="{{ route('admin.edit', $admin->id) }}" class="btn btn-outline-primary">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
            @endif
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
                                <p class="mb-0"><span class="badge bg-light text-dark text-uppercase">{{ str_replace('_',' ', $admin->role) }}</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Status</label>
                                <p class="mb-0"><span class="badge bg-light text-dark">{{ $admin->is_active ? 'Aktif' : 'Tidak Aktif' }}</span></p>
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
                        @if(($actor && in_array($actor->role, ['super_admin','admin'])) || ($actor && (int)$actor->id === (int)$admin->id))
                        <a href="{{ route('admin.edit', $admin->id) }}" class="btn btn-light btn-icon">
                            <i class="fas fa-edit me-2"></i>Edit Admin
                        </a>
                        @endif
                        @php(
                            $inScopeRegional = ($actor && $actor->role === 'admin_regional' && in_array($admin->role, ['manager_bidang','staf']) && (int)$admin->kantor_id === (int)($actor->kantor_id ?? 0))
                        )
                        @php(
                            $inScopeMB = ($actor && $actor->role === 'manager_bidang' && $admin->role === 'staf' && (int)$admin->kantor_id === (int)($actor->kantor_id ?? 0) && (int)$admin->bidang_id === (int)($actor->bidang_id ?? 0))
                        )
                        @if($admin->role !== 'super_admin' && (($actor && $actor->role === 'super_admin') || ($actor && $actor->role === 'admin_regional' && $inScopeRegional) || ($actor && $actor->role === 'manager_bidang' && $inScopeMB)))
                            <form action="{{ route('admin.toggle-status', $admin->id) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="btn btn-light btn-icon w-100"
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
                                <button type="submit" class="btn btn-light btn-icon w-100">
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
                    <div class="alert alert-light border">
                        <strong class="text-uppercase">{{ str_replace('_',' ', $admin->role) }}</strong>
                        <p class="small text-muted mb-0">Informasi singkat mengenai hak akses role.</p>
                    </div>
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
                        <p class="mb-0"><span class="badge bg-light text-dark">Terenskripsi</span></p>
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
