@extends('layouts.app')

@section('title', 'Tambah Inventaris')
@section('page-title', 'Tambah Inventaris')
@section('page-subtitle', 'Tambah data inventaris PLN Icon Plus')

@section('page-actions')
<a href="{{ route('inventaris.index') }}" class="btn btn-outline-secondary">
    <i class="fas fa-arrow-left"></i> Kembali
</a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">
                            <i class="fas fa-plus me-2"></i>Tambah Inventaris
                        </h3>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('inventaris.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nama_barang" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_barang') is-invalid @enderror"
                                        id="nama_barang" name="nama_barang" value="{{ old('nama_barang') }}" required>
                                    @error('nama_barang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kode_inventaris" class="form-label">Kode Inventaris <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('kode_inventaris') is-invalid @enderror"
                                        id="kode_inventaris" name="kode_inventaris" value="{{ old('kode_inventaris') }}" required>
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
                                    <select class="form-select @error('kategori_id') is-invalid @enderror"
                                        id="kategori_id" name="kategori_id" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach($kategori as $k)
                                        <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>
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
                                    <input type="number" class="form-control @error('jumlah') is-invalid @enderror"
                                        id="jumlah" name="jumlah" value="{{ old('jumlah', 1) }}" min="1" required>
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
                                    <select class="form-select @error('kondisi') is-invalid @enderror"
                                        id="kondisi" name="kondisi" required>
                                        <option value="">Pilih Kondisi</option>
                                        <option value="Baru" {{ old('kondisi') == 'Baru' ? 'selected' : '' }}>Baru</option>
                                        <option value="Baik" {{ old('kondisi') == 'Baik' ? 'selected' : '' }}>Baik</option>
                                        <option value="Rusak Ringan" {{ old('kondisi') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                        <option value="Rusak Berat" {{ old('kondisi') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                                    </select>
                                    @error('kondisi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="bidang_id" class="form-label">Bidang <span class="text-danger">*</span></label>
                                    <select class="form-select @error('bidang_id') is-invalid @enderror"
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
                        </div>

                        <!-- Field baru: Merk, Harga, Tahun -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="merk" class="form-label">Merk</label>
                                    <input type="text" class="form-control @error('merk') is-invalid @enderror"
                                        id="merk" name="merk" value="{{ old('merk') }}" placeholder="Masukkan merk barang">
                                    @error('merk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="harga" class="form-label">Harga</label>
                                    <input type="number" class="form-control @error('harga') is-invalid @enderror"
                                        id="harga" name="harga" value="{{ old('harga') }}" min="0" placeholder="Masukkan harga barang">
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
                                    <input type="number" class="form-control @error('tahun') is-invalid @enderror"
                                        id="tahun" name="tahun" value="{{ old('tahun') }}" min="1900" max="2030" placeholder="Masukkan tahun pembelian">
                                    @error('tahun')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal_pembelian" class="form-label">Tanggal Pembelian</label>
                                    <input type="date" class="form-control @error('tanggal_pembelian') is-invalid @enderror"
                                        id="tanggal_pembelian" name="tanggal_pembelian" value="{{ old('tanggal_pembelian') }}">
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
                                    <select class="form-select @error('lokasi_kantor_id') is-invalid @enderror"
                                        id="lokasi_kantor_id" name="lokasi_kantor_id" required>
                                        <option value="">Pilih Kantor</option>
                                        @foreach($kantor as $k)
                                        <option value="{{ $k->id }}" {{ old('lokasi_kantor_id') == $k->id ? 'selected' : '' }}>
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
                                    <select class="form-select @error('lokasi_gedung_id') is-invalid @enderror"
                                        id="lokasi_gedung_id" name="lokasi_gedung_id" required>
                                        <option value="">Pilih Gedung</option>
                                        @foreach($gedung as $g)
                                        <option value="{{ $g->id }}" {{ old('lokasi_gedung_id') == $g->id ? 'selected' : '' }}>
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
                                    <select class="form-select @error('lokasi_lantai_id') is-invalid @enderror"
                                        id="lokasi_lantai_id" name="lokasi_lantai_id" required>
                                        <option value="">Pilih Lantai</option>
                                        @foreach($lantai as $l)
                                        <option value="{{ $l->id }}" {{ old('lokasi_lantai_id') == $l->id ? 'selected' : '' }}>
                                            {{ $l->nama_lantai }}
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
                                    <select class="form-select @error('lokasi_ruang_id') is-invalid @enderror"
                                        id="lokasi_ruang_id" name="lokasi_ruang_id" required>
                                        <option value="">Pilih Ruang</option>
                                        @foreach($ruang as $r)
                                        <option value="{{ $r->id }}" {{ old('lokasi_ruang_id') == $r->id ? 'selected' : '' }}>
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
                                    <select class="form-select @error('sub_bidang_id') is-invalid @enderror"
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
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal_input" class="form-label">Tanggal Input <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('tanggal_input') is-invalid @enderror"
                                        id="tanggal_input" name="tanggal_input" value="{{ old('tanggal_input', date('Y-m-d')) }}" required>
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
                                    <input type="file" class="form-control @error('gambar') is-invalid @enderror"
                                        id="gambar" name="gambar" accept="image/*">
                                    @error('gambar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label">Deskripsi</label>
                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror"
                                        id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi') }}</textarea>
                                    @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('inventaris.index') }}" class="btn btn-outline-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>@endsection
