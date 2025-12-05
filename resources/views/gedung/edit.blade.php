@extends('layouts.app')

@section('title', 'Edit Gedung - PLN Icon Plus Kantor Management')
@section('page-title', 'Edit Gedung')

@section('page-actions')
    <a href="{{ route('gedung.show', $gedung->id) }}" class="btn btn-outline-secondary">
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
                    Form Edit Gedung
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('gedung.update', $gedung->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama_gedung" class="form-label">Nama Gedung</label>
                                <input type="text" class="form-control" id="nama_gedung" name="nama_gedung" 
                                       value="{{ old('nama_gedung', $gedung->nama_gedung) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kantor_id" class="form-label">Kantor</label>
                                <select class="form-select" id="kantor_id" name="kantor_id" required>
                                    <option value="">Pilih Kantor</option>
                                    @foreach($kantor as $k)
                                        <option value="{{ $k->id }}" {{ $gedung->kantor_id == $k->id ? 'selected' : '' }}>
                                            {{ $k->kode_kantor }} - {{ $k->nama_kantor }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat Gedung</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3" required>{{ old('alamat', $gedung->alamat) }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="latitude" class="form-label">Latitude</label>
                                <input type="text" class="form-control" id="latitude" name="latitude" 
                                       value="{{ old('latitude', $gedung->latitude) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="longitude" class="form-label">Longitude</label>
                                <input type="text" class="form-control" id="longitude" name="longitude" 
                                       value="{{ old('longitude', $gedung->longitude) }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status_gedung" class="form-label">Status</label>
                                <select class="form-select" id="status_gedung" name="status_gedung" required>
                                    <option value="aktif" {{ $gedung->status_gedung == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="non_aktif" {{ $gedung->status_gedung == 'non_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status_kepemilikan" class="form-label">Status Kepemilikan</label>
                                <select class="form-select" id="status_kepemilikan" name="status_kepemilikan" required>
                                    <option value="milik" {{ $gedung->status_kepemilikan == 'milik' ? 'selected' : '' }}>Milik</option>
                                    <option value="sewa" {{ $gedung->status_kepemilikan == 'sewa' ? 'selected' : '' }}>Sewa</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="layout_gedung" class="form-label">Layout Gedung</label>
                        <input type="file" class="form-control" id="layout_gedung" name="layout_gedung" accept=".pdf,.jpg,.jpeg,.png,.svg">
                        <div class="form-text">Unggah layout baru untuk mengganti file sebelumnya (maks. 20 MB).</div>
                        @if($gedung->layout_url)
                            <div class="mt-2">
                                <a href="{{ $gedung->layout_url }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-file-image"></i> Lihat Layout Saat Ini
                                </a>
                            </div>
                        @endif
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Gedung
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
