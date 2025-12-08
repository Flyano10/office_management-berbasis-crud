@extends('layouts.app')

@section('title', 'Edit Realisasi - PLN Icon Plus Kantor Management')
@section('page-title', 'Edit Realisasi')
@section('page-subtitle', 'Ubah data realisasi kontrak')

@section('page-actions')
    <a href="{{ route('realisasi.index') }}" class="btn btn-modern btn-clear">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Form -->
        <div class="col-lg-8">
            <div class="realisasi-form-card">
                <div class="realisasi-form-header">
                    <h5 class="realisasi-form-title">
                        <i class="fas fa-edit"></i>
                        Form Edit Realisasi
                    </h5>
                </div>
                <div class="realisasi-form-body">
                    <form action="{{ route('realisasi.update', $realisasi->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Data Kontrak (Auto-fill) -->
                        <div class="mb-4">
                            <div class="info-card">
                                <div class="info-card-header">
                                    <h6 class="info-card-title">
                                        <i class="fas fa-file-contract"></i>
                                        Data Kontrak (Auto-fill)
                                    </h6>
                                </div>
                                <div class="info-card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="kontrak_id" class="form-label">
                                                    Pilih Kontrak <span class="text-danger">*</span>
                                                </label>
                                                <select class="form-select modern-select @error('kontrak_id') is-invalid @enderror" 
                                                        id="kontrak_id" name="kontrak_id" required onchange="loadKontrakData()">
                                                    <option value="">Pilih Kontrak</option>
                                                    @foreach($kontrak as $k)
                                                        <option value="{{ $k->id }}" 
                                                                data-no-pihak-1="{{ $k->no_perjanjian_pihak_1 }}"
                                                                data-no-pihak-2="{{ $k->no_perjanjian_pihak_2 }}"
                                                                data-tanggal-mulai="{{ $k->tanggal_mulai }}"
                                                                data-tanggal-selesai="{{ $k->tanggal_selesai }}"
                                                                {{ old('kontrak_id', $realisasi->kontrak_id) == $k->id ? 'selected' : '' }}>
                                                            {{ $k->nama_perjanjian }} - {{ $k->no_perjanjian_pihak_1 }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('kontrak_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row" id="kontrak-data">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">No Perjanjian Pihak 1</label>
                                                <input type="text" class="form-control modern-input" id="no_perjanjian_pihak_1" 
                                                       value="{{ $realisasi->no_perjanjian_pihak_1 }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">No Perjanjian Pihak 2</label>
                                                <input type="text" class="form-control modern-input" id="no_perjanjian_pihak_2" 
                                                       value="{{ $realisasi->no_perjanjian_pihak_2 }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Tanggal Mulai</label>
                                                <input type="date" class="form-control modern-input" id="tanggal_mulai" 
                                                       value="{{ $realisasi->tanggal_mulai }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Tanggal Selesai</label>
                                                <input type="date" class="form-control modern-input" id="tanggal_selesai" 
                                                       value="{{ $realisasi->tanggal_selesai }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Data Realisasi (Input Manual) -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal_realisasi" class="form-label">
                                        Tanggal Realisasi <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control modern-input @error('tanggal_realisasi') is-invalid @enderror" 
                                           id="tanggal_realisasi" name="tanggal_realisasi" 
                                           value="{{ old('tanggal_realisasi', $realisasi->tanggal_realisasi) }}" required>
                                    @error('tanggal_realisasi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kompensasi" class="form-label">
                                        Kompensasi <span class="text-danger">*</span>
                                    </label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="kompensasi" id="pemeliharaan" 
                                                   value="Pemeliharaan" {{ old('kompensasi', $realisasi->kompensasi) == 'Pemeliharaan' ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="pemeliharaan">
                                                Pemeliharaan
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="kompensasi" id="pembangunan" 
                                                   value="Pembangunan" {{ old('kompensasi', $realisasi->kompensasi) == 'Pembangunan' ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="pembangunan">
                                                Pembangunan
                                            </label>
                                        </div>
                                    </div>
                                    @error('kompensasi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="rp_kompensasi" class="form-label">
                                        Rp. Kompensasi <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control modern-input @error('rp_kompensasi') is-invalid @enderror" 
                                           id="rp_kompensasi" name="rp_kompensasi" 
                                           placeholder="Contoh: 1000000" min="0" step="1000" 
                                           value="{{ old('rp_kompensasi', $realisasi->rp_kompensasi) }}" required>
                                    @error('rp_kompensasi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="lokasi_kantor" class="form-label">Lokasi Kantor</label>
                                    <select class="form-select modern-select @error('lokasi_kantor') is-invalid @enderror" 
                                            id="lokasi_kantor" name="lokasi_kantor">
                                        <option value="">Pilih Lokasi</option>
                                        <option value="UIW" {{ old('lokasi_kantor', $realisasi->lokasi_kantor) == 'UIW' ? 'selected' : '' }}>UIW</option>
                                        <option value="UID" {{ old('lokasi_kantor', $realisasi->lokasi_kantor) == 'UID' ? 'selected' : '' }}>UID</option>
                                        <option value="UIP" {{ old('lokasi_kantor', $realisasi->lokasi_kantor) == 'UIP' ? 'selected' : '' }}>UIP</option>
                                        <option value="UIT" {{ old('lokasi_kantor', $realisasi->lokasi_kantor) == 'UIT' ? 'selected' : '' }}>UIT</option>
                                    </select>
                                    @error('lokasi_kantor')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label">
                                        Deskripsi <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control modern-input @error('deskripsi') is-invalid @enderror" 
                                              id="deskripsi" name="deskripsi" rows="4" 
                                              placeholder="Deskripsikan detail realisasi" required>{{ old('deskripsi', $realisasi->deskripsi) }}</textarea>
                                    @error('deskripsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="upload_berita_acara" class="form-label">Upload Berita Acara</label>
                                    <input type="file" class="form-control modern-input @error('upload_berita_acara') is-invalid @enderror" 
                                           id="upload_berita_acara" name="upload_berita_acara" 
                                           accept=".pdf,.doc,.docx">
                                    @if($realisasi->upload_berita_acara)
                                        <small class="text-muted">File saat ini: <a href="{{ asset('uploads/berita_acara/' . $realisasi->upload_berita_acara) }}" target="_blank">{{ $realisasi->upload_berita_acara }}</a></small>
                                    @else
                                        <small class="text-muted">Format: PDF, DOC, DOCX (Max: 2MB)</small>
                                    @endif
                                    @error('upload_berita_acara')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <textarea class="form-control modern-input @error('alamat') is-invalid @enderror" 
                                              id="alamat" name="alamat" rows="3" 
                                              placeholder="Masukkan alamat lengkap (opsional)">{{ old('alamat', $realisasi->alamat) }}</textarea>
                                    @error('alamat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-modern btn-primary">
                                <i class="fas fa-save"></i> Update Realisasi
                            </button>
                            <a href="{{ route('realisasi.index') }}" class="btn btn-modern btn-clear">
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
                    <div class="guide-section">
                        <h6 class="guide-section-title">
                            <i class="fas fa-file-contract"></i> Pilih Kontrak
                        </h6>
                        <p class="guide-text">
                            Pilih kontrak yang mau direalisasikan. Nanti data kontraknya bakal otomatis keisi setelah lo pilih.
                        </p>
                    </div>
                    <div class="guide-section">
                        <h6 class="guide-section-title">
                            <i class="fas fa-calendar-alt"></i> Tanggal Realisasi
                        </h6>
                        <p class="guide-text">
                            Isi tanggal kapan realisasi kontraknya dilakukan. Pastikan tanggalnya sesuai sama dokumen yang ada ya.
                        </p>
                    </div>
                    <div class="guide-section">
                        <h6 class="guide-section-title">
                            <i class="fas fa-money-bill-wave"></i> Kompensasi
                        </h6>
                        <p class="guide-text">
                            Pilih jenis kompensasinya: <strong>Pemeliharaan</strong> atau <strong>Pembangunan</strong>. Nilai kompensasinya tulis tanpa titik atau koma, misalnya 1000000 untuk 1 juta.
                        </p>
                    </div>
                    <div class="guide-section">
                        <h6 class="guide-section-title">
                            <i class="fas fa-file-alt"></i> Deskripsi
                        </h6>
                        <p class="guide-text">
                            Jelasin detail realisasinya selengkap mungkin. Ini penting buat dokumentasi dan tracking nanti.
                        </p>
                    </div>
                    <div class="guide-section">
                        <h6 class="guide-section-title">
                            <i class="fas fa-file-upload"></i> Berita Acara
                        </h6>
                        <p class="guide-text">
                            Kalau ada file berita acara, upload aja. Formatnya PDF, DOC, atau DOCX dengan maksimal 2MB.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function loadKontrakData() {
    const select = document.getElementById('kontrak_id');
    const kontrakData = document.getElementById('kontrak-data');
    const selectedOption = select.options[select.selectedIndex];
    
    if (select.value) {
        kontrakData.style.display = 'block';
        document.getElementById('no_perjanjian_pihak_1').value = selectedOption.getAttribute('data-no-pihak-1');
        document.getElementById('no_perjanjian_pihak_2').value = selectedOption.getAttribute('data-no-pihak-2');
        document.getElementById('tanggal_mulai').value = selectedOption.getAttribute('data-tanggal-mulai');
        document.getElementById('tanggal_selesai').value = selectedOption.getAttribute('data-tanggal-selesai');
    } else {
        kontrakData.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    loadKontrakData();
});
</script>

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

    /* Form Card */
    .realisasi-form-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(33, 97, 140, 0.1);
        border: 1px solid rgba(33, 97, 140, 0.15);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .realisasi-form-header {
        background: white;
        padding: 1.25rem 1.75rem;
        border-bottom: 2px solid var(--pln-blue);
    }

    .realisasi-form-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--pln-blue);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .realisasi-form-title i {
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

    .realisasi-form-body {
        padding: 1.75rem;
    }

    /* Info Card */
    .info-card {
        background: var(--pln-blue-lighter);
        border: 1px solid rgba(33, 97, 140, 0.2);
        border-radius: 10px;
        overflow: hidden;
    }

    .info-card-header {
        background: var(--pln-blue);
        padding: 1rem 1.25rem;
    }

    .info-card-title {
        font-size: 0.9375rem;
        font-weight: 700;
        color: white;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-card-title i {
        font-size: 1rem;
    }

    .info-card-body {
        padding: 1.25rem;
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
