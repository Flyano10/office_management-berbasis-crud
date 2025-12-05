@extends('layouts.app')

@section('title', 'Setup MFA - PLN Icon Plus')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-shield-alt me-2"></i>
                        Multi-Factor Authentication (MFA) Setup
                    </h4>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if ($admin->mfa_enabled)
                        <!-- MFA Already Enabled -->
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>MFA sudah diaktifkan</strong> untuk akun Anda.
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="mb-3">Backup Codes</h5>
                                <p class="text-muted">
                                    Simpan backup codes ini dengan aman. Gunakan jika Anda kehilangan akses ke aplikasi authenticator.
                                </p>
                                
                                @if ($admin->mfa_backup_codes && count($admin->mfa_backup_codes) > 0)
                                    <div class="alert alert-warning">
                                        <strong>Sisa Backup Codes: {{ count($admin->mfa_backup_codes) }}</strong>
                                        <ul class="mb-0 mt-2">
                                            @foreach ($admin->mfa_backup_codes as $code)
                                                <li><code>{{ $code }}</code></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @else
                                    <div class="alert alert-danger">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Tidak ada backup codes tersisa. Silakan regenerate backup codes.
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('mfa.regenerate-backup-codes') }}" class="mt-3">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="password_regenerate" class="form-label">Konfirmasi Password</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                               id="password_regenerate" name="password" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-sync-alt me-2"></i>
                                        Regenerate Backup Codes
                                    </button>
                                </form>
                            </div>

                            <div class="col-md-6">
                                <h5 class="mb-3">Nonaktifkan MFA</h5>
                                <p class="text-muted">
                                    Jika Anda ingin menonaktifkan MFA, masukkan password Anda untuk konfirmasi.
                                </p>
                                
                                <form method="POST" action="{{ route('mfa.disable') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="password_disable" class="form-label">Konfirmasi Password</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                               id="password_disable" name="password" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menonaktifkan MFA?')">
                                        <i class="fas fa-times me-2"></i>
                                        Nonaktifkan MFA
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- MFA Setup Form -->
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="mb-3">Langkah 1: Scan QR Code</h5>
                                <p class="text-muted mb-3">
                                    Buka aplikasi authenticator (Google Authenticator, Authy, Microsoft Authenticator, dll) 
                                    dan scan QR code di bawah ini:
                                </p>
                                
                                <div class="text-center mb-3">
                                    @if($qrCodeInline)
                                        <div class="d-inline-block border p-2 bg-white rounded">
                                            {!! $qrCodeInline !!}
                                        </div>
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            QR Code tidak dapat di-generate. Silakan gunakan manual entry.
                                        </div>
                                    @endif
                                </div>

                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Manual Entry:</strong> Jika tidak bisa scan, masukkan kode berikut secara manual:
                                    <br>
                                    <code class="mt-2 d-block">{{ $secret }}</code>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h5 class="mb-3">Langkah 2: Verifikasi & Aktifkan</h5>
                                <p class="text-muted mb-3">
                                    Setelah menambahkan akun di aplikasi authenticator, masukkan kode 6 digit untuk verifikasi:
                                </p>

                                <form method="POST" action="{{ route('mfa.enable') }}">
                                    @csrf
                                    <input type="hidden" name="secret" value="{{ $secret }}">
                                    <input type="hidden" name="backup_codes" value="{{ json_encode($backupCodes) }}">

                                    <div class="mb-3">
                                        <label for="mfa_code" class="form-label">Kode Verifikasi (6 digit)</label>
                                        <input type="text" 
                                               class="form-control @error('mfa_code') is-invalid @enderror" 
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
                                    </div>

                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-check me-2"></i>
                                        Aktifkan MFA
                                    </button>
                                </form>

                                <div class="alert alert-warning mt-4">
                                    <h6 class="alert-heading">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Backup Codes - SIMPAN DENGAN AMAN!
                                    </h6>
                                    <p class="mb-2">
                                        Backup codes ini hanya ditampilkan sekali. Simpan di tempat yang aman. 
                                        Gunakan jika Anda kehilangan akses ke aplikasi authenticator.
                                    </p>
                                    <div class="bg-light p-3 rounded">
                                        @foreach ($backupCodes as $code)
                                            <code class="d-block mb-1">{{ $code }}</code>
                                        @endforeach
                                    </div>
                                    <small class="text-muted mt-2 d-block">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Setiap backup code hanya bisa digunakan sekali.
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (session('backup_codes'))
                        <div class="alert alert-success mt-4">
                            <h6 class="alert-heading">
                                <i class="fas fa-check-circle me-2"></i>
                                Backup Codes Baru
                            </h6>
                            <p class="mb-2">Backup codes baru telah dibuat. Simpan dengan aman!</p>
                            <div class="bg-light p-3 rounded">
                                @foreach (session('backup_codes') as $code)
                                    <code class="d-block mb-1">{{ $code }}</code>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-format MFA code input
    document.getElementById('mfa_code')?.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value.length > 6) {
            this.value = this.value.slice(0, 6);
        }
    });
</script>
@endsection

