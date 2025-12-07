@extends('layouts.app')

@section('title', 'Tambah Gedung - PLN Icon Plus Kantor Management')
@section('page-title', 'Tambah Gedung Baru')
@section('page-subtitle', 'Buat gedung baru PLN Icon Plus')

@section('page-actions')
    <a href="{{ route('gedung.index') }}" class="btn btn-modern btn-clear">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="gedung-form-card">
                <div class="gedung-form-header">
                    <h5 class="gedung-form-title">
                        <i class="fas fa-home"></i>
                        Informasi Gedung
                    </h5>
                </div>
                <div class="gedung-form-body">
                <form action="{{ route('gedung.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama_gedung" class="form-label">
                                    Nama Gedung <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control modern-input @error('nama_gedung') is-invalid @enderror" 
                                       id="nama_gedung" name="nama_gedung" 
                                       value="{{ old('nama_gedung') }}"
                                       placeholder="Contoh: Gedung A" required>
                                @error('nama_gedung')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kantor_id" class="form-label">
                                    Kantor <span class="text-danger">*</span>
                                </label>
                                <select class="form-select modern-select @error('kantor_id') is-invalid @enderror" 
                                        id="kantor_id" name="kantor_id" required>
                                    <option value="">Pilih Kantor</option>
                                    @foreach($kantor as $k)
                                        <option value="{{ $k->id }}" {{ old('kantor_id') == $k->id ? 'selected' : '' }}>
                                            {{ $k->kode_kantor }} - {{ $k->nama_kantor }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kantor_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">
                            Alamat Gedung <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control modern-input @error('alamat') is-invalid @enderror" 
                                  id="alamat" name="alamat" rows="3" 
                                  placeholder="Masukkan alamat lengkap gedung" required>{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="latitude" class="form-label">Latitude</label>
                                <input type="text" class="form-control modern-input @error('latitude') is-invalid @enderror" 
                                       id="latitude" name="latitude" 
                                       value="{{ old('latitude') }}"
                                       placeholder="Contoh: -6.2088">
                                @error('latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="longitude" class="form-label">Longitude</label>
                                <input type="text" class="form-control modern-input @error('longitude') is-invalid @enderror" 
                                       id="longitude" name="longitude" 
                                       value="{{ old('longitude') }}"
                                       placeholder="Contoh: 106.8456">
                                @error('longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status_gedung" class="form-label">
                                    Status <span class="text-danger">*</span>
                                </label>
                                <select class="form-select modern-select @error('status_gedung') is-invalid @enderror" 
                                        id="status_gedung" name="status_gedung" required>
                                    <option value="aktif" {{ old('status_gedung', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="non_aktif" {{ old('status_gedung') == 'non_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                                @error('status_gedung')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status_kepemilikan" class="form-label">
                                    Status Kepemilikan <span class="text-danger">*</span>
                                </label>
                                <select class="form-select modern-select @error('status_kepemilikan') is-invalid @enderror" 
                                        id="status_kepemilikan" name="status_kepemilikan" required>
                                    <option value="milik" {{ old('status_kepemilikan', 'milik') == 'milik' ? 'selected' : '' }}>Milik</option>
                                    <option value="sewa" {{ old('status_kepemilikan') == 'sewa' ? 'selected' : '' }}>Sewa</option>
                                </select>
                                @error('status_kepemilikan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="layout_gedung" class="form-label">Layout Gedung (opsional)</label>
                        <input type="file" class="form-control modern-file-input @error('layout_gedung') is-invalid @enderror" 
                               id="layout_gedung" name="layout_gedung" 
                               accept=".pdf,.jpg,.jpeg,.png,.svg">
                        <div class="form-text">Unggah layout gedung dalam format PDF atau gambar (maks. 20 MB).</div>
                        @error('layout_gedung')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('gedung.index') }}" class="btn btn-modern btn-clear">
                            <i class="fas fa-times"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-modern btn-primary">
                            <i class="fas fa-save"></i> Simpan Gedung
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
                            <li><i class="fas fa-check-circle"></i> Nama Gedung: Nama resmi gedung</li>
                            <li><i class="fas fa-check-circle"></i> Kantor: Pilih kantor yang memiliki gedung ini</li>
                            <li><i class="fas fa-check-circle"></i> Alamat: Lokasi lengkap gedung</li>
                            <li><i class="fas fa-check-circle"></i> Status: Aktif atau tidak aktif</li>
                        </ul>
                    </div>

                    <div class="guide-section">
                        <h6 class="guide-section-title">Informasi Tambahan</h6>
                        <ul class="guide-list">
                            <li><i class="fas fa-check-circle"></i> Koordinat: GPS latitude & longitude (opsional)</li>
                            <li><i class="fas fa-check-circle"></i> Status Kepemilikan: Milik atau sewa</li>
                            <li><i class="fas fa-check-circle"></i> Layout: File PDF atau gambar (opsional)</li>
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
    .gedung-form-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(33, 97, 140, 0.1);
        border: 1px solid rgba(33, 97, 140, 0.15);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .gedung-form-header {
        background: white;
        padding: 1.25rem 1.75rem;
        border-bottom: 2px solid var(--pln-blue);
    }

    .gedung-form-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--pln-blue);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .gedung-form-title i {
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

    .gedung-form-body {
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

    textarea.form-control,
    textarea.modern-input {
        resize: vertical;
        min-height: 80px;
    }

    /* File Input */
    .modern-file-input {
        padding: 0.5rem 0.875rem;
        cursor: pointer;
    }

    .modern-file-input::-webkit-file-upload-button {
        background: var(--pln-blue);
        color: white;
        border: none;
        border-radius: 6px;
        padding: 0.5rem 1rem;
        margin-right: 1rem;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .modern-file-input::-webkit-file-upload-button:hover {
        background: var(--pln-blue-dark);
    }

    .form-text {
        font-size: 0.8rem;
        color: var(--text-gray);
        margin-top: 0.5rem;
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
        .gedung-form-body {
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
