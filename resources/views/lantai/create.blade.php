@extends('layouts.app')

@section('title', 'Tambah Lantai - PLN Icon Plus Kantor Management')
@section('page-title', 'Tambah Lantai Baru')

@section('page-actions')
    <a href="{{ route('lantai.index') }}" class="btn btn-outline-secondary">
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
                    Form Tambah Lantai
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

                <form action="{{ route('lantai.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama_lantai" class="form-label">Nama Lantai</label>
                                <input type="text" class="form-control" id="nama_lantai" name="nama_lantai" 
                                       placeholder="Contoh: Lantai 1, Lantai 2, dll" 
                                       value="{{ old('nama_lantai') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nomor_lantai" class="form-label">Nomor Lantai</label>
                                <input type="number" class="form-control" id="nomor_lantai" name="nomor_lantai" 
                                       placeholder="Contoh: 1, 2, 3, dll" min="1" 
                                       value="{{ old('nomor_lantai') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="gedung_id" class="form-label">Gedung</label>
                                <select class="form-select" id="gedung_id" name="gedung_id" required>
                                    <option value="">Pilih Gedung</option>
                                    @foreach($gedung as $g)
                                        <option value="{{ $g->id }}" {{ old('gedung_id') == $g->id ? 'selected' : '' }}>
                                            {{ $g->nama_gedung }} - {{ $g->kantor->nama_kantor ?? 'N/A' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Lantai
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
