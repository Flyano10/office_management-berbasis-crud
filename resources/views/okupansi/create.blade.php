@extends('layouts.app')

@section('title', 'Tambah Okupansi - PLN Icon Plus Kantor Management')
@section('page-title', 'Tambah Okupansi Baru')

@section('page-actions')
    <a href="{{ route('okupansi.index') }}" class="btn btn-outline-secondary">
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
                    Form Tambah Okupansi
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

                <form action="{{ route('okupansi.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="ruang_id" class="form-label">Ruang</label>
                                <select class="form-select" id="ruang_id" name="ruang_id" required>
                                    <option value="">Pilih Ruang</option>
                                    @foreach($ruang as $r)
                                        <option value="{{ $r->id }}" {{ old('ruang_id') == $r->id ? 'selected' : '' }}>
                                            {{ $r->nama_ruang }} - Lantai {{ $r->lantai->nomor_lantai }} ({{ $r->lantai->gedung->nama_gedung }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tanggal_okupansi" class="form-label">Tanggal Okupansi</label>
                                <input type="date" class="form-control" id="tanggal_okupansi" name="tanggal_okupansi" 
                                       value="{{ old('tanggal_okupansi') }}" required>
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
                                        <option value="{{ $b->id }}" {{ old('bidang_id') == $b->id ? 'selected' : '' }}>
                                            {{ $b->nama_bidang }}
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
                                        <option value="{{ $sb->id }}" {{ old('sub_bidang_id') == $sb->id ? 'selected' : '' }}>
                                            {{ $sb->nama_sub_bidang }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="jml_pegawai_organik" class="form-label">Jumlah Pegawai Organik</label>
                                <input type="number" class="form-control" id="jml_pegawai_organik" name="jml_pegawai_organik" 
                                       placeholder="Contoh: 10, 20, 50, dll" min="0" 
                                       value="{{ old('jml_pegawai_organik') }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="jml_pegawai_tad" class="form-label">Jumlah Pegawai TAD</label>
                                <input type="number" class="form-control" id="jml_pegawai_tad" name="jml_pegawai_tad" 
                                       placeholder="Contoh: 5, 10, 15, dll" min="0" 
                                       value="{{ old('jml_pegawai_tad') }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="jml_pegawai_kontrak" class="form-label">Jumlah Pegawai Kontrak</label>
                                <input type="number" class="form-control" id="jml_pegawai_kontrak" name="jml_pegawai_kontrak" 
                                       placeholder="Contoh: 3, 8, 12, dll" min="0" 
                                       value="{{ old('jml_pegawai_kontrak') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="3" 
                                          placeholder="Masukkan keterangan tambahan">{{ old('keterangan') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Okupansi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
