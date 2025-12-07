@extends('layouts.app')

@section('title', 'Edit Lantai - PLN Icon Plus Kantor Management')
@section('page-title', 'Edit Lantai')
@section('page-subtitle', 'Edit informasi lantai: ' . $lantai->nama_lantai)

@section('page-actions')
    <a href="{{ route('lantai.index') }}" class="btn btn-modern btn-clear">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
@endsection

@section('content')
    <div class="container-fluid">

        <!-- Form -->
        <div class="row">
            <div class="col-lg-8">
                <div class="lantai-form-card">
                    <div class="lantai-form-header">
                        <h5 class="lantai-form-title">
                            <i class="fas fa-layer-group"></i>
                            Informasi Lantai
                        </h5>
                    </div>
                    <div class="lantai-form-body">
                        <form action="{{ route('lantai.update', $lantai->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nama_lantai" class="form-label">
                                            Nama Lantai <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control modern-input @error('nama_lantai') is-invalid @enderror" 
                                               id="nama_lantai" name="nama_lantai" 
                                               value="{{ old('nama_lantai', $lantai->nama_lantai) }}" 
                                               placeholder="Contoh: Lantai 1, Lantai Dasar" required>
                                        @error('nama_lantai')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nomor_lantai" class="form-label">
                                            Nomor Lantai <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" class="form-control modern-input @error('nomor_lantai') is-invalid @enderror" 
                                               id="nomor_lantai" name="nomor_lantai" 
                                               value="{{ old('nomor_lantai', $lantai->nomor_lantai) }}" 
                                               placeholder="Contoh: 1, 2, 3" min="1" required>
                                        @error('nomor_lantai')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="gedung_id" class="form-label">
                                    Gedung <span class="text-danger">*</span>
                                </label>
                                <select class="form-select modern-select @error('gedung_id') is-invalid @enderror" id="gedung_id" name="gedung_id" required>
                                    <option value="">Pilih Gedung</option>
                                    @foreach($gedung as $g)
                                        <option value="{{ $g->id }}" {{ old('gedung_id', $lantai->gedung_id) == $g->id ? 'selected' : '' }}>
                                            {{ $g->nama_gedung }} - {{ $g->kantor->nama_kantor ?? 'N/A' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('gedung_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-actions">
                                <a href="{{ route('lantai.index') }}" class="btn btn-modern btn-clear">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-modern btn-primary">
                                    <i class="fas fa-save"></i> Update Lantai
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
                            <h6 class="guide-section-title">Informasi Utama</h6>
                            <ul class="guide-list">
                                <li><i class="fas fa-check-circle"></i> Nama Lantai: Nama resmi lantai</li>
                                <li><i class="fas fa-check-circle"></i> Nomor Lantai: Urutan lantai (1, 2, 3, dll)</li>
                                <li><i class="fas fa-check-circle"></i> Gedung: Pilih gedung yang memiliki lantai ini</li>
                            </ul>
                        </div>

                        <div class="guide-alert">
                            <i class="fas fa-info-circle"></i>
                            <span>Pastikan data yang diisi sudah benar dan akurat.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<style>
    :root {
        --pln-blue: #21618C;
        --pln-blue-dark: #1A4D73;
        --pln-blue-light: #2E86AB;
        --pln-blue-lighter: #E8F4F8;
        --pln-blue-bg: #F5FAFC;
        --white: #FFFFFF;
        --gray-light: #F8F9FA;
        --gray-border: #E0E0E0;
        --text-dark: #1A1A1A;
        --text-gray: #6C757D;
    }

    /* Form Card */
    .lantai-form-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(33, 97, 140, 0.1);
        border: 1px solid rgba(33, 97, 140, 0.15);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .lantai-form-header {
        background: white;
        padding: 1.25rem 1.75rem;
        border-bottom: 2px solid var(--pln-blue);
    }

    .lantai-form-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--pln-blue);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .lantai-form-title i {
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

    .lantai-form-body {
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

    /* Invalid Feedback */
    .invalid-feedback {
        display: block;
        color: #dc3545;
        font-size: 0.8rem;
        margin-top: 0.25rem;
    }

    .is-invalid {
        border-color: #dc3545;
    }

    .is-invalid:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
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

    .guide-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .guide-list li {
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: var(--text-gray);
        margin-bottom: 0.5rem;
        line-height: 1.5;
    }

    .guide-list li:last-child {
        margin-bottom: 0;
    }

    .guide-list li i {
        color: var(--pln-blue);
        font-size: 0.75rem;
        margin-top: 0.25rem;
        flex-shrink: 0;
    }

    .guide-alert {
        background: var(--pln-blue-lighter);
        border: 1px solid var(--pln-blue);
        border-radius: 8px;
        padding: 1rem;
        margin-top: 1.5rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .guide-alert i {
        color: var(--pln-blue);
        font-size: 1rem;
        flex-shrink: 0;
        margin-top: 0.125rem;
    }

    .guide-alert span {
        font-size: 0.875rem;
        color: var(--text-dark);
        line-height: 1.5;
    }

    /* Required Field Indicator */
    .text-danger {
        color: #dc3545;
    }

    /* Responsive */
    @media (max-width: 991px) {
        .lantai-form-body {
            padding: 1.25rem;
        }

        .guide-body {
            padding: 1.25rem;
        }

        .form-actions {
            flex-direction: column;
        }

        .form-actions .btn-modern {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endsection
