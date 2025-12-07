@extends('layouts.app')

@section('title', 'Tambah Ruang - PLN Icon Plus Kantor Management')
@section('page-title', 'Tambah Ruang Baru')
@section('page-subtitle', 'Buat ruang baru PLN Icon Plus')

@section('page-actions')
    <a href="{{ route('ruang.index') }}" class="btn btn-modern btn-clear">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
@endsection

@section('content')
    <div class="container-fluid">

        <!-- Form -->
        <div class="row">
            <div class="col-lg-8">
                <div class="ruang-form-card">
                    <div class="ruang-form-header">
                        <h5 class="ruang-form-title">
                            <i class="fas fa-door-open"></i>
                            Informasi Ruang
                        </h5>
                    </div>
                    <div class="ruang-form-body">
                        <form action="{{ route('ruang.store') }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nama_ruang" class="form-label">
                                            Nama Ruang <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control modern-input @error('nama_ruang') is-invalid @enderror" 
                                               id="nama_ruang" name="nama_ruang" 
                                               value="{{ old('nama_ruang') }}" 
                                               placeholder="Contoh: Ruang Meeting, Ruang Kerja" required>
                                        @error('nama_ruang')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="kapasitas" class="form-label">
                                            Kapasitas <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" class="form-control modern-input @error('kapasitas') is-invalid @enderror" 
                                               id="kapasitas" name="kapasitas" 
                                               value="{{ old('kapasitas') }}" 
                                               placeholder="Contoh: 10, 20, 50" min="1" required>
                                        @error('kapasitas')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="lantai_id" class="form-label">
                                            Lantai <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select modern-select @error('lantai_id') is-invalid @enderror" id="lantai_id" name="lantai_id" required>
                                            <option value="">Pilih Lantai</option>
                                            @foreach($lantai as $l)
                                                <option value="{{ $l->id }}" {{ old('lantai_id') == $l->id ? 'selected' : '' }}>
                                                    Lantai {{ $l->nomor_lantai }} - {{ $l->gedung->nama_gedung }} ({{ $l->gedung->kantor->nama_kantor ?? 'N/A' }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('lantai_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status_ruang" class="form-label">
                                            Status Ruang <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select modern-select @error('status_ruang') is-invalid @enderror" id="status_ruang" name="status_ruang" required>
                                            <option value="">Pilih Status</option>
                                            <option value="tersedia" {{ old('status_ruang') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                            <option value="terisi" {{ old('status_ruang') == 'terisi' ? 'selected' : '' }}>Terisi</option>
                                            <option value="perbaikan" {{ old('status_ruang') == 'perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                                        </select>
                                        @error('status_ruang')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="bidang_id" class="form-label">
                                            Bidang <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select modern-select @error('bidang_id') is-invalid @enderror" id="bidang_id" name="bidang_id" required>
                                            <option value="">Pilih Bidang</option>
                                            @foreach($bidang as $b)
                                                <option value="{{ $b->id }}" {{ old('bidang_id') == $b->id ? 'selected' : '' }}>
                                                    {{ $b->nama_bidang }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('bidang_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sub_bidang_id" class="form-label">
                                            Sub Bidang <span class="text-muted">(Opsional)</span>
                                        </label>
                                        <select class="form-select modern-select @error('sub_bidang_id') is-invalid @enderror" id="sub_bidang_id" name="sub_bidang_id">
                                            <option value="">Pilih Sub Bidang</option>
                                            @foreach($subBidang as $sb)
                                                <option value="{{ $sb->id }}" {{ old('sub_bidang_id') == $sb->id ? 'selected' : '' }}>
                                                    {{ $sb->nama_sub_bidang }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('sub_bidang_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <a href="{{ route('ruang.index') }}" class="btn btn-modern btn-clear">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-modern btn-primary">
                                    <i class="fas fa-save"></i> Simpan Ruang
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="guide-card">
                    <div class="guide-header">
                        <h5 class="guide-title">
                            <i class="fas fa-info-circle"></i>
                            Panduan Pengisian
                        </h5>
                    </div>
                    <div class="guide-body">
                        <div class="guide-section">
                            <h6 class="guide-section-title">
                                <i class="fas fa-door-open"></i> Nama Ruang
                            </h6>
                            <p class="guide-text">
                                Masukkan nama ruang yang jelas dan deskriptif, misalnya "Ruang Meeting A", "Ruang Kerja Tim", atau "Ruang Rapat".
                            </p>
                        </div>

                        <div class="guide-section">
                            <h6 class="guide-section-title">
                                <i class="fas fa-users"></i> Kapasitas
                            </h6>
                            <p class="guide-text">
                                Tentukan jumlah maksimal orang yang dapat menampung ruang ini. Pastikan angka yang dimasukkan akurat.
                            </p>
                        </div>

                        <div class="guide-section">
                            <h6 class="guide-section-title">
                                <i class="fas fa-layer-group"></i> Lantai
                            </h6>
                            <p class="guide-text">
                                Pilih lantai di mana ruang ini berada. Pastikan lantai sudah terdaftar dalam sistem.
                            </p>
                        </div>

                        <div class="guide-section">
                            <h6 class="guide-section-title">
                                <i class="fas fa-info-circle"></i> Status Ruang
                            </h6>
                            <p class="guide-text">
                                Pilih status ruang: <strong>Tersedia</strong> (bisa digunakan), <strong>Terisi</strong> (sedang digunakan), atau <strong>Perbaikan</strong> (sedang diperbaiki).
                            </p>
                        </div>

                        <div class="guide-section">
                            <h6 class="guide-section-title">
                                <i class="fas fa-building"></i> Bidang & Sub Bidang
                            </h6>
                            <p class="guide-text">
                                Pilih bidang yang menggunakan ruang ini. Sub bidang bersifat opsional dan dapat dipilih jika diperlukan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

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
    .ruang-form-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(33, 97, 140, 0.1);
        border: 1px solid rgba(33, 97, 140, 0.15);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .ruang-form-header {
        background: white;
        padding: 1.25rem 1.75rem;
        border-bottom: 2px solid var(--pln-blue);
    }

    .ruang-form-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--pln-blue);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .ruang-form-title i {
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

    .ruang-form-body {
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

    .form-label .text-muted {
        color: #64748b;
        font-weight: 400;
    }

    /* Invalid Feedback */
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

    /* Buttons */
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

    .btn-modern.btn-primary {
        background: var(--pln-blue);
        color: white;
        border: 1px solid var(--pln-blue);
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
        margin-bottom: 1.5rem;
    }

    .guide-header {
        background: var(--pln-blue-lighter);
        padding: 1.25rem 1.75rem;
        border-bottom: 1px solid rgba(33, 97, 140, 0.1);
    }

    .guide-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--pln-blue);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .guide-title i {
        color: var(--pln-blue);
        font-size: 1.25rem;
    }

    .guide-body {
        padding: 1.75rem;
    }

    .guide-section {
        margin-bottom: 1.5rem;
    }

    .guide-section:last-of-type {
        margin-bottom: 0;
    }

    .guide-section-title {
        font-size: 0.875rem;
        font-weight: 700;
        color: var(--pln-blue);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.75rem;
    }

    .guide-text {
        font-size: 0.875rem;
        color: var(--text-gray);
        line-height: 1.5;
        margin: 0;
    }

    .guide-text strong {
        color: var(--pln-blue);
        font-weight: 600;
    }

    /* Responsive */
    @media (max-width: 991px) {
        .guide-card {
            position: static;
            margin-top: 1.5rem;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .form-actions .btn-modern {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush
