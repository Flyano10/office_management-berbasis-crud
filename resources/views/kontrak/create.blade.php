@extends('layouts.app')

@section('title', 'Tambah Kontrak - PLN Icon Plus Kantor Management')
@section('page-title', 'Tambah Kontrak Baru')
@section('page-subtitle', 'Buat data kontrak baru')

@section('page-actions')
    <a href="{{ route('kontrak.index') }}" class="btn btn-modern btn-clear">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Form -->
        <div class="col-lg-8">
            <div class="kontrak-form-card">
                <div class="kontrak-form-header">
                    <h5 class="kontrak-form-title">
                        <i class="fas fa-file-contract"></i>
                        Form Tambah Kontrak
                    </h5>
                </div>
                <div class="kontrak-form-body">
                    <form action="{{ route('kontrak.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nama_perjanjian" class="form-label">
                                        Nama Perjanjian <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control modern-input @error('nama_perjanjian') is-invalid @enderror" 
                                           id="nama_perjanjian" name="nama_perjanjian" 
                                           placeholder="Contoh: Sewa Gedung Menara Jamsostek" 
                                           value="{{ old('nama_perjanjian') }}" required>
                                    @error('nama_perjanjian')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kantor_id" class="form-label">
                                        Kantor <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select modern-select @error('kantor_id') is-invalid @enderror" id="kantor_id" name="kantor_id" required>
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

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal_mulai" class="form-label">
                                        Tanggal Mulai <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control modern-input @error('tanggal_mulai') is-invalid @enderror" 
                                           id="tanggal_mulai" name="tanggal_mulai" 
                                           value="{{ old('tanggal_mulai') }}" required>
                                    @error('tanggal_mulai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal_selesai" class="form-label">
                                        Tanggal Selesai <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control modern-input @error('tanggal_selesai') is-invalid @enderror" 
                                           id="tanggal_selesai" name="tanggal_selesai" 
                                           value="{{ old('tanggal_selesai') }}" required>
                                    @error('tanggal_selesai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nilai_kontrak" class="form-label">
                                        Nilai Kontrak <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control modern-input @error('nilai_kontrak') is-invalid @enderror" 
                                           id="nilai_kontrak" name="nilai_kontrak" 
                                           placeholder="Contoh: 500000000" 
                                           value="{{ old('nilai_kontrak') }}" step="0.01" required>
                                    @error('nilai_kontrak')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status_perjanjian" class="form-label">
                                        Status Perjanjian <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select modern-select @error('status_perjanjian') is-invalid @enderror" 
                                            id="status_perjanjian" name="status_perjanjian" required>
                                        <option value="Baru" {{ old('status_perjanjian', 'Baru') == 'Baru' ? 'selected' : '' }}>Baru</option>
                                        <option value="Amandemen" {{ old('status_perjanjian') == 'Amandemen' ? 'selected' : '' }}>Amandemen</option>
                                    </select>
                                    @error('status_perjanjian')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">
                                        Status <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select modern-select @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="Aktif" {{ old('status', 'Aktif') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="Tidak Aktif" {{ old('status') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                        <option value="Batal" {{ old('status') == 'Batal' ? 'selected' : '' }}>Batal</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="no_perjanjian_pihak_1" class="form-label">
                                        No Perjanjian Pihak 1 <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control modern-input @error('no_perjanjian_pihak_1') is-invalid @enderror" 
                                           id="no_perjanjian_pihak_1" name="no_perjanjian_pihak_1" 
                                           placeholder="Contoh: ICONNET-2025-01" 
                                           value="{{ old('no_perjanjian_pihak_1') }}" required>
                                    @error('no_perjanjian_pihak_1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="no_perjanjian_pihak_2" class="form-label">
                                        No Perjanjian Pihak 2 <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control modern-input @error('no_perjanjian_pihak_2') is-invalid @enderror" 
                                           id="no_perjanjian_pihak_2" name="no_perjanjian_pihak_2" 
                                           placeholder="Contoh: PLN-2025-01" 
                                           value="{{ old('no_perjanjian_pihak_2') }}" required>
                                    @error('no_perjanjian_pihak_2')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="asset_owner" class="form-label">
                                        Asset Owner <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control modern-input @error('asset_owner') is-invalid @enderror" 
                                           id="asset_owner" name="asset_owner" 
                                           placeholder="Contoh: PT PLN Icon Plus" 
                                           value="{{ old('asset_owner') }}" required>
                                    @error('asset_owner')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sbu" class="form-label" id="sbu_label">Parent Kantor</label>
                                    <div class="input-group">
                                        <select class="form-select modern-select" id="sbu_type" name="parent_kantor" style="max-width: 120px;">
                                            <option value="">Pilih</option>
                                            <option value="Pusat" {{ old('parent_kantor') == 'Pusat' ? 'selected' : '' }}>Pusat</option>
                                            <option value="SBU" {{ old('parent_kantor') == 'SBU' ? 'selected' : '' }}>SBU</option>
                                            <option value="Perwakilan" {{ old('parent_kantor') == 'Perwakilan' ? 'selected' : '' }}>Perwakilan</option>
                                            <option value="Gudang" {{ old('parent_kantor') == 'Gudang' ? 'selected' : '' }}>Gudang</option>
                                        </select>
                                        <input type="text" class="form-control modern-input" id="sbu" name="parent_kantor_nama" 
                                               placeholder="Contoh: SBU Jakarta" value="{{ old('parent_kantor_nama') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ruang_lingkup" class="form-label">Ruang Lingkup</label>
                                    <textarea class="form-control modern-input @error('ruang_lingkup') is-invalid @enderror" 
                                              id="ruang_lingkup" name="ruang_lingkup" rows="3" 
                                              placeholder="Deskripsikan ruang lingkup perjanjian">{{ old('ruang_lingkup') }}</textarea>
                                    @error('ruang_lingkup')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="peruntukan_kantor" class="form-label">Peruntukan Kantor</label>
                                    <select class="form-select modern-select @error('peruntukan_kantor') is-invalid @enderror" 
                                            id="peruntukan_kantor" name="peruntukan_kantor">
                                        <option value="">Pilih Peruntukan</option>
                                        <option value="Kantor SBU" {{ old('peruntukan_kantor') == 'Kantor SBU' ? 'selected' : '' }}>Kantor SBU</option>
                                        <option value="Kantor KP" {{ old('peruntukan_kantor') == 'Kantor KP' ? 'selected' : '' }}>Kantor KP</option>
                                        <option value="Gudang" {{ old('peruntukan_kantor') == 'Gudang' ? 'selected' : '' }}>Gudang</option>
                                    </select>
                                    @error('peruntukan_kantor')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="alamat" class="form-label">
                                        Alamat <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control modern-input @error('alamat') is-invalid @enderror" 
                                              id="alamat" name="alamat" rows="3" 
                                              placeholder="Masukkan alamat lengkap" required>{{ old('alamat') }}</textarea>
                                    @error('alamat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="berita_acara" class="form-label">Berita Acara (PDF)</label>
                                    <input type="file" class="form-control modern-input @error('berita_acara') is-invalid @enderror" 
                                           id="berita_acara" name="berita_acara" accept=".pdf">
                                    <small class="text-muted">Maksimal 10MB, format PDF</small>
                                    @error('berita_acara')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="keterangan" class="form-label">Keterangan</label>
                                    <textarea class="form-control modern-input @error('keterangan') is-invalid @enderror" 
                                              id="keterangan" name="keterangan" rows="3" 
                                              placeholder="Masukkan keterangan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                                    @error('keterangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-modern btn-primary">
                                <i class="fas fa-save"></i> Simpan Kontrak
                            </button>
                            <a href="{{ route('kontrak.index') }}" class="btn btn-modern btn-clear">
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
                        Tips Pengisian
                    </h6>
                </div>
                <div class="guide-body">
                    <div class="guide-section">
                        <h6 class="guide-section-title">
                            <i class="fas fa-file-signature"></i> Nama & Nomor Perjanjian
                        </h6>
                        <p class="guide-text">
                            Isi nama perjanjian yang jelas, misalnya "Sewa Gedung Menara Jamsostek". Nomor perjanjian pihak 1 biasanya dari pihak kita (ICONNET), pihak 2 dari pihak lain (PLN).
                        </p>
                    </div>
                    <div class="guide-section">
                        <h6 class="guide-section-title">
                            <i class="fas fa-calendar-alt"></i> Tanggal & Periode
                        </h6>
                        <p class="guide-text">
                            Tanggal mulai harus lebih dulu dari tanggal selesai. Pastikan periode kontraknya sesuai dengan dokumen aslinya ya.
                        </p>
                    </div>
                    <div class="guide-section">
                        <h6 class="guide-section-title">
                            <i class="fas fa-money-bill-wave"></i> Nilai Kontrak
                        </h6>
                        <p class="guide-text">
                            Masukkan nilai kontrak tanpa titik atau koma. Misalnya kalau 500 juta, tulis 500000000 aja.
                        </p>
                    </div>
                    <div class="guide-section">
                        <h6 class="guide-section-title">
                            <i class="fas fa-info-circle"></i> Status
                        </h6>
                        <p class="guide-text">
                            Status perjanjian itu "Baru" atau "Amandemen". Status kontrak itu "Aktif", "Tidak Aktif", atau "Batal". Jangan sampai ketuker ya.
                        </p>
                    </div>
                    <div class="guide-section">
                        <h6 class="guide-section-title">
                            <i class="fas fa-file-pdf"></i> Berita Acara
                        </h6>
                        <p class="guide-text">
                            Kalau ada file berita acara, upload aja. Formatnya harus PDF dan maksimal 10MB. Ini nggak wajib sih, tapi kalau ada lebih baik.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sbuTypeSelect = document.getElementById('sbu_type');
    const sbuInput = document.getElementById('sbu');

    if (sbuTypeSelect && sbuInput) {
        sbuTypeSelect.addEventListener('change', function() {
            const selectedValue = this.value;
            
            if (selectedValue && selectedValue !== '') {
                switch(selectedValue) {
                    case 'Pusat':
                        sbuInput.placeholder = 'Contoh: Pusat Jakarta Selatan';
                        break;
                    case 'SBU':
                        sbuInput.placeholder = 'Contoh: SBU Jakarta';
                        break;
                    case 'Perwakilan':
                        sbuInput.placeholder = 'Contoh: Perwakilan Jakarta';
                        break;
                    case 'Gudang':
                        sbuInput.placeholder = 'Contoh: Gudang Jakarta';
                        break;
                }
                
                if (sbuInput.value === '') {
                    sbuInput.value = selectedValue + ' ';
                    sbuInput.focus();
                    sbuInput.setSelectionRange(sbuInput.value.length, sbuInput.value.length);
                }
            } else {
                sbuInput.placeholder = 'Contoh: SBU Jakarta';
            }
        });
    }
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

    /* Form Card - Modern Design */
    .kontrak-form-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(33, 97, 140, 0.1);
        border: 1px solid rgba(33, 97, 140, 0.15);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .kontrak-form-header {
        background: white;
        padding: 1.25rem 1.75rem;
        border-bottom: 2px solid var(--pln-blue);
    }

    .kontrak-form-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--pln-blue);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .kontrak-form-title i {
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

    .kontrak-form-body {
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

    /* Input Group */
    .input-group {
        display: flex;
        gap: 0;
    }

    .input-group .form-select {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        border-right: none;
    }

    .input-group .form-control {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
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
