@extends('layouts.app')

@section('title', 'Edit Kantor - PLN Icon Plus Kantor Management')
@section('page-title', 'Edit Kantor')

@section('page-actions')
    <a href="{{ route('kantor.show', $kantor->id) }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">Edit Kantor</h2>
                <p class="text-muted mb-0">Perbarui informasi kantor: {{ $kantor->nama_kantor }}</p>
            </div>
            <a href="{{ route('kantor.show', $kantor->id) }}" class="btn btn-outline-secondary">
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
                    <form action="{{ route('kantor.update', $kantor->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kode_kantor" class="form-label">
                                        Kode Kantor <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('kode_kantor') is-invalid @enderror" 
                                           id="kode_kantor" name="kode_kantor" 
                                           value="{{ old('kode_kantor', $kantor->kode_kantor) }}" required>
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
                                           value="{{ old('nama_kantor', $kantor->nama_kantor) }}" required>
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
                                <input type="number" step="0.01" min="0" class="form-control" id="luas_tanah" name="luas_tanah" value="{{ old('luas_tanah', $kantor->luas_tanah) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="luas_bangunan" class="form-label">Luas Bangunan (m<sup>2</sup>)</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="luas_bangunan" name="luas_bangunan" value="{{ old('luas_bangunan', $kantor->luas_bangunan) }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="daya_listrik_va" class="form-label">Daya Listrik (VA)</label>
                                <input type="number" min="0" class="form-control" id="daya_listrik_va" name="daya_listrik_va" value="{{ old('daya_listrik_va', $kantor->daya_listrik_va) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kapasitas_genset_kva" class="form-label">Kapasitas Genset (kVA)</label>
                                <input type="number" min="0" class="form-control" id="kapasitas_genset_kva" name="kapasitas_genset_kva" value="{{ old('kapasitas_genset_kva', $kantor->kapasitas_genset_kva) }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jumlah_sumur" class="form-label">Jumlah Sumur/Air Tanah</label>
                                <input type="number" min="0" class="form-control" id="jumlah_sumur" name="jumlah_sumur" value="{{ old('jumlah_sumur', $kantor->jumlah_sumur) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jumlah_septictank" class="form-label">Jumlah Septic Tank/Biofil</label>
                                <input type="number" min="0" class="form-control" id="jumlah_septictank" name="jumlah_septictank" value="{{ old('jumlah_septictank', $kantor->jumlah_septictank) }}">
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
                                            <option value="{{ $jk->id }}" {{ $kantor->jenis_kantor_id == $jk->id ? 'selected' : '' }}>
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
                                            <option value="{{ $k->id }}" {{ $kantor->kota_id == $k->id ? 'selected' : '' }}>
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
                            <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3" required>{{ old('alamat', $kantor->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="latitude" class="form-label">Latitude</label>
                                <input type="text" class="form-control" id="latitude" name="latitude" 
                                       value="{{ old('latitude', $kantor->latitude) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="longitude" class="form-label">Longitude</label>
                                <input type="text" class="form-control" id="longitude" name="longitude" 
                                       value="{{ old('longitude', $kantor->longitude) }}">
                            </div>
                        </div>
                    </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status_kantor" class="form-label">
                                        Status <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('status_kantor') is-invalid @enderror" id="status_kantor" name="status_kantor" required>
                                        <option value="aktif" {{ $kantor->status_kantor == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="tidak_aktif" {{ $kantor->status_kantor == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
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
                                            <option value="{{ $pk->id }}" {{ old('parent_kantor_id', $kantor->parent_kantor_id) == $pk->id ? 'selected' : '' }}>
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
                                    <option value="milik" {{ $kantor->status_kepemilikan == 'milik' ? 'selected' : '' }}>Milik</option>
                                    <option value="sewa" {{ $kantor->status_kepemilikan == 'sewa' ? 'selected' : '' }}>Sewa</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jenis_kepemilikan" class="form-label">Jenis Kepemilikan</label>
                                <select class="form-select" id="jenis_kepemilikan" name="jenis_kepemilikan" required>
                                    <option value="tunai" {{ $kantor->jenis_kepemilikan == 'tunai' ? 'selected' : '' }}>Tunai</option>
                                    <option value="non_tunai" {{ $kantor->jenis_kepemilikan == 'non_tunai' ? 'selected' : '' }}>Non Tunai</option>
                                    <option value="non_pln" {{ $kantor->jenis_kepemilikan == 'non_pln' ? 'selected' : '' }}>Non PLN</option>
                                </select>
                            </div>
                        </div>
                    </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('kantor.show', $kantor->id) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="fas fa-save me-2"></i>Update Kantor
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>Informasi Kantor
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="text-muted">ID Kantor</h6>
                            <p class="mb-0">{{ $kantor->id }}</p>
                        </div>
                        <div class="mb-3">
                            <h6 class="text-muted">Dibuat</h6>
                            <p class="mb-0">{{ $kantor->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="mb-3">
                            <h6 class="text-muted">Terakhir Diupdate</h6>
                            <p class="mb-0">{{ $kantor->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="mb-3">
                            <h6 class="text-muted">Status Saat Ini</h6>
                            <span class="badge bg-light text-dark">{{ ucfirst($kantor->status_kantor) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
