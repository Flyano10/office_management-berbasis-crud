@extends('layouts.app')

@section('title', 'Tambah Sub Bidang - PLN Icon Plus Kantor Management')
@section('page-title', 'Tambah Sub Bidang Baru')

@section('page-actions')
    <a href="{{ route('sub-bidang.index') }}" class="btn btn-outline-secondary">
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
                    Form Tambah Sub Bidang
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

                <form action="{{ route('sub-bidang.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="nama_sub_bidang" class="form-label">Nama Sub Bidang</label>
                                <input type="text" class="form-control" id="nama_sub_bidang" name="nama_sub_bidang" 
                                       placeholder="Contoh: Sistem Informasi, Rekrutmen, dll" 
                                       value="{{ old('nama_sub_bidang') }}" required>
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
                                            {{ $b->kode_bidang }} - {{ $b->nama_bidang }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" 
                                          placeholder="Masukkan deskripsi sub bidang">{{ old('deskripsi') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Sub Bidang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
