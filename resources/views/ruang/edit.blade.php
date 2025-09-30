@extends('layouts.app')

@section('title', 'Edit Ruang - PLN Icon Plus Kantor Management')
@section('page-title', 'Edit Ruang')

@section('page-actions')
    <a href="{{ route('ruang.index') }}" class="btn btn-outline-secondary">
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
                    Form Edit Ruang
                </h5>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <h6>Error Validation:</h6>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('ruang.update', $ruang->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama_ruang" class="form-label">Nama Ruang</label>
                                <input type="text" class="form-control" id="nama_ruang" name="nama_ruang" 
                                       placeholder="Contoh: Ruang Meeting, Ruang Kerja" 
                                       value="{{ old('nama_ruang', $ruang->nama_ruang) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kapasitas" class="form-label">Kapasitas</label>
                                <input type="number" class="form-control" id="kapasitas" name="kapasitas" 
                                       placeholder="Contoh: 10, 20, 50" 
                                       value="{{ old('kapasitas', $ruang->kapasitas) }}" min="1" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="lantai_id" class="form-label">Lantai</label>
                                <select class="form-select" id="lantai_id" name="lantai_id" required>
                                    <option value="">Pilih Lantai</option>
                                    @foreach($lantai as $l)
                                        <option value="{{ $l->id }}" {{ old('lantai_id', $ruang->lantai_id) == $l->id ? 'selected' : '' }}>
                                            Lantai {{ $l->nomor_lantai }} - {{ $l->gedung->nama_gedung }} ({{ $l->gedung->kantor->nama_kantor ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status_ruang" class="form-label">Status Ruang</label>
                                <select class="form-select" id="status_ruang" name="status_ruang" required>
                                    <option value="">Pilih Status</option>
                                    <option value="tersedia" {{ old('status_ruang', $ruang->status_ruang) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                    <option value="terisi" {{ old('status_ruang', $ruang->status_ruang) == 'terisi' ? 'selected' : '' }}>Terisi</option>
                                    <option value="perbaikan" {{ old('status_ruang', $ruang->status_ruang) == 'perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="bidang_id" class="form-label">Bidang</label>
                                <select class="form-select" id="bidang_id" name="bidang_id" required>
                                    <option value="">Pilih Bidang</option>
                                    @foreach($bidang as $b)
                                        <option value="{{ $b->id }}" {{ old('bidang_id', $ruang->bidang_id) == $b->id ? 'selected' : '' }}>
                                            {{ $b->kode_bidang }} - {{ $b->nama_bidang }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="sub_bidang_id" class="form-label">Sub Bidang</label>
                                <select class="form-select" id="sub_bidang_id" name="sub_bidang_id">
                                    <option value="">Pilih Sub Bidang (Opsional)</option>
                                    @foreach($subBidang as $sb)
                                        <option value="{{ $sb->id }}" {{ old('sub_bidang_id', $ruang->sub_bidang_id) == $sb->id ? 'selected' : '' }}>
                                            {{ $sb->kode_sub_bidang }} - {{ $sb->nama_sub_bidang }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Ruang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
