@extends('layouts.app')

@section('title', 'Setup MFA - PLN Icon Plus Kantor Management')
@section('page-title', 'Multi-Factor Authentication (MFA) Setup')
@section('page-subtitle', 'Tingkatkan keamanan akun dengan MFA')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="mfa-card">
                <div class="mfa-card-header">
                    <h5 class="mfa-card-title">
                        <i class="fas fa-shield-alt"></i>
                        Multi-Factor Authentication (MFA) Setup
                    </h5>
                    <p class="mfa-card-subtitle">Aktifkan MFA untuk meningkatkan keamanan akun Anda</p>
                </div>
                <div class="mfa-card-body">
                    <!-- Alert Messages -->
                    <div id="mfaAlerts"></div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if ($admin->mfa_enabled)
                        <!-- MFA Already Enabled -->
                        <div class="mfa-status-card">
                            <div class="mfa-status-header">
                                <div class="mfa-status-icon success">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div>
                                    <h6 class="mfa-status-title">MFA Sudah Diaktifkan</h6>
                                    <p class="mfa-status-subtitle">Akun Anda sudah dilindungi dengan Multi-Factor Authentication</p>
                                </div>
                            </div>
                        </div>

                        <div class="row g-4 mt-2">
                            <!-- Backup Codes Section -->
                            <div class="col-lg-6">
                                <div class="mfa-section-card">
                                    <div class="mfa-section-header">
                                        <h6 class="mfa-section-title">
                                            <i class="fas fa-key"></i>
                                            Backup Codes
                                        </h6>
                                    </div>
                                    <div class="mfa-section-body">
                                        <p class="mfa-section-description">
                                            Simpan backup codes ini dengan aman. Gunakan jika Anda kehilangan akses ke aplikasi authenticator.
                                        </p>
                                        
                                        @if ($admin->mfa_backup_codes && count($admin->mfa_backup_codes) > 0)
                                            <div class="backup-codes-box">
                                                <div class="backup-codes-header">
                                                    <strong>Sisa Backup Codes: {{ count($admin->mfa_backup_codes) }}</strong>
                                                </div>
                                                <div class="backup-codes-list">
                                                    @foreach ($admin->mfa_backup_codes as $code)
                                                        <div class="backup-code-item">
                                                            <code>{{ $code }}</code>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                Tidak ada backup codes tersisa. Silakan regenerate backup codes.
                                            </div>
                                        @endif

                                        <form method="POST" action="{{ route('mfa.regenerate-backup-codes') }}" class="mt-3">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="password_regenerate" class="form-label modern-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                                <input type="password" class="form-control modern-input @error('password') is-invalid @enderror" 
                                                       id="password_regenerate" name="password" required>
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <button type="submit" class="btn btn-modern btn-warning">
                                                <i class="fas fa-sync-alt"></i>
                                                Regenerate Backup Codes
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Disable MFA Section -->
                            <div class="col-lg-6">
                                <div class="mfa-section-card">
                                    <div class="mfa-section-header">
                                        <h6 class="mfa-section-title">
                                            <i class="fas fa-ban"></i>
                                            Nonaktifkan MFA
                                        </h6>
                                    </div>
                                    <div class="mfa-section-body">
                                        <p class="mfa-section-description">
                                            Jika Anda ingin menonaktifkan MFA, masukkan password Anda untuk konfirmasi.
                                        </p>
                                        
                                        <form method="POST" action="{{ route('mfa.disable') }}">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="password_disable" class="form-label modern-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                                <input type="password" class="form-control modern-input @error('password') is-invalid @enderror" 
                                                       id="password_disable" name="password" required>
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <button type="submit" class="btn btn-modern btn-danger" onclick="return confirm('Apakah Anda yakin ingin menonaktifkan MFA?')">
                                                <i class="fas fa-times"></i>
                                                Nonaktifkan MFA
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- MFA Setup Form -->
                        <div class="row g-4">
                            <!-- Step 1: QR Code -->
                            <div class="col-lg-6">
                                <div class="mfa-section-card">
                                    <div class="mfa-section-header">
                                        <div class="step-badge">1</div>
                                        <h6 class="mfa-section-title">
                                            <i class="fas fa-qrcode"></i>
                                            Scan QR Code
                                        </h6>
                                    </div>
                                    <div class="mfa-section-body">
                                        <p class="mfa-section-description">
                                            Buka aplikasi authenticator (Google Authenticator, Authy, Microsoft Authenticator, dll) 
                                            dan scan QR code di bawah ini:
                                        </p>
                                        
                                        <div class="qr-code-container">
                                            @if($qrCodeInline)
                                                <div class="qr-code-wrapper">
                                                    {!! $qrCodeInline !!}
                                                </div>
                                            @else
                                                <div class="alert alert-warning">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                    QR Code tidak dapat di-generate. Silakan gunakan manual entry.
                                                </div>
                                            @endif
                                        </div>

                                        <div class="manual-entry-box">
                                            <div class="manual-entry-header">
                                                <i class="fas fa-info-circle"></i>
                                                <strong>Manual Entry</strong>
                                            </div>
                                            <p style="margin: 0.5rem 0; color: var(--text-gray); font-size: 0.875rem;">
                                                Jika tidak bisa scan, masukkan kode berikut secara manual:
                                            </p>
                                            <div class="secret-code-box">
                                                <code>{{ $secret }}</code>
                                                <button type="button" class="btn-copy-secret" onclick="copySecret()" title="Copy">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2: Verification -->
                            <div class="col-lg-6">
                                <div class="mfa-section-card">
                                    <div class="mfa-section-header">
                                        <div class="step-badge">2</div>
                                        <h6 class="mfa-section-title">
                                            <i class="fas fa-check-circle"></i>
                                            Verifikasi & Aktifkan
                                        </h6>
                                    </div>
                                    <div class="mfa-section-body">
                                        <p class="mfa-section-description">
                                            Setelah menambahkan akun di aplikasi authenticator, masukkan kode 6 digit untuk verifikasi:
                                        </p>

                                        <form method="POST" action="{{ route('mfa.enable') }}">
                                            @csrf
                                            <input type="hidden" name="secret" value="{{ $secret }}">
                                            <input type="hidden" name="backup_codes" value="{{ json_encode($backupCodes) }}">

                                            <div class="mb-3">
                                                <label for="mfa_code" class="form-label modern-label">Kode Verifikasi (6 digit) <span class="text-danger">*</span></label>
                                                <input type="text" 
                                                       class="form-control modern-input mfa-code-input @error('mfa_code') is-invalid @enderror" 
                                                       id="mfa_code" 
                                                       name="mfa_code" 
                                                       placeholder="000000"
                                                       maxlength="6"
                                                       pattern="[0-9]{6}"
                                                       required
                                                       autofocus>
                                                @error('mfa_code')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <div class="form-text">Masukkan 6 digit kode dari aplikasi authenticator</div>
                                            </div>

                                            <button type="submit" class="btn btn-modern btn-primary w-100">
                                                <i class="fas fa-check"></i>
                                                Aktifkan MFA
                                            </button>
                                        </form>

                                        <!-- Backup Codes Warning -->
                                        <div class="backup-codes-warning">
                                            <div class="backup-codes-warning-header">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                <strong>Backup Codes - SIMPAN DENGAN AMAN!</strong>
                                            </div>
                                            <p style="margin: 0.75rem 0; color: var(--text-gray); font-size: 0.875rem;">
                                                Backup codes ini hanya ditampilkan sekali. Simpan di tempat yang aman. 
                                                Gunakan jika Anda kehilangan akses ke aplikasi authenticator.
                                            </p>
                                            <div class="backup-codes-box">
                                                <div class="backup-codes-list">
                                                    @foreach ($backupCodes as $code)
                                                        <div class="backup-code-item">
                                                            <code>{{ $code }}</code>
                                                            <button type="button" class="btn-copy-code" onclick="copyCode('{{ $code }}')" title="Copy">
                                                                <i class="fas fa-copy"></i>
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <small class="text-muted" style="display: block; margin-top: 0.75rem;">
                                                <i class="fas fa-info-circle"></i>
                                                Setiap backup code hanya bisa digunakan sekali.
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (session('backup_codes'))
                        <div class="alert alert-success mt-4">
                            <div class="alert-header">
                                <i class="fas fa-check-circle"></i>
                                <strong>Backup Codes Baru</strong>
                            </div>
                            <p style="margin: 0.75rem 0;">Backup codes baru telah dibuat. Simpan dengan aman!</p>
                            <div class="backup-codes-box">
                                <div class="backup-codes-list">
                                    @foreach (session('backup_codes') as $code)
                                        <div class="backup-code-item">
                                            <code>{{ $code }}</code>
                                            <button type="button" class="btn-copy-code" onclick="copyCode('{{ $code }}')" title="Copy">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
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
        --text-dark: #1A1A1A;
        --text-gray: #6C757D;
    }

    /* MFA Card */
    .mfa-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(33, 97, 140, 0.1);
        border: 1px solid rgba(33, 97, 140, 0.15);
        overflow: hidden;
    }

    .mfa-card-header {
        background: white;
        padding: 1.5rem 2rem;
        border-bottom: 2px solid var(--pln-blue);
    }

    .mfa-card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--pln-blue);
        margin: 0 0 0.5rem 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .mfa-card-title i {
        color: var(--pln-blue);
        font-size: 1.5rem;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--pln-blue-lighter);
        border-radius: 10px;
    }

    .mfa-card-subtitle {
        color: var(--text-gray);
        font-size: 0.9375rem;
        margin: 0;
    }

    .mfa-card-body {
        padding: 2rem;
    }

    /* MFA Status Card */
    .mfa-status-card {
        background: var(--pln-blue-bg);
        border: 2px solid #28a745;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .mfa-status-header {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .mfa-status-icon {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        flex-shrink: 0;
    }

    .mfa-status-icon.success {
        background: #d4edda;
        color: #28a745;
    }

    .mfa-status-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #28a745;
        margin: 0 0 0.25rem 0;
    }

    .mfa-status-subtitle {
        color: var(--text-gray);
        font-size: 0.9375rem;
        margin: 0;
    }

    /* MFA Section Card */
    .mfa-section-card {
        background: white;
        border: 2px solid rgba(33, 97, 140, 0.15);
        border-radius: 12px;
        overflow: hidden;
        height: 100%;
        transition: all 0.3s ease;
    }

    .mfa-section-card:hover {
        border-color: var(--pln-blue);
        box-shadow: 0 4px 12px rgba(33, 97, 140, 0.15);
    }

    .mfa-section-header {
        background: var(--pln-blue-lighter);
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgba(33, 97, 140, 0.2);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .step-badge {
        width: 32px;
        height: 32px;
        background: var(--pln-blue);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9375rem;
        flex-shrink: 0;
    }

    .mfa-section-title {
        font-size: 1.0625rem;
        font-weight: 700;
        color: var(--pln-blue);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .mfa-section-title i {
        color: var(--pln-blue);
        font-size: 1.125rem;
    }

    .mfa-section-body {
        padding: 1.5rem;
    }

    .mfa-section-description {
        color: var(--text-gray);
        font-size: 0.9375rem;
        margin-bottom: 1.5rem;
        line-height: 1.6;
    }

    /* QR Code Container */
    .qr-code-container {
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .qr-code-wrapper {
        display: inline-block;
        padding: 1.5rem;
        background: white;
        border: 2px solid rgba(33, 97, 140, 0.15);
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(33, 97, 140, 0.1);
    }

    /* Manual Entry Box */
    .manual-entry-box {
        background: var(--pln-blue-bg);
        border-left: 4px solid var(--pln-blue);
        border-radius: 8px;
        padding: 1.25rem;
    }

    .manual-entry-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--pln-blue);
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .secret-code-box {
        background: white;
        border: 2px solid rgba(33, 97, 140, 0.2);
        border-radius: 8px;
        padding: 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-top: 0.75rem;
    }

    .secret-code-box code {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--pln-blue);
        letter-spacing: 2px;
        flex: 1;
    }

    .btn-copy-secret,
    .btn-copy-code {
        background: var(--pln-blue);
        color: white;
        border: none;
        border-radius: 6px;
        padding: 0.5rem 0.75rem;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-copy-secret:hover,
    .btn-copy-code:hover {
        background: var(--pln-blue-dark);
        transform: translateY(-1px);
    }

    /* MFA Code Input */
    .mfa-code-input {
        font-size: 1.5rem;
        text-align: center;
        letter-spacing: 0.5rem;
        font-weight: 700;
        padding: 1rem;
        border: 2px solid rgba(33, 97, 140, 0.2);
        border-radius: 10px;
    }

    .mfa-code-input:focus {
        border-color: var(--pln-blue);
        box-shadow: 0 0 0 3px rgba(33, 97, 140, 0.1);
    }

    /* Backup Codes */
    .backup-codes-warning {
        background: #fff3cd;
        border: 2px solid #ffc107;
        border-left: 4px solid #ffc107;
        border-radius: 10px;
        padding: 1.25rem;
        margin-top: 1.5rem;
    }

    .backup-codes-warning-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #856404;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .backup-codes-box {
        background: white;
        border-radius: 8px;
        padding: 1rem;
        margin-top: 1rem;
        border: 1px solid rgba(33, 97, 140, 0.1);
    }

    .backup-codes-header {
        color: var(--pln-blue);
        font-weight: 700;
        margin-bottom: 0.75rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid rgba(33, 97, 140, 0.1);
    }

    .backup-codes-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 0.75rem;
    }

    .backup-code-item {
        background: var(--pln-blue-bg);
        border: 1px solid rgba(33, 97, 140, 0.15);
        border-radius: 8px;
        padding: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }

    .backup-code-item:hover {
        background: var(--pln-blue-lighter);
        border-color: var(--pln-blue);
    }

    .backup-code-item code {
        font-size: 0.9375rem;
        font-weight: 700;
        color: var(--pln-blue);
        letter-spacing: 1px;
        flex: 1;
    }

    .btn-copy-code {
        background: transparent;
        border: 1px solid var(--pln-blue);
        color: var(--pln-blue);
        padding: 0.375rem 0.5rem;
        font-size: 0.75rem;
    }

    .btn-copy-code:hover {
        background: var(--pln-blue);
        color: white;
    }

    /* Modern Form Elements */
    .modern-label {
        font-weight: 600;
        color: var(--text-dark);
        font-size: 0.9375rem;
        margin-bottom: 0.5rem;
    }

    .modern-input {
        border-radius: 10px;
        border: 2px solid rgba(33, 97, 140, 0.2);
        padding: 0.75rem 1rem;
        font-size: 0.9375rem;
        transition: all 0.2s ease;
    }

    .modern-input:focus {
        border-color: var(--pln-blue);
        box-shadow: 0 0 0 3px rgba(33, 97, 140, 0.1);
        outline: none;
    }

    /* Button Modern */
    .btn-modern {
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.2s ease;
        border: 1px solid transparent;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9375rem;
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
    }

    .btn-modern.btn-primary:hover {
        background: var(--pln-blue-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(33, 97, 140, 0.25);
    }

    .btn-modern.btn-warning {
        background: #ffc107;
        color: #1e293b;
        border: 1px solid #ffc107;
    }

    .btn-modern.btn-warning:hover {
        background: #e0a800;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(255, 193, 7, 0.25);
    }

    .btn-modern.btn-danger {
        background: #dc3545;
        color: white;
        border: 1px solid #dc3545;
    }

    .btn-modern.btn-danger:hover {
        background: #c82333;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.25);
    }

    .btn-modern.w-100 {
        width: 100%;
        justify-content: center;
    }

    /* Alert Styles */
    .alert {
        border-radius: 10px;
        border: none;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border-left: 4px solid #28a745;
    }

    .alert-warning {
        background: #fff3cd;
        color: #856404;
        border-left: 4px solid #ffc107;
    }

    .alert-info {
        background: var(--pln-blue-lighter);
        color: var(--pln-blue-dark);
        border-left: 4px solid var(--pln-blue);
    }

    .alert-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .mfa-card-body {
            padding: 1.25rem;
        }

        .mfa-section-body {
            padding: 1rem;
        }

        .backup-codes-list {
            grid-template-columns: 1fr;
        }

        .qr-code-wrapper {
            padding: 1rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto-format MFA code input
    document.getElementById('mfa_code')?.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value.length > 6) {
            this.value = this.value.slice(0, 6);
        }
    });

    // Copy Secret Code
    function copySecret() {
        const secret = '{{ $secret ?? '' }}';
        if (secret) {
            navigator.clipboard.writeText(secret).then(() => {
                showToast('Secret code berhasil disalin!', 'success');
            });
        }
    }

    // Copy Backup Code
    function copyCode(code) {
        navigator.clipboard.writeText(code).then(() => {
            showToast('Backup code berhasil disalin!', 'success');
        });
    }

    // Toast Notification
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type} position-fixed`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 250px;';
        toast.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            ${message}
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
</script>
@endpush
@endsection
