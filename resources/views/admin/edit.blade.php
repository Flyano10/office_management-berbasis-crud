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
        @php($actor = Auth::guard('admin')->user())
        @php($isSelfRestricted = $actor && in_array($actor->role, ['admin_regional','manager_bidang','staf']) && (int)$actor->id === (int)$admin->id)
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
                                        <input type="text" class="form-control @error('nama_admin') is-invalid @enderror"
                                            id="nama_admin" name="nama_admin"
                                            value="{{ old('nama_admin', $admin->nama_admin) }}" required @if($isSelfRestricted) readonly @endif>
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
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email', $admin->email) }}"
                                            required @if($isSelfRestricted) readonly @endif>
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
                                        <input type="text" class="form-control @error('username') is-invalid @enderror"
                                            id="username" name="username" value="{{ old('username', $admin->username) }}"
                                            required @if($isSelfRestricted) readonly @endif>
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
                                        @if($isSelfRestricted)
                                            <div>
                                                <span class="badge bg-secondary text-uppercase">{{ $admin->role }}</span>
                                            </div>
                                            <input type="hidden" id="role" name="role" value="{{ $admin->role }}">
                                        @else
                                            <select class="form-select @error('role') is-invalid @enderror" id="role"
                                                name="role" required onchange="toggleRegionBidangFields()">
                                                <option value="">Pilih Role</option>
                                                @if($actor && $actor->role === 'admin_regional')
                                                    <option value="manager_bidang" {{ old('role', $admin->role) == 'manager_bidang' ? 'selected' : '' }}>Manager Bidang</option>
                                                    <option value="staf" {{ old('role', $admin->role) == 'staf' ? 'selected' : '' }}>Staf</option>
                                                @elseif($actor && $actor->role === 'manager_bidang')
                                                    <option value="staf" {{ old('role', $admin->role) == 'staf' ? 'selected' : '' }}>Staf</option>
                                                @else
                                                    <option value="super_admin" {{ old('role', $admin->role) == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                                    <option value="admin_regional" {{ old('role', $admin->role) == 'admin_regional' ? 'selected' : '' }}>Admin Regional</option>
                                                    <option value="manager_bidang" {{ old('role', $admin->role) == 'manager_bidang' ? 'selected' : '' }}>Manager Bidang</option>
                                                    <option value="staf" {{ old('role', $admin->role) == 'staf' ? 'selected' : '' }}>Staf</option>
                                                @endif
                                            </select>
                                            @error('role')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Bidang and Kantor Fields -->
                            <div class="row" id="region-bidang-kantor-fields" style="display: none; @if($isSelfRestricted) display:none; @endif">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="bidang_id" class="form-label">
                                            Bidang <span class="text-danger" id="bidang-required">*</span>
                                        </label>
                                        <select class="form-select @error('bidang_id') is-invalid @enderror" id="bidang_id"
                                            name="bidang_id" @if($isSelfRestricted) disabled @endif>
                                            <option value="">Pilih Bidang</option>
                                            @foreach ($bidangs as $bidang)
                                                @if(!$actor || ($actor->role !== 'admin_regional' && $actor->role !== 'manager_bidang') || (int)($actor->bidang_id ?? 0) === (int)$bidang->id)
                                                    <option value="{{ $bidang->id }}"
                                                        {{ old('bidang_id', ($actor && in_array($actor->role,['admin_regional','manager_bidang'])) ? $actor->bidang_id : $admin->bidang_id) == $bidang->id ? 'selected' : '' }}>
                                                        {{ $bidang->nama_bidang }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('bidang_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="kantor_id" class="form-label">
                                            Kantor
                                        </label>
                                        <select class="form-select @error('kantor_id') is-invalid @enderror" id="kantor_id"
                                            name="kantor_id" @if($isSelfRestricted) disabled @endif>
                                            <option value="">Pilih Kantor</option>
                                            @foreach ($kantors as $kantor)
                                                @if(!$actor || ($actor->role !== 'admin_regional' && $actor->role !== 'manager_bidang') || (int)($actor->kantor_id ?? 0) === (int)$kantor->id)
                                                    <option value="{{ $kantor->id }}"
                                                        {{ old('kantor_id', ($actor && in_array($actor->role,['admin_regional','manager_bidang'])) ? $actor->kantor_id : $admin->kantor_id) == $kantor->id ? 'selected' : '' }}>
                                                        {{ $kantor->nama_kantor }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('kantor_id')
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
                                                id="password" name="password">
                                            <button class="btn btn-light btn-icon" type="button"
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
                                                id="password_confirmation" name="password_confirmation">
                                            <button class="btn btn-light btn-icon" type="button"
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

                            @unless($isSelfRestricted)
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                            value="1" {{ old('is_active', $admin->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Admin aktif
                                        </label>
                                    </div>
                                </div>
                            @endunless

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-outline-primary">
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
                        @if ($admin->last_login)
                            <div class="mb-3">
                                <h6 class="text-muted">Login Terakhir</h6>
                                <p class="mb-0">{{ $admin->last_login->format('d/m/Y H:i') }}</p>
                            </div>
                        @endif
                        <div class="mb-3">
                            <h6 class="text-muted">Status Saat Ini</h6>
                            <span class="badge bg-light text-dark">{{ $admin->is_active ? 'Aktif' : 'Tidak Aktif' }}</span>
                        </div>
                        <div class="mb-3">
                            <h6 class="text-muted">Role Saat Ini</h6>
                            <span class="badge bg-light text-dark text-uppercase">{{ $admin->role }}</span>
                        </div>
                    </div>
                </div>

                @if ($admin->role === 'super_admin')
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Super Admin:</strong> Admin ini memiliki akses penuh dan tidak dapat dihapus atau
                                dinonaktifkan.
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

        const isSelfRestricted = {{ $isSelfRestricted ? 'true' : 'false' }};

        function toggleRegionBidangFields() {
            const roleSelect = document.getElementById('role');
            const regionBidangFields = document.getElementById('region-bidang-kantor-fields');
            const bidangRequired = document.getElementById('bidang-required');
            const bidangSelect = document.getElementById('bidang_id');
            const kantorSelect = document.getElementById('kantor_id');

            const selectedRole = roleSelect ? roleSelect.value : 'staf';

            if (isSelfRestricted) {
                // Self edit untuk admin_regional/manager_bidang/staf: sembunyikan bidang/kantor
                regionBidangFields.style.display = 'none';
                bidangRequired.style.display = 'none';
                bidangSelect.required = false;
                kantorSelect.required = false;
                return;
            }

            if (selectedRole === 'admin_regional' || selectedRole === 'staf') {
                regionBidangFields.style.display = 'block';
                bidangRequired.style.display = (selectedRole === 'staf') ? 'inline' : 'none';
                bidangSelect.required = (selectedRole === 'staf');
                kantorSelect.required = true;
            } else {
                regionBidangFields.style.display = 'none';
                bidangRequired.style.display = 'none';
                bidangSelect.required = false;
                kantorSelect.required = false;
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleRegionBidangFields();
        });
    </script>
@endsection
