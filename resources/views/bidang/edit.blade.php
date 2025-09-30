@extends('layouts.app')

@section('title', 'Edit Bidang - PLN Icon Plus Kantor Management')
@section('page-title', 'Edit Bidang')

@section('page-actions')
    <a href="{{ route('bidang.index') }}" class="btn btn-outline-secondary">
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
                    Form Edit Bidang
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

                <form action="{{ route('bidang.update', $bidang->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="nama_bidang" class="form-label">Nama Bidang</label>
                                <input type="text" class="form-control" id="nama_bidang" name="nama_bidang" 
                                       placeholder="Contoh: Information Technology, Human Resources, dll" 
                                       value="{{ old('nama_bidang', $bidang->nama_bidang) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" 
                                          placeholder="Masukkan deskripsi bidang">{{ old('deskripsi', $bidang->deskripsi) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Bidang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
