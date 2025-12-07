@extends('layouts.app')

@section('title', 'Tambah Bidang - PLN Icon Plus Kantor Management')
@section('page-title', 'Tambah Bidang Baru')
@section('page-subtitle', 'Buat data bidang baru')

@section('page-actions')
    <a href="{{ route('bidang.index') }}" class="btn btn-modern btn-clear">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Form -->
        <div class="col-lg-8">
            <div class="bidang-form-card">
                <div class="bidang-form-header">
                    <h5 class="bidang-form-title">
                        <i class="fas fa-sitemap"></i>
                        Form Tambah Bidang
                    </h5>
                </div>
                <div class="bidang-form-body">
                    <form action="{{ route('bidang.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="nama_bidang" class="form-label">
                                        Nama Bidang <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control modern-input @error('nama_bidang') is-invalid @enderror" 
                                           id="nama_bidang" name="nama_bidang" 
                                           placeholder="Contoh: Information Technology, Human Resources, dll" 
                                           value="{{ old('nama_bidang') }}" required>
                                    @error('nama_bidang')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label">Deskripsi</label>
                                    <textarea class="form-control modern-input @error('deskripsi') is-invalid @enderror" 
                                              id="deskripsi" name="deskripsi" rows="3" 
                                              placeholder="Masukkan deskripsi bidang">{{ old('deskripsi') }}</textarea>
                                    @error('deskripsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-modern btn-primary">
                                <i class="fas fa-save"></i> Simpan Bidang
                            </button>
                            <a href="{{ route('bidang.index') }}" class="btn btn-modern btn-clear">
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
                        <i class="fas fa-info-circle"></i>
                        Panduan Pengisian
                    </h6>
                </div>
                <div class="guide-body">
                    <div class="guide-section">
                        <h6 class="guide-section-title">
                            <i class="fas fa-sitemap"></i> Nama Bidang
                        </h6>
                        <p class="guide-text">
                            Masukkan nama bidang yang jelas dan deskriptif, misalnya "Information Technology", "Human Resources", atau "Finance".
                        </p>
                    </div>
                    <div class="guide-section">
                        <h6 class="guide-section-title">
                            <i class="fas fa-file-alt"></i> Deskripsi
                        </h6>
                        <p class="guide-text">
                            Masukkan deskripsi singkat tentang bidang ini. Deskripsi bersifat opsional namun disarankan untuk memberikan informasi lebih lanjut.
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
    .bidang-form-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(33, 97, 140, 0.1);
        border: 1px solid rgba(33, 97, 140, 0.15);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .bidang-form-header {
        background: white;
        padding: 1.25rem 1.75rem;
        border-bottom: 2px solid var(--pln-blue);
    }

    .bidang-form-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--pln-blue);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .bidang-form-title i {
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

    .bidang-form-body {
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
@endsection
