@extends('layouts.app')

@section('title', 'Edit Kantor - PLN Icon Plus Kantor Management')
@section('page-title', 'Edit Kantor')

@section('page-actions')
    <a href="{{ route('kantor.show', $kantor->id) }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-edit"></i>
                    Form Edit Kantor
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('kantor.update', $kantor->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kode_kantor" class="form-label">Kode Kantor</label>
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
                                <label for="nama_kantor" class="form-label">Nama Kantor</label>
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
                                <label for="jenis_kantor_id" class="form-label">Jenis Kantor</label>
                                <select class="form-select" id="jenis_kantor_id" name="jenis_kantor_id" required>
                                    <option value="">Pilih Jenis Kantor</option>
                                    @foreach($jenisKantor as $jk)
                                        <option value="{{ $jk->id }}" {{ $kantor->jenis_kantor_id == $jk->id ? 'selected' : '' }}>
                                            {{ $jk->nama_jenis }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kota_id" class="form-label">Kota</label>
                                <select class="form-select" id="kota_id" name="kota_id" required>
                                    <option value="">Pilih Kota</option>
                                    @foreach($kota as $k)
                                        <option value="{{ $k->id }}" {{ $kantor->kota_id == $k->id ? 'selected' : '' }}>
                                            {{ $k->nama_kota }}, {{ $k->provinsi->nama_provinsi }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3" required>{{ old('alamat', $kantor->alamat) }}</textarea>
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
                                <label for="parent_kantor_id" class="form-label">Parent Kantor (Opsional)</label>
                                <select class="form-select" id="parent_kantor_id" name="parent_kantor_id">
                                    <option value="">Pilih Parent Kantor</option>
                                    @foreach($parentKantor as $pk)
                                        <option value="{{ $pk->id }}" {{ $kantor->parent_kantor_id == $pk->id ? 'selected' : '' }}>
                                            {{ $pk->kode_kantor }} - {{ $pk->nama_kantor }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status_kantor" class="form-label">Status</label>
                                <select class="form-select" id="status_kantor" name="status_kantor" required>
                                    <option value="aktif" {{ $kantor->status_kantor == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="tidak_aktif" {{ $kantor->status_kantor == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
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
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Kantor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
