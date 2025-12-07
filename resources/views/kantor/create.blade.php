@extends('layouts.app')

@section('title', 'Tambah Kantor - PLN Icon Plus Kantor Management')
@section('page-title', 'Tambah Kantor Baru')
@section('page-subtitle', 'Buat kantor baru PLN Icon Plus')

@section('page-actions')
    <a href="{{ route('kantor.index') }}" class="btn btn-modern btn-clear">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
@endsection

@section('content')
    <div class="container-fluid">

        <!-- Form -->
        <div class="row">
            <div class="col-lg-8">
                <div class="kantor-form-card">
                    <div class="kantor-form-header">
                        <h5 class="kantor-form-title">
                            <i class="fas fa-building"></i>
                            Informasi Kantor
                        </h5>
                    </div>
                    <div class="kantor-form-body">
                        <form action="{{ route('kantor.store') }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="kode_kantor" class="form-label">
                                            Kode Kantor <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control modern-input @error('kode_kantor') is-invalid @enderror" 
                                               id="kode_kantor" name="kode_kantor" 
                                               value="{{ old('kode_kantor') }}" 
                                               placeholder="Contoh: IC-0001" required>
                                        @error('kode_kantor')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nama_kantor" class="form-label">
                                            Nama Kantor <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control modern-input @error('nama_kantor') is-invalid @enderror" 
                                               id="nama_kantor" name="nama_kantor" 
                                               value="{{ old('nama_kantor') }}" 
                                               placeholder="Contoh: Kantor Pusat" required>
                                        @error('nama_kantor')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="luas_tanah" class="form-label">Luas Tanah (m<sup>2</sup>)</label>
                                <input type="number" step="0.01" min="0" class="form-control modern-input" id="luas_tanah" name="luas_tanah" value="{{ old('luas_tanah') }}" placeholder="Contoh: 1500">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="luas_bangunan" class="form-label">Luas Bangunan (m<sup>2</sup>)</label>
                                <input type="number" step="0.01" min="0" class="form-control modern-input" id="luas_bangunan" name="luas_bangunan" value="{{ old('luas_bangunan') }}" placeholder="Contoh: 1200">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="daya_listrik_va" class="form-label">Daya Listrik (VA)</label>
                                <input type="number" min="0" class="form-control modern-input" id="daya_listrik_va" name="daya_listrik_va" value="{{ old('daya_listrik_va') }}" placeholder="Contoh: 66000">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kapasitas_genset_kva" class="form-label">Kapasitas Genset (kVA)</label>
                                <input type="number" min="0" class="form-control modern-input" id="kapasitas_genset_kva" name="kapasitas_genset_kva" value="{{ old('kapasitas_genset_kva') }}" placeholder="Contoh: 80">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jumlah_sumur" class="form-label">Jumlah Sumur/Air Tanah</label>
                                <input type="number" min="0" class="form-control modern-input" id="jumlah_sumur" name="jumlah_sumur" value="{{ old('jumlah_sumur') }}" placeholder="Contoh: 2">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jumlah_septictank" class="form-label">Jumlah Septic Tank/Biofil</label>
                                <input type="number" min="0" class="form-control modern-input" id="jumlah_septictank" name="jumlah_septictank" value="{{ old('jumlah_septictank') }}" placeholder="Contoh: 3">
                            </div>
                        </div>
                    </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="jenis_kantor_id" class="form-label">
                                            Jenis Kantor <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select modern-select @error('jenis_kantor_id') is-invalid @enderror" id="jenis_kantor_id" name="jenis_kantor_id" required>
                                            <option value="">Pilih Jenis Kantor</option>
                                            @foreach($jenisKantor as $jk)
                                                <option value="{{ $jk->id }}" {{ old('jenis_kantor_id') == $jk->id ? 'selected' : '' }}>
                                                    {{ $jk->nama_jenis }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('jenis_kantor_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="kota_id" class="form-label">
                                            Kota <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select modern-select @error('kota_id') is-invalid @enderror" id="kota_id" name="kota_id" required>
                                            <option value="">Pilih Kota</option>
                                            @foreach($kota as $k)
                                                <option value="{{ $k->id }}" {{ old('kota_id') == $k->id ? 'selected' : '' }}>
                                                    {{ $k->nama_kota }}, {{ $k->provinsi->nama_provinsi }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('kota_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="alamat" class="form-label">
                                    Alamat <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control modern-input @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3" 
                                          placeholder="Masukkan alamat lengkap kantor" required>{{ old('alamat') }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="latitude" class="form-label">Latitude</label>
                                <input type="text" class="form-control modern-input" id="latitude" name="latitude" 
                                       placeholder="Contoh: -6.2088">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="longitude" class="form-label">Longitude</label>
                                <input type="text" class="form-control modern-input" id="longitude" name="longitude" 
                                       placeholder="Contoh: 106.8456">
                            </div>
                        </div>
                    </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status_kantor" class="form-label">
                                            Status Operasional <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select modern-select @error('status_kantor') is-invalid @enderror" id="status_kantor" name="status_kantor" required>
                                            <option value="aktif" {{ old('status_kantor', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                            <option value="tidak_aktif" {{ old('status_kantor') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                        </select>
                                        @error('status_kantor')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="parent_kantor_id" class="form-label">Parent Kantor (Opsional)</label>
                                        <select class="form-select modern-select @error('parent_kantor_id') is-invalid @enderror" id="parent_kantor_id" name="parent_kantor_id">
                                            <option value="">Pilih Parent Kantor</option>
                                            @foreach($parentKantor as $pk)
                                                <option value="{{ $pk->id }}" {{ old('parent_kantor_id') == $pk->id ? 'selected' : '' }}>
                                                    {{ $pk->nama_kantor }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('parent_kantor_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status_kepemilikan" class="form-label">Status Kepemilikan</label>
                                <select class="form-select modern-select" id="status_kepemilikan" name="status_kepemilikan" required>
                                    <option value="sewa" {{ old('status_kepemilikan', 'sewa') === 'sewa' ? 'selected' : '' }}>Sewa</option>
                                    <option value="milik" {{ old('status_kepemilikan') === 'milik' ? 'selected' : '' }}>Milik</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jenis_kepemilikan" class="form-label">Jenis Kepemilikan</label>
                                <select class="form-select modern-select" id="jenis_kepemilikan" name="jenis_kepemilikan" required>
                                    <option value="tunai" {{ old('jenis_kepemilikan', 'tunai') === 'tunai' ? 'selected' : '' }}>Tunai</option>
                                    <option value="non_tunai" {{ old('jenis_kepemilikan') === 'non_tunai' ? 'selected' : '' }}>Non Tunai</option>
                                    <option value="non_pln" {{ old('jenis_kepemilikan') === 'non_pln' ? 'selected' : '' }}>Non PLN</option>
                                </select>
                            </div>
                        </div>
                    </div>

                            <div class="form-actions">
                                <a href="{{ route('kantor.index') }}" class="btn btn-modern btn-clear">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-modern btn-primary">
                                    <i class="fas fa-save"></i> Simpan Kantor
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
                                <li><i class="fas fa-check-circle"></i> Kode Kantor: Unik identifier</li>
                                <li><i class="fas fa-check-circle"></i> Nama Kantor: Nama resmi kantor</li>
                                <li><i class="fas fa-check-circle"></i> Jenis: Tipe kantor (Pusat, Cabang, dll)</li>
                                <li><i class="fas fa-check-circle"></i> Lokasi: Kota dan provinsi</li>
                            </ul>
                        </div>

                        <div class="guide-section">
                            <h6 class="guide-section-title">Informasi Tambahan</h6>
                            <ul class="guide-list">
                                <li><i class="fas fa-check-circle"></i> Alamat: Lokasi lengkap kantor</li>
                                <li><i class="fas fa-check-circle"></i> Koordinat: GPS latitude & longitude</li>
                                <li><i class="fas fa-check-circle"></i> Parent: Kantor induk (jika ada)</li>
                                <li><i class="fas fa-check-circle"></i> Status: Aktif atau tidak aktif</li>
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
    .kantor-form-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(33, 97, 140, 0.1);
        border: 1px solid rgba(33, 97, 140, 0.15);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .kantor-form-header {
        background: white;
        padding: 1.25rem 1.75rem;
        border-bottom: 2px solid var(--pln-blue);
    }

    .kantor-form-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--pln-blue);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .kantor-form-title i {
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

    .kantor-form-body {
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
        .kantor-form-body {
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
