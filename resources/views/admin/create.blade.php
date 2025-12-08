@extends('layouts.app')

@section('title', 'Tambah Admin - PLN Icon Plus Kantor Management')
@section('page-title', 'Tambah Admin Baru')
@section('page-subtitle', 'Buat admin baru dengan role-based access')

@section('page-actions')
    <a href="{{ route('admin.index') }}" class="btn btn-modern btn-clear">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Form -->
        <div class="col-lg-8">
            <div class="admin-form-card">
                <div class="admin-form-header">
                    <h5 class="admin-form-title">
                        <i class="fas fa-user-plus"></i>
                        Form Tambah Admin
                    </h5>
                </div>
                <div class="admin-form-body">
                    @php($actor = Auth::guard('admin')->user())
                    <form action="{{ route('admin.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nama_admin" class="form-label">
                                        Nama Admin <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control modern-input @error('nama_admin') is-invalid @enderror"
                                        id="nama_admin" name="nama_admin" 
                                        placeholder="Contoh: John Doe"
                                        value="{{ old('nama_admin') }}" required>
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
                                    <input type="email" class="form-control modern-input @error('email') is-invalid @enderror"
                                        id="email" name="email" 
                                        placeholder="Contoh: john.doe@pln.co.id"
                                        value="{{ old('email') }}" required>
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
                                    <input type="text" class="form-control modern-input @error('username') is-invalid @enderror"
                                        id="username" name="username" 
                                        placeholder="Contoh: johndoe"
                                        value="{{ old('username') }}" required>
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Minimal 3 karakter, maksimal 50 karakter</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="role" class="form-label">
                                        Role <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select modern-select @error('role') is-invalid @enderror" id="role"
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
                                    <select class="form-select modern-select @error('bidang_id') is-invalid @enderror" id="bidang_id"
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
                                    <select class="form-select modern-select @error('kantor_id') is-invalid @enderror" id="kantor_id"
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
                                            class="form-control modern-input @error('password') is-invalid @enderror"
                                            id="password" name="password" 
                                            placeholder="Minimal 8 karakter"
                                            required>
                                        <button class="btn btn-light btn-icon" type="button"
                                            onclick="togglePassword('password')">
                                            <i class="fas fa-eye" id="password-icon"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Minimal 8 karakter</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">
                                        Konfirmasi Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password"
                                            class="form-control modern-input @error('password_confirmation') is-invalid @enderror"
                                            id="password_confirmation" name="password_confirmation" 
                                            placeholder="Ulangi password"
                                            required>
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

                        <div class="form-actions">
                            <button type="submit" class="btn btn-modern btn-primary">
                                <i class="fas fa-save"></i> Simpan Admin
                            </button>
                            <a href="{{ route('admin.index') }}" class="btn btn-modern btn-clear">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Panduan Pengisian -->
        <div class="col-lg-4">
            <div class="guide-card">
                <div class="guide-header">
                    <h6 class="guide-title">
                        <i class="fas fa-lightbulb"></i>
                        Panduan Pengisian
                    </h6>
                </div>
                <div class="guide-body">
                    @php($actor = Auth::guard('admin')->user())
                    @if($actor && in_array($actor->role, ['super_admin','admin']))
                    <div class="guide-section">
                        <h6 class="guide-section-title">
                            <i class="fas fa-user-shield"></i> Admin Regional
                        </h6>
                        <p class="guide-text">
                            Admin Regional wajib pilih kantor saat dibuat. Admin ini bisa kelola Manager Bidang dan Staf di kantornya, tapi tidak bisa kelola Super Admin.
                        </p>
                    </div>
                    @endif

                    @if($actor && $actor->role === 'admin_regional')
                    <div class="guide-section">
                        <h6 class="guide-section-title">
                            <i class="fas fa-user-tie"></i> Manager Bidang
                        </h6>
                        <p class="guide-text">
                            Manager Bidang akan otomatis dibuat di kantor Anda. Wajib pilih bidang, dan bisa kelola Staf di bidangnya.
                        </p>
                    </div>
                    @endif

                    @if($actor && $actor->role === 'manager_bidang')
                    <div class="guide-section">
                        <h6 class="guide-section-title">
                            <i class="fas fa-user"></i> Staf
                        </h6>
                        <p class="guide-text">
                            Staf akan otomatis pada kantor dan bidang Anda. Akses terbatas, hanya bisa edit profil sendiri dan tidak bisa membuat admin lain.
                        </p>
                    </div>
                    @endif

                    <div class="guide-section">
                        <h6 class="guide-section-title">
                            <i class="fas fa-key"></i> Password
                        </h6>
                        <p class="guide-text">
                            Password minimal 8 karakter. Pastikan password kuat dan jangan bagikan ke orang lain. Gunakan kombinasi huruf, angka, dan karakter khusus untuk keamanan lebih baik.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    :root {
        --pln-blue: #21618C;
        --pln-blue-dark: #1A4D73;
        --pln-blue-light: #2E86AB;
        --pln-blue-lighter: #E8F4F8;
        --pln-blue-bg: #F5FAFC;
        --text-dark: #1e293b;
        --text-gray: #64748b;
        --gray-border: #e2e8f0;
    }

    /* Form Card - Modern Design */
    .admin-form-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(33, 97, 140, 0.1);
        border: 1px solid rgba(33, 97, 140, 0.15);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .admin-form-header {
        background: white;
        padding: 1.25rem 1.75rem;
        border-bottom: 2px solid var(--pln-blue);
    }

    .admin-form-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--pln-blue);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .admin-form-title i {
        color: var(--pln-blue);
        font-size: 1.25rem;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--pln-blue-lighter);
        border-radius: 8px;
    }

    .admin-form-body {
        padding: 1.75rem;
    }

    /* Form Controls */
    .form-label {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .form-control,
    .form-select,
    .modern-input,
    .modern-select {
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 0.625rem 0.875rem;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        background: white;
        width: 100%;
    }

    .form-control:focus,
    .form-select:focus,
    .modern-input:focus,
    .modern-select:focus {
        border-color: var(--pln-blue);
        box-shadow: 0 0 0 3px rgba(33, 97, 140, 0.1);
        outline: none;
    }

    .form-control::placeholder,
    .modern-input::placeholder {
        color: #9ca3af;
    }

    .form-select,
    .modern-select {
        cursor: pointer;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 16px 12px;
        padding-right: 2.5rem;
    }

    .form-select:hover,
    .modern-select:hover {
        border-color: var(--pln-blue);
    }

    .is-invalid {
        border-color: #dc3545;
    }

    .is-invalid:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
    }

    .form-label .text-danger {
        color: #dc3545;
    }

    .invalid-feedback {
        display: block;
        color: #dc3545;
        font-size: 0.8rem;
        margin-top: 0.25rem;
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--gray-border);
    }

    /* Button Modern */
    .btn-modern {
        border-radius: 10px;
        padding: 0.625rem 1.25rem;
        font-weight: 600;
        transition: all 0.2s ease;
        border: 1px solid transparent;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .btn-modern:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(33, 97, 140, 0.2);
    }

    .btn-modern.btn-primary {
        background: var(--pln-blue);
        color: white;
        border: 1px solid var(--pln-blue);
        box-shadow: 0 2px 6px rgba(33, 97, 140, 0.15);
    }

    .btn-modern.btn-primary:hover {
        background: var(--pln-blue-dark);
        border-color: var(--pln-blue-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(33, 97, 140, 0.25);
    }

    .btn-modern.btn-clear {
        background: white;
        color: #64748b;
        border: 1px solid #e2e8f0;
    }

    .btn-modern.btn-clear:hover {
        background: #f8f9fa;
        color: #475569;
        border-color: #cbd5e0;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    }

    /* Guide Card */
    .guide-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(33, 97, 140, 0.1);
        border: 1px solid rgba(33, 97, 140, 0.15);
        overflow: hidden;
        position: sticky;
        top: 1rem;
    }

    .guide-header {
        background: var(--pln-blue-lighter);
        padding: 1rem 1.25rem;
        border-bottom: 2px solid var(--pln-blue);
    }

    .guide-title {
        font-size: 0.9375rem;
        font-weight: 700;
        color: var(--pln-blue);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .guide-title i {
        color: var(--pln-blue);
        font-size: 1rem;
    }

    .guide-body {
        padding: 1.25rem;
    }

    .guide-section {
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--gray-border);
    }

    .guide-section:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .guide-section-title {
        font-size: 0.875rem;
        font-weight: 700;
        color: var(--pln-blue);
        margin: 0 0 0.5rem 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .guide-section-title i {
        color: var(--pln-blue);
        font-size: 0.875rem;
    }

    .guide-text {
        font-size: 0.8125rem;
        color: var(--text-gray);
        line-height: 1.6;
        margin: 0;
    }
</style>
@endpush

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
