@extends('layouts.app')

@section('title', 'Tambah Kantor - PLN Icon Plus Kantor Management')
@section('page-title', 'Tambah Kantor Baru')

@section('page-actions')
    <a href="{{ route('kantor.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">Tambah Kantor</h2>
                <p class="text-muted mb-0">Buat kantor baru PLN Icon Plus</p>
            </div>
            <a href="{{ route('kantor.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>

        <!-- Form -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-building me-2"></i>Informasi Kantor
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('kantor.store') }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="kode_kantor" class="form-label">
                                            Kode Kantor <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control @error('kode_kantor') is-invalid @enderror" 
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
                                        <input type="text" class="form-control @error('nama_kantor') is-invalid @enderror" 
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
                                <input type="number" step="0.01" min="0" class="form-control" id="luas_tanah" name="luas_tanah" value="{{ old('luas_tanah') }}" placeholder="Contoh: 1500">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="luas_bangunan" class="form-label">Luas Bangunan (m<sup>2</sup>)</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="luas_bangunan" name="luas_bangunan" value="{{ old('luas_bangunan') }}" placeholder="Contoh: 1200">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="daya_listrik_va" class="form-label">Daya Listrik (VA)</label>
                                <input type="number" min="0" class="form-control" id="daya_listrik_va" name="daya_listrik_va" value="{{ old('daya_listrik_va') }}" placeholder="Contoh: 66000">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kapasitas_genset_kva" class="form-label">Kapasitas Genset (kVA)</label>
                                <input type="number" min="0" class="form-control" id="kapasitas_genset_kva" name="kapasitas_genset_kva" value="{{ old('kapasitas_genset_kva') }}" placeholder="Contoh: 80">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jumlah_sumur" class="form-label">Jumlah Sumur/Air Tanah</label>
                                <input type="number" min="0" class="form-control" id="jumlah_sumur" name="jumlah_sumur" value="{{ old('jumlah_sumur') }}" placeholder="Contoh: 2">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jumlah_septictank" class="form-label">Jumlah Septic Tank/Biofil</label>
                                <input type="number" min="0" class="form-control" id="jumlah_septictank" name="jumlah_septictank" value="{{ old('jumlah_septictank') }}" placeholder="Contoh: 3">
                            </div>
                        </div>
                    </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="jenis_kantor_id" class="form-label">
                                            Jenis Kantor <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('jenis_kantor_id') is-invalid @enderror" id="jenis_kantor_id" name="jenis_kantor_id" required>
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
                                        <select class="form-select @error('kota_id') is-invalid @enderror" id="kota_id" name="kota_id" required>
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
                                <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3" 
                                          placeholder="Masukkan alamat lengkap kantor" required>{{ old('alamat') }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="latitude" class="form-label">Latitude</label>
                                <input type="text" class="form-control" id="latitude" name="latitude" 
                                       placeholder="Contoh: -6.2088">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="longitude" class="form-label">Longitude</label>
                                <input type="text" class="form-control" id="longitude" name="longitude" 
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
                                        <select class="form-select @error('status_kantor') is-invalid @enderror" id="status_kantor" name="status_kantor" required>
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
                                        <select class="form-select @error('parent_kantor_id') is-invalid @enderror" id="parent_kantor_id" name="parent_kantor_id">
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
                                <select class="form-select" id="status_kepemilikan" name="status_kepemilikan" required>
                                    <option value="sewa" {{ old('status_kepemilikan', 'sewa') === 'sewa' ? 'selected' : '' }}>Sewa</option>
                                    <option value="milik" {{ old('status_kepemilikan') === 'milik' ? 'selected' : '' }}>Milik</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jenis_kepemilikan" class="form-label">Jenis Kepemilikan</label>
                                <select class="form-select" id="jenis_kepemilikan" name="jenis_kepemilikan" required>
                                    <option value="tunai" {{ old('jenis_kepemilikan', 'tunai') === 'tunai' ? 'selected' : '' }}>Tunai</option>
                                    <option value="non_tunai" {{ old('jenis_kepemilikan') === 'non_tunai' ? 'selected' : '' }}>Non Tunai</option>
                                    <option value="non_pln" {{ old('jenis_kepemilikan') === 'non_pln' ? 'selected' : '' }}>Non PLN</option>
                                </select>
                            </div>
                        </div>
                    </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('kantor.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-save me-2"></i>Simpan Kantor
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>Panduan Pengisian
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="text-uppercase text-muted mb-2">Informasi Utama</h6>
                            <ul class="list-unstyled small text-muted mb-0">
                                <li>• Kode Kantor: Unik identifier</li>
                                <li>• Nama Kantor: Nama resmi kantor</li>
                                <li>• Jenis: Tipe kantor (Pusat, Cabang, dll)</li>
                                <li>• Lokasi: Kota dan provinsi</li>
                            </ul>
                        </div>

                        <div class="mb-3">
                            <h6 class="text-uppercase text-muted mb-2">Informasi Tambahan</h6>
                            <ul class="list-unstyled small text-muted mb-0">
                                <li>• Alamat: Lokasi lengkap kantor</li>
                                <li>• Koordinat: GPS latitude & longitude</li>
                                <li>• Parent: Kantor induk (jika ada)</li>
                                <li>• Status: Aktif atau tidak aktif</li>
                            </ul>
                        </div>

                        <div class="alert alert-light border mt-3">
                            <i class="fas fa-info-circle me-2"></i>
                            <span class="small">Pastikan data yang diisi sudah benar dan akurat.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
