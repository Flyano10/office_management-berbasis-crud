@extends('layouts.app')

@section('title', 'Tambah Gedung - PLN Icon Plus Kantor Management')
@section('page-title', 'Tambah Gedung Baru')

@section('page-actions')
    <a href="{{ route('gedung.index') }}" class="btn btn-outline-secondary">
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
                    Form Tambah Gedung
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('gedung.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama_gedung" class="form-label">Nama Gedung</label>
                                <input type="text" class="form-control" id="nama_gedung" name="nama_gedung" 
                                       placeholder="Contoh: Gedung A" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kantor_id" class="form-label">Kantor</label>
                                <select class="form-select" id="kantor_id" name="kantor_id" required>
                                    <option value="">Pilih Kantor</option>
                                    @foreach($kantor as $k)
                                        <option value="{{ $k->id }}">{{ $k->kode_kantor }} - {{ $k->nama_kantor }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat Gedung</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3" 
                                  placeholder="Masukkan alamat lengkap gedung" required></textarea>
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
                                <label for="status_gedung" class="form-label">Status</label>
                                <select class="form-select" id="status_gedung" name="status_gedung" required>
                                    <option value="aktif">Aktif</option>
                                    <option value="non_aktif">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status_kepemilikan" class="form-label">Status Kepemilikan</label>
                                <select class="form-select" id="status_kepemilikan" name="status_kepemilikan" required>
                                    <option value="milik">Milik</option>
                                    <option value="sewa">Sewa</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="layout_gedung" class="form-label">Layout Gedung (opsional)</label>
                        <input type="file" class="form-control" id="layout_gedung" name="layout_gedung" accept=".pdf,.jpg,.jpeg,.png,.svg">
                        <div class="form-text">Unggah layout gedung dalam format PDF atau gambar (maks. 20 MB).</div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Gedung
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
