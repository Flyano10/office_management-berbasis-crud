@extends('layouts.app')

@section('title', 'Tambah Okupansi - PLN Icon Plus Kantor Management')
@section('page-title', 'Tambah Okupansi Baru')
@section('page-subtitle', 'Buat data okupansi baru')

@section('page-actions')
    <a href="{{ route('okupansi.index') }}" class="btn btn-modern btn-clear">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Form -->
        <div class="col-lg-8">
            <div class="okupansi-form-card">
                <div class="okupansi-form-header">
                    <h5 class="okupansi-form-title">
                        <i class="fas fa-chart-pie"></i>
                        Form Tambah Okupansi
                    </h5>
                </div>
                <div class="okupansi-form-body">
                    <form action="{{ route('okupansi.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ruang_id" class="form-label">
                                        Ruang <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select modern-select @error('ruang_id') is-invalid @enderror" 
                                            id="ruang_id" name="ruang_id" required>
                                        <option value="">Pilih Ruang</option>
                                        @foreach($ruang as $r)
                                            <option value="{{ $r->id }}" {{ old('ruang_id') == $r->id ? 'selected' : '' }}>
                                                {{ $r->nama_ruang }} - Lantai {{ $r->lantai->nomor_lantai ?? 'N/A' }} ({{ $r->lantai->gedung->nama_gedung ?? 'N/A' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('ruang_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal_okupansi" class="form-label">
                                        Tanggal Okupansi <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control modern-input @error('tanggal_okupansi') is-invalid @enderror" 
                                           id="tanggal_okupansi" name="tanggal_okupansi" 
                                           value="{{ old('tanggal_okupansi') }}" required>
                                    @error('tanggal_okupansi')
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
                                    <select class="form-select modern-select @error('bidang_id') is-invalid @enderror" 
                                            id="bidang_id" name="bidang_id" required>
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
                                    <label for="sub_bidang_id" class="form-label">Sub Bidang</label>
                                    <select class="form-select modern-select @error('sub_bidang_id') is-invalid @enderror" 
                                            id="sub_bidang_id" name="sub_bidang_id">
                                        <option value="">Pilih Sub Bidang (Opsional)</option>
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

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="jml_pegawai_organik" class="form-label">
                                        Jumlah Pegawai Organik <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control modern-input @error('jml_pegawai_organik') is-invalid @enderror" 
                                           id="jml_pegawai_organik" name="jml_pegawai_organik" 
                                           placeholder="Contoh: 10, 20, 50, dll" min="0" 
                                           value="{{ old('jml_pegawai_organik') }}" required>
                                    @error('jml_pegawai_organik')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="jml_pegawai_tad" class="form-label">
                                        Jumlah Pegawai TAD <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control modern-input @error('jml_pegawai_tad') is-invalid @enderror" 
                                           id="jml_pegawai_tad" name="jml_pegawai_tad" 
                                           placeholder="Contoh: 5, 10, 15, dll" min="0" 
                                           value="{{ old('jml_pegawai_tad') }}" required>
                                    @error('jml_pegawai_tad')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="jml_pegawai_kontrak" class="form-label">
                                        Jumlah Pegawai Kontrak <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control modern-input @error('jml_pegawai_kontrak') is-invalid @enderror" 
                                           id="jml_pegawai_kontrak" name="jml_pegawai_kontrak" 
                                           placeholder="Contoh: 3, 8, 12, dll" min="0" 
                                           value="{{ old('jml_pegawai_kontrak') }}" required>
                                    @error('jml_pegawai_kontrak')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="keterangan" class="form-label">Keterangan</label>
                                    <textarea class="form-control modern-input @error('keterangan') is-invalid @enderror" 
                                              id="keterangan" name="keterangan" rows="3" 
                                              placeholder="Masukkan keterangan tambahan">{{ old('keterangan') }}</textarea>
                                    @error('keterangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-modern btn-primary">
                                <i class="fas fa-save"></i> Simpan Okupansi
                            </button>
                            <a href="{{ route('okupansi.index') }}" class="btn btn-modern btn-clear">
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
                            <i class="fas fa-building"></i> Ruang
                        </h6>
                        <p class="guide-text">
                            Pilih ruang yang akan diisi data okupansinya. Pastikan ruang sudah terdaftar dalam sistem.
                        </p>
                    </div>
                    <div class="guide-section">
                        <h6 class="guide-section-title">
                            <i class="fas fa-calendar"></i> Tanggal Okupansi
                        </h6>
                        <p class="guide-text">
                            Pilih tanggal pencatatan okupansi. Gunakan tanggal yang relevan dengan periode data.
                        </p>
                    </div>
                    <div class="guide-section">
                        <h6 class="guide-section-title">
                            <i class="fas fa-sitemap"></i> Bidang & Sub Bidang
                        </h6>
                        <p class="guide-text">
                            Pilih bidang yang menggunakan ruang tersebut. Sub bidang bersifat opsional.
                        </p>
                    </div>
                    <div class="guide-section">
                        <h6 class="guide-section-title">
                            <i class="fas fa-users"></i> Jumlah Pegawai
                        </h6>
                        <p class="guide-text">
                            Masukkan jumlah pegawai berdasarkan jenisnya (Organik, TAD, Kontrak). Total akan dihitung otomatis.
                        </p>
                    </div>
                    <div class="guide-section">
                        <h6 class="guide-section-title">
                            <i class="fas fa-percentage"></i> Persentase Okupansi
                        </h6>
                        <p class="guide-text">
                            Persentase okupansi akan dihitung otomatis berdasarkan total pegawai dan kapasitas ruang.
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
    .okupansi-form-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(33, 97, 140, 0.1);
        border: 1px solid rgba(33, 97, 140, 0.15);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .okupansi-form-header {
        background: white;
        padding: 1.25rem 1.75rem;
        border-bottom: 2px solid var(--pln-blue);
    }

    .okupansi-form-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--pln-blue);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .okupansi-form-title i {
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

    .okupansi-form-body {
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

    /* Alert */
    .alert {
        border-radius: 10px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        border: 1px solid transparent;
    }

    .alert-danger {
        background: var(--pln-blue-lighter);
        color: var(--pln-blue-dark);
        border-color: var(--pln-blue);
    }

    .alert-danger h6 {
        color: var(--pln-blue-dark);
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .alert-danger ul {
        margin: 0;
        padding-left: 1.25rem;
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
