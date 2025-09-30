@extends('layouts.app')

@section('title', 'Edit Kontrak - PLN Icon Plus Kantor Management')
@section('page-title', 'Edit Kontrak')

@section('page-actions')
    <a href="{{ route('kontrak.show', $kontrak->id) }}" class="btn btn-outline-secondary">
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
                    Form Edit Kontrak
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

                <form action="{{ route('kontrak.update', $kontrak->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama_perjanjian" class="form-label">Nama Perjanjian</label>
                                <input type="text" class="form-control" id="nama_perjanjian" name="nama_perjanjian" 
                                       placeholder="Contoh: Sewa Gedung Menara Jamsostek" 
                                       value="{{ old('nama_perjanjian', $kontrak->nama_perjanjian) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kantor_id" class="form-label">Kantor</label>
                                <select class="form-select" id="kantor_id" name="kantor_id" required>
                                    <option value="">Pilih Kantor</option>
                                    @foreach($kantor as $k)
                                        <option value="{{ $k->id }}" {{ $kontrak->kantor_id == $k->id ? 'selected' : '' }}>
                                            {{ $k->kode_kantor }} - {{ $k->nama_kantor }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" 
                                       value="{{ old('tanggal_mulai', $kontrak->tanggal_mulai) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                                <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" 
                                       value="{{ old('tanggal_selesai', $kontrak->tanggal_selesai) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nilai_kontrak" class="form-label">Nilai Kontrak</label>
                                <input type="number" class="form-control" id="nilai_kontrak" name="nilai_kontrak" 
                                       value="{{ old('nilai_kontrak', $kontrak->nilai_kontrak) }}" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status_perjanjian" class="form-label">Status Perjanjian</label>
                                <select class="form-select" id="status_perjanjian" name="status_perjanjian" required>
                                    <option value="baru" {{ $kontrak->status_perjanjian == 'baru' ? 'selected' : '' }}>Baru</option>
                                    <option value="berjalan" {{ $kontrak->status_perjanjian == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                                    <option value="selesai" {{ $kontrak->status_perjanjian == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="no_perjanjian_pihak_1" class="form-label">No Perjanjian Pihak 1</label>
                                <input type="text" class="form-control" id="no_perjanjian_pihak_1" name="no_perjanjian_pihak_1" 
                                       value="{{ old('no_perjanjian_pihak_1', $kontrak->no_perjanjian_pihak_1) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="no_perjanjian_pihak_2" class="form-label">No Perjanjian Pihak 2</label>
                                <input type="text" class="form-control" id="no_perjanjian_pihak_2" name="no_perjanjian_pihak_2" 
                                       value="{{ old('no_perjanjian_pihak_2', $kontrak->no_perjanjian_pihak_2) }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="asset_owner" class="form-label">Asset Owner</label>
                                <input type="text" class="form-control" id="asset_owner" name="asset_owner" 
                                       value="{{ old('asset_owner', $kontrak->asset_owner) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="sbu" class="form-label">SBU</label>
                                <input type="text" class="form-control" id="sbu" name="sbu" 
                                       value="{{ old('sbu', $kontrak->sbu) }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="ruang_lingkup" class="form-label">Ruang Lingkup</label>
                                <textarea class="form-control" id="ruang_lingkup" name="ruang_lingkup" rows="3">{{ old('ruang_lingkup', $kontrak->ruang_lingkup) }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="peruntukan_kantor" class="form-label">Peruntukan Kantor</label>
                                <select class="form-select" id="peruntukan_kantor" name="peruntukan_kantor">
                                    <option value="">Pilih Peruntukan</option>
                                    <option value="Kantor SBU" {{ $kontrak->peruntukan_kantor == 'Kantor SBU' ? 'selected' : '' }}>Kantor SBU</option>
                                    <option value="Kantor KP" {{ $kontrak->peruntukan_kantor == 'Kantor KP' ? 'selected' : '' }}>Kantor KP</option>
                                    <option value="Gudang" {{ $kontrak->peruntukan_kantor == 'Gudang' ? 'selected' : '' }}>Gudang</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3">{{ old('alamat', $kontrak->alamat) }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="berita_acara" class="form-label">Berita Acara (PDF)</label>
                                <input type="file" class="form-control" id="berita_acara" name="berita_acara" accept=".pdf">
                                @if($kontrak->berita_acara)
                                    <small class="text-muted">File saat ini: {{ $kontrak->berita_acara }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="3">{{ old('keterangan', $kontrak->keterangan) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Kontrak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection