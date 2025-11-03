@extends('layouts.app')

@section('title', 'Edit Admin')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Edit Admin</h2>
            <p class="text-muted mb-0">Perbarui informasi admin: {{ $admin->nama_admin }}</p>
        </div>
        <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <!-- Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-edit me-2"></i>Informasi Admin
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.update', $admin->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nama_admin" class="form-label">
                                        Nama Admin <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('nama_admin') is-invalid @enderror" 
                                           id="nama_admin" 
                                           name="nama_admin" 
                                           value="{{ old('nama_admin', $admin->nama_admin) }}" 
                                           required>
                                    @error('nama_admin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">
                                        Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', $admin->email) }}" 
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="username" class="form-label">
                                        Username <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('username') is-invalid @enderror" 
                                           id="username" 
                                           name="username" 
                                           value="{{ old('username', $admin->username) }}" 
                                           required>
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Minimal 3 karakter, maksimal 50 karakter</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="role" class="form-label">
                                        Role <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('role') is-invalid @enderror" 
                                            id="role" 
                                            name="role" 
                                            required>
                                        <option value="">Pilih Role</option>
                                        <option value="admin" {{ old('role', $admin->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="super_admin" {{ old('role', $admin->role) == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">
                                        Password Baru
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password">
                                        <button class="btn btn-outline-secondary" 
                                                type="button" 
                                                onclick="togglePassword('password')">
                                            <i class="fas fa-eye" id="password-icon"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Kosongkan jika tidak ingin mengubah password</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">
                                        Konfirmasi Password Baru
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control @error('password_confirmation') is-invalid @enderror" 
                                               id="password_confirmation" 
                                               name="password_confirmation">
                                        <button class="btn btn-outline-secondary" 
                                                type="button" 
                                                onclick="togglePassword('password_confirmation')">
                                            <i class="fas fa-eye" id="password_confirmation-icon"></i>
                                        </button>
                                    </div>
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1" 
                                       {{ old('is_active', $admin->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Admin aktif
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Admin
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informasi Admin
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted">ID Admin</h6>
                        <p class="mb-0">{{ $admin->id }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted">Dibuat</h6>
                        <p class="mb-0">{{ $admin->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted">Terakhir Diupdate</h6>
                        <p class="mb-0">{{ $admin->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @if($admin->last_login)
                        <div class="mb-3">
                            <h6 class="text-muted">Login Terakhir</h6>
                            <p class="mb-0">{{ $admin->last_login->format('d/m/Y H:i') }}</p>
                        </div>
                    @endif
                    <div class="mb-3">
                        <h6 class="text-muted">Status Saat Ini</h6>
                        @if($admin->is_active)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Tidak Aktif</span>
                        @endif
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted">Role Saat Ini</h6>
                        @if($admin->role === 'super_admin')
                            <span class="badge bg-danger">Super Admin</span>
                        @else
                            <span class="badge bg-info">Admin</span>
                        @endif
                    </div>
                </div>
            </div>

            @if($admin->role === 'super_admin')
                <div class="card mt-3">
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Super Admin:</strong> Admin ini memiliki akses penuh dan tidak dapat dihapus atau dinonaktifkan.
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endsection
