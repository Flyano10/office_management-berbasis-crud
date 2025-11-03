@extends('layouts.app')

@section('title', 'Tambah Admin')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">Tambah Admin</h2>
                <p class="text-muted mb-0">Buat admin baru dengan role-based access</p>
            </div>
            <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>

        <!-- Form -->
        @php($actor = Auth::guard('admin')->user())
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-user-plus me-2"></i>Informasi Admin
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nama_admin" class="form-label">
                                            Nama Admin <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control @error('nama_admin') is-invalid @enderror"
                                            id="nama_admin" name="nama_admin" value="{{ old('nama_admin') }}" required>
                                        @error('nama_admin')
                                            <span class="badge bg-light text-dark">{{ $admin->username ?? '' }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">
                                            Email <span class="text-danger">*</span>
                                        </label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}" required>
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
                                            id="username" name="username" value="{{ old('username') }}" required>
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
                                        <select class="form-select @error('role') is-invalid @enderror" id="role"
                                            name="role" required onchange="toggleRegionBidangFields()">
                                            <option value="">Pilih Role</option>
                                            @if($actor && $actor->role === 'admin_regional')
                                                <option value="manager_bidang" {{ old('role') == 'manager_bidang' ? 'selected' : '' }}>Manager Bidang</option>
                                            @elseif($actor && $actor->role === 'manager_bidang')
                                                <option value="staf" {{ old('role','staf') == 'staf' ? 'selected' : '' }}>Staf</option>
                                            @else
                                                @php($isAdminLike = in_array($actor->role ?? '', ['super_admin','admin'], true))
                                                @if($isAdminLike)
                                                    <option value="admin_regional" {{ old('role','admin_regional') == 'admin_regional' ? 'selected' : '' }}>Admin Regional</option>
                                                @endif
                                            @endif
                                        </select>
                                        @error('role')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Bidang and Kantor Fields -->
                            <div class="row" id="region-bidang-kantor-fields" style="display: none;">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="bidang_id" class="form-label">
                                            Bidang <span class="text-danger" id="bidang-required">*</span>
                                        </label>
                                        <select class="form-select @error('bidang_id') is-invalid @enderror" id="bidang_id"
                                            name="bidang_id">
                                            <option value="">Pilih Bidang</option>
                                            @foreach ($bidangs as $bidang)
                                                @php($showBidang = true)
                                                @if($actor && $actor->role === 'manager_bidang')
                                                    @php($showBidang = (int)($bidang->id ?? 0) === (int)($actor->bidang_id ?? 0))
                                                @endif
                                                @if($showBidang)
                                                    <option value="{{ $bidang->id }}" {{ old('bidang_id') == $bidang->id ? 'selected' : '' }}>
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
                                            name="kantor_id">
                                            <option value="">Pilih Kantor</option>
                                            @foreach ($kantors as $kantor)
                                                @if(!$actor || ($actor->role !== 'admin_regional' && $actor->role !== 'manager_bidang') || (int)($actor->kantor_id ?? 0) === (int)$kantor->id)
                                                    <option value="{{ $kantor->id }}"
                                                        {{ old('kantor_id') == $kantor->id ? 'selected' : '' }}>
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
                                            Password <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                id="password" name="password" required>
                                            <button class="btn btn-light btn-icon" type="button"
                                                onclick="togglePassword('password')">
                                                <i class="fas fa-eye" id="password-icon"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Minimal 8 karakter</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">
                                            Konfirmasi Password <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="password"
                                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                                id="password_confirmation" name="password_confirmation" required>
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

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                        value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Aktifkan admin setelah dibuat
                                    </label>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-save me-2"></i>Simpan Admin
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
                            <i class="fas fa-info-circle me-2"></i>Panduan Role
                        </h5>
                    </div>
                    <div class="card-body">
                        @php($actor = Auth::guard('admin')->user())
                        @if($actor && in_array($actor->role, ['super_admin','admin']))
                        <div class="mb-3">
                            <h6 class="text-uppercase text-muted mb-2">Admin Regional</h6>
                            <ul class="list-unstyled small text-muted mb-0">
                                <li>• Wajib pilih kantor saat membuat.</li>
                                <li>• Dapat kelola Manager Bidang & Staf di kantornya.</li>
                                <li>• Tidak bisa kelola Super Admin.</li>
                            </ul>
                        </div>
                        @endif

                        @if($actor && $actor->role === 'admin_regional')
                        <div class="mb-3">
                            <h6 class="text-uppercase text-muted mb-2">Manager Bidang</h6>
                            <ul class="list-unstyled small text-muted mb-0">
                                <li>• Dibuat pada kantor Anda.</li>
                                <li>• Wajib pilih bidang.</li>
                                <li>• Dapat kelola Staf di bidangnya.</li>
                            </ul>
                        </div>
                        @endif

                        @if($actor && $actor->role === 'manager_bidang')
                        <div class="mb-3">
                            <h6 class="text-uppercase text-muted mb-2">Staf</h6>
                            <ul class="list-unstyled small text-muted mb-0">
                                <li>• Otomatis pada kantor & bidang Anda.</li>
                                <li>• Akses terbatas, hanya edit profil sendiri.</li>
                                <li>• Tidak dapat membuat admin lain.</li>
                            </ul>
                        </div>
                        @endif

                        <div class="alert alert-light border mt-3">
                            <i class="fas fa-info-circle me-2"></i>
                            <span class="small">Hanya role di atas yang tersedia sesuai peran Anda.</span>
                        </div>
                    </div>
                </div>
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

        function toggleRegionBidangFields() {
            const roleSelect = document.getElementById('role');
            const regionBidangFields = document.getElementById('region-bidang-kantor-fields');
            const bidangRequired = document.getElementById('bidang-required');
            const bidangSelect = document.getElementById('bidang_id');
            const kantorSelect = document.getElementById('kantor_id');
            const actorRole = '{{ $actor->role ?? '' }}';
            const actorKantorId = '{{ $actor->kantor_id ?? '' }}';
            const actorBidangId = '{{ $actor->bidang_id ?? '' }}';

            // Clean up hidden inputs if any
            const existingHiddenKantor = document.getElementById('hidden_kantor_id');
            const existingHiddenBidang = document.getElementById('hidden_bidang_id');
            if (existingHiddenKantor) existingHiddenKantor.remove();
            if (existingHiddenBidang) existingHiddenBidang.remove();

            const selectedRole = roleSelect.value;

            // Default hide
            regionBidangFields.style.display = 'none';
            bidangRequired.style.display = 'none';
            bidangSelect.required = false;
            kantorSelect.required = false;

            // super_admin cases
            if (selectedRole === 'admin_regional') {
                // Only kantor required; hide bidang
                regionBidangFields.style.display = 'block';
                bidangRequired.style.display = 'none';
                bidangSelect.required = false;
                kantorSelect.required = true;
                bidangSelect.closest('.col-md-6').style.display = 'none';
                // Clear bidang selection
                bidangSelect.value = '';
                return;
            }

            if (selectedRole === 'manager_bidang') {
                if (actorRole === 'admin_regional') {
                    // Kantor otomatis dari aktor; sembunyikan kantor, tampilkan bidang wajib
                    regionBidangFields.style.display = 'block';
                    bidangRequired.style.display = 'inline';
                    bidangSelect.required = true;
                    kantorSelect.required = false;
                    // Hide kantor select and inject hidden kantor_id
                    kantorSelect.closest('.col-md-6').style.display = 'none';
                    const hiddenK = document.createElement('input');
                    hiddenK.type = 'hidden';
                    hiddenK.name = 'kantor_id';
                    hiddenK.id = 'hidden_kantor_id';
                    hiddenK.value = actorKantorId;
                    kantorSelect.form.appendChild(hiddenK);
                    return;
                }
                // super_admin: both kantor & bidang required
                regionBidangFields.style.display = 'block';
                bidangRequired.style.display = 'inline';
                bidangSelect.required = true;
                kantorSelect.required = true;
                kantorSelect.closest('.col-md-6').style.display = '';
                return;
            }

            if (selectedRole === 'staf') {
                if (actorRole === 'manager_bidang') {
                    // Semuanya otomatis dari aktor; sembunyikan keduanya dan inject hidden
                    regionBidangFields.style.display = 'none';
                    const formEl = document.querySelector('form');
                    const hiddenK = document.createElement('input');
                    hiddenK.type = 'hidden';
                    hiddenK.name = 'kantor_id';
                    hiddenK.id = 'hidden_kantor_id';
                    hiddenK.value = actorKantorId;
                    formEl.appendChild(hiddenK);
                    const hiddenB = document.createElement('input');
                    hiddenB.type = 'hidden';
                    hiddenB.name = 'bidang_id';
                    hiddenB.id = 'hidden_bidang_id';
                    hiddenB.value = actorBidangId;
                    formEl.appendChild(hiddenB);
                    return;
                }
                // super_admin: staf membutuhkan bidang & kantor
                regionBidangFields.style.display = 'block';
                bidangRequired.style.display = 'inline';
                bidangSelect.required = true;
                kantorSelect.required = true;
                kantorSelect.closest('.col-md-6').style.display = '';
                return;
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleRegionBidangFields();
        });
    </script>
@endsection
