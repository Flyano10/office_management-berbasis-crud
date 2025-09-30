@extends('layouts.app')

@section('title', 'Tambah Kantor - PLN Icon Plus Kantor Management')
@section('page-title', 'Tambah Kantor Baru')

@section('page-actions')
    <a href="{{ route('kantor.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-plus"></i>
                    Form Tambah Kantor
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('kantor.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kode_kantor" class="form-label">Kode Kantor</label>
                                <input type="text" class="form-control" id="kode_kantor" name="kode_kantor" 
                                       placeholder="Contoh: IC-0001" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama_kantor" class="form-label">Nama Kantor</label>
                                <input type="text" class="form-control" id="nama_kantor" name="nama_kantor" 
                                       placeholder="Contoh: Kantor Pusat" required>
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
                                        <option value="{{ $jk->id }}">{{ $jk->nama_jenis }}</option>
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
                                        <option value="{{ $k->id }}">{{ $k->nama_kota }}, {{ $k->provinsi->nama_provinsi }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3" 
                                  placeholder="Masukkan alamat lengkap kantor" required></textarea>
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
                                <label for="parent_kantor_id" class="form-label">Parent Kantor (Opsional)</label>
                                <select class="form-select" id="parent_kantor_id" name="parent_kantor_id">
                                    <option value="">Pilih Parent Kantor</option>
                                    @foreach($parentKantor as $pk)
                                        <option value="{{ $pk->id }}">{{ $pk->kode_kantor }} - {{ $pk->nama_kantor }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status_kantor" class="form-label">Status Operasional</label>
                                <select class="form-select" id="status_kantor" name="status_kantor" required>
                                    <option value="aktif">Aktif</option>
                                    <option value="tidak_aktif">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status_kepemilikan" class="form-label">Status Kepemilikan</label>
                                <select class="form-select" id="status_kepemilikan" name="status_kepemilikan" required>
                                    <option value="sewa">Sewa</option>
                                    <option value="milik">Milik</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Kantor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
