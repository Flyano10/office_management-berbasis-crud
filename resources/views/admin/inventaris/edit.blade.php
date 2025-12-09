@extends('layouts.app')

@section('title', 'Edit Inventaris - PLN Icon Plus Kantor Management')
@section('page-title', 'Edit Inventaris')
@section('page-subtitle', 'Ubah data inventaris')

@section('page-actions')
    <a href="{{ route('inventaris.index') }}" class="btn btn-modern btn-clear">
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
                        <i class="fas fa-edit"></i>
                        Form Edit Inventaris
                    </h5>
                </div>
                <div class="kontrak-form-body">
                    <form method="POST" action="{{ route('inventaris.update', $inventaris->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nama_barang" class="form-label">
                                        Nama Barang <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control modern-input @error('nama_barang') is-invalid @enderror" 
                                           id="nama_barang" name="nama_barang" 
                                           placeholder="Contoh: Meja Kerja, Kursi, Laptop, dll" 
                                           value="{{ old('nama_barang', $inventaris->nama_barang) }}" required>
                                    @error('nama_barang')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kode_inventaris" class="form-label">Kode Inventaris <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control modern-input @error('kode_inventaris') is-invalid @enderror" 
                                           id="kode_inventaris" name="kode_inventaris" 
                                           placeholder="Contoh: INV-001, INV-2024-001" 
                                           value="{{ old('kode_inventaris', $inventaris->kode_inventaris) }}" required>
                                    @error('kode_inventaris')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kategori_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                                    <select class="form-select modern-select @error('kategori_id') is-invalid @enderror" 
                                            id="kategori_id" name="kategori_id" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach($kategori as $k)
                                            <option value="{{ $k->id }}" {{ old('kategori_id', $inventaris->kategori_id) == $k->id ? 'selected' : '' }}>
                                                {{ $k->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kategori_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jumlah" class="form-label">Jumlah <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control modern-input @error('jumlah') is-invalid @enderror" 
                                           id="jumlah" name="jumlah" value="{{ old('jumlah', $inventaris->jumlah) }}" min="1" required>
                                    @error('jumlah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kondisi" class="form-label">Kondisi <span class="text-danger">*</span></label>
                                    <select class="form-select modern-select @error('kondisi') is-invalid @enderror" 
                                            id="kondisi" name="kondisi" required>
                                        <option value="">Pilih Kondisi</option>
                                        <option value="Baru" {{ old('kondisi', $inventaris->kondisi) == 'Baru' ? 'selected' : '' }}>Baru</option>
                                        <option value="Baik" {{ old('kondisi', $inventaris->kondisi) == 'Baik' ? 'selected' : '' }}>Baik</option>
                                        <option value="Rusak Ringan" {{ old('kondisi', $inventaris->kondisi) == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                        <option value="Rusak Berat" {{ old('kondisi', $inventaris->kondisi) == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                                    </select>
                                    @error('kondisi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="bidang_id" class="form-label">Bidang <span class="text-danger">*</span></label>
                                    <select class="form-select modern-select @error('bidang_id') is-invalid @enderror" 
                                            id="bidang_id" name="bidang_id" required>
                                        <option value="">Pilih Bidang</option>
                                        @foreach($bidang as $b)
                                            <option value="{{ $b->id }}" {{ old('bidang_id', $inventaris->bidang_id) == $b->id ? 'selected' : '' }}>
                                                {{ $b->nama_bidang }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('bidang_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Field baru: Merk, Harga, Tahun -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="merk" class="form-label">Merk</label>
                                    <input type="text" class="form-control modern-input @error('merk') is-invalid @enderror"
                                        id="merk" name="merk" value="{{ old('merk', $inventaris->merk) }}" 
                                        placeholder="Contoh: IKEA, Herman Miller, Dell">
                                    @error('merk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="harga" class="form-label">Harga</label>
                                    <input type="number" class="form-control modern-input @error('harga') is-invalid @enderror"
                                        id="harga" name="harga" value="{{ old('harga', $inventaris->harga) }}" min="0" 
                                        placeholder="Masukkan harga tanpa titik atau koma">
                                    @error('harga')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tahun" class="form-label">Tahun</label>
                                    <input type="number" class="form-control modern-input @error('tahun') is-invalid @enderror"
                                        id="tahun" name="tahun" value="{{ old('tahun', $inventaris->tahun) }}" min="1900" max="2030" 
                                        placeholder="Tahun pembelian, contoh: 2024">
                                    @error('tahun')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal_pembelian" class="form-label">Tanggal Pembelian</label>
                                    <input type="date" class="form-control modern-input @error('tanggal_pembelian') is-invalid @enderror"
                                        id="tanggal_pembelian" name="tanggal_pembelian" value="{{ old('tanggal_pembelian', $inventaris->tanggal_pembelian) }}">
                                    @error('tanggal_pembelian')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Lokasi fields -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="lokasi_kantor_id" class="form-label">Kantor <span class="text-danger">*</span></label>
                                    <select class="form-select modern-select @error('lokasi_kantor_id') is-invalid @enderror" 
                                            id="lokasi_kantor_id" name="lokasi_kantor_id" required>
                                        <option value="">Pilih Kantor</option>
                                        @foreach($kantor as $k)
                                            <option value="{{ $k->id }}" {{ old('lokasi_kantor_id', $inventaris->lokasi_kantor_id) == $k->id ? 'selected' : '' }}>
                                                {{ $k->nama_kantor }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('lokasi_kantor_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="lokasi_gedung_id" class="form-label">Gedung <span class="text-danger">*</span></label>
                                    <select class="form-select modern-select @error('lokasi_gedung_id') is-invalid @enderror" 
                                            id="lokasi_gedung_id" name="lokasi_gedung_id" required>
                                        <option value="">Pilih Gedung</option>
                                        @foreach($gedung as $g)
                                            <option value="{{ $g->id }}" {{ old('lokasi_gedung_id', $inventaris->lokasi_gedung_id) == $g->id ? 'selected' : '' }}>
                                                {{ $g->nama_gedung }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('lokasi_gedung_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="lokasi_lantai_id" class="form-label">Lantai <span class="text-danger">*</span></label>
                                    <select class="form-select modern-select @error('lokasi_lantai_id') is-invalid @enderror" 
                                            id="lokasi_lantai_id" name="lokasi_lantai_id" required>
                                        <option value="">Pilih Lantai</option>
                                        @foreach($lantai as $l)
                                            <option value="{{ $l->id }}" {{ old('lokasi_lantai_id', $inventaris->lokasi_lantai_id) == $l->id ? 'selected' : '' }}>
                                                {{ $l->nomor_lantai ? 'Lantai ' . $l->nomor_lantai . ' - ' : '' }}{{ $l->nama_lantai }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('lokasi_lantai_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="lokasi_ruang_id" class="form-label">Ruang <span class="text-danger">*</span></label>
                                    <select class="form-select modern-select @error('lokasi_ruang_id') is-invalid @enderror" 
                                            id="lokasi_ruang_id" name="lokasi_ruang_id" required>
                                        <option value="">Pilih Ruang</option>
                                        @foreach($ruang as $r)
                                            <option value="{{ $r->id }}" {{ old('lokasi_ruang_id', $inventaris->lokasi_ruang_id) == $r->id ? 'selected' : '' }}>
                                                {{ $r->nama_ruang }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('lokasi_ruang_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sub_bidang_id" class="form-label">Sub Bidang</label>
                                    <select class="form-select modern-select @error('sub_bidang_id') is-invalid @enderror" 
                                            id="sub_bidang_id" name="sub_bidang_id">
                                        <option value="">Pilih Sub Bidang (Opsional)</option>
                                        @foreach($subBidang as $sb)
                                            <option value="{{ $sb->id }}" {{ old('sub_bidang_id', $inventaris->sub_bidang_id) == $sb->id ? 'selected' : '' }}>
                                                {{ $sb->nama_sub_bidang }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('sub_bidang_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal_input" class="form-label">Tanggal Input <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control modern-input @error('tanggal_input') is-invalid @enderror" 
                                           id="tanggal_input" name="tanggal_input" value="{{ old('tanggal_input', $inventaris->tanggal_input->format('Y-m-d')) }}" required>
                                    @error('tanggal_input')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="gambar" class="form-label">Gambar</label>
                                    @if($inventaris->gambar)
                                        <div class="mb-2">
                                            <img src="{{ asset($inventaris->gambar) }}" alt="Gambar saat ini" class="img-thumbnail" style="max-width: 150px;">
                                            <p class="text-muted small">Gambar saat ini</p>
                                        </div>
                                    @endif
                                    <input type="file" class="form-control modern-input @error('gambar') is-invalid @enderror" 
                                           id="gambar" name="gambar" accept="image/*">
                                    <small class="text-muted">Format: JPG, PNG, maksimal 5MB</small>
                                    @error('gambar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label">Deskripsi</label>
                                    <textarea class="form-control modern-input @error('deskripsi') is-invalid @enderror" 
                                              id="deskripsi" name="deskripsi" rows="3" 
                                              placeholder="Masukkan deskripsi barang (opsional)">{{ old('deskripsi', $inventaris->deskripsi) }}</textarea>
                                    @error('deskripsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-modern btn-primary">
                                <i class="fas fa-save"></i> Update Inventaris
                            </button>
                            <a href="{{ route('inventaris.index') }}" class="btn btn-modern btn-clear">
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
                            <i class="fas fa-tag"></i> Nama & Kode Barang
                        </h6>
                        <p class="guide-text">
                            Nama barang tulis yang jelas aja, misalnya "Meja Kerja" atau "Laptop Dell". Kode inventaris biasanya pakai format INV-001 atau INV-2024-001. Kalau belum ada sistemnya, bisa buat sendiri yang konsisten.
                        </p>
                    </div>
                    <div class="guide-section">
                        <h6 class="guide-section-title">
                            <i class="fas fa-check-circle"></i> Kondisi Barang
                        </h6>
                        <p class="guide-text">
                            Pilih kondisi yang sesuai. Baru berarti masih baru banget, Baik berarti masih layak pakai, Rusak Ringan berarti ada masalah kecil tapi masih bisa dipakai, Rusak Berat berarti udah nggak bisa dipakai lagi.
                        </p>
                    </div>
                    <div class="guide-section">
                        <h6 class="guide-section-title">
                            <i class="fas fa-map-marker-alt"></i> Lokasi
                        </h6>
                        <p class="guide-text">
                            Pilih lokasi dari yang paling besar dulu (Kantor), terus turun ke Gedung, Lantai, dan Ruang. Pastikan semua dipilih ya, soalnya ini wajib diisi. Kalau barangnya ada di ruang tertentu, pilih ruangnya yang spesifik.
                        </p>
                    </div>
                    <div class="guide-section">
                        <h6 class="guide-section-title">
                            <i class="fas fa-money-bill-wave"></i> Harga & Tahun
                        </h6>
                        <p class="guide-text">
                            Harga tulis tanpa titik atau koma. Misalnya kalau 5 juta, tulis 5000000 aja. Tahun pembelian isi tahunnya aja, misalnya 2024. Kalau nggak tahu tahunnya, bisa dikosongin dulu.
                        </p>
                    </div>
                    <div class="guide-section">
                        <h6 class="guide-section-title">
                            <i class="fas fa-image"></i> Gambar
                        </h6>
                        <p class="guide-text">
                            Kalau ada foto barangnya, upload aja. Formatnya JPG atau PNG, maksimal 5MB. Ini nggak wajib sih, tapi kalau ada lebih bagus biar lebih jelas barangnya kayak apa. Kalau udah ada gambar sebelumnya, upload baru akan ganti yang lama.
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
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .guide-text {
        font-size: 0.875rem;
        color: var(--text-gray);
        line-height: 1.5;
        margin: 0;
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
