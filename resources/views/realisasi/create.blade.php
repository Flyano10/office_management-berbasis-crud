@extends('layouts.app')

@section('title', 'Tambah Realisasi - PLN Icon Plus Kantor Management')
@section('page-title', 'Tambah Realisasi Baru')

@section('page-actions')
    <a href="{{ route('realisasi.index') }}" class="btn btn-outline-secondary">
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
                    Form Tambah Realisasi
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

                <form action="{{ route('realisasi.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Data Kontrak (Auto-fill) -->
                    <div class="card mb-4">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0"><i class="fas fa-file-contract"></i> Data Kontrak (Auto-fill)</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="kontrak_id" class="form-label">Pilih Kontrak</label>
                                        <select class="form-select" id="kontrak_id" name="kontrak_id" required onchange="loadKontrakData()">
                                            <option value="">Pilih Kontrak</option>
                                            @foreach($kontrak as $k)
                                                <option value="{{ $k->id }}" 
                                                        data-no-pihak-1="{{ $k->no_perjanjian_pihak_1 }}"
                                                        data-no-pihak-2="{{ $k->no_perjanjian_pihak_2 }}"
                                                        data-tanggal-mulai="{{ $k->tanggal_mulai }}"
                                                        data-tanggal-selesai="{{ $k->tanggal_selesai }}"
                                                        {{ old('kontrak_id') == $k->id ? 'selected' : '' }}>
                                                    {{ $k->nama_perjanjian }} - {{ $k->no_perjanjian_pihak_1 }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row" id="kontrak-data" style="display: none;">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">No Perjanjian Pihak I</label>
                                        <input type="text" class="form-control" id="no_perjanjian_pihak_1" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">No Perjanjian Pihak II</label>
                                        <input type="text" class="form-control" id="no_perjanjian_pihak_2" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Mulai</label>
                                        <input type="date" class="form-control" id="tanggal_mulai" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Selesai</label>
                                        <input type="date" class="form-control" id="tanggal_selesai" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Data Realisasi (Input Manual) -->
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0"><i class="fas fa-edit"></i> Data Realisasi (Input Manual)</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tanggal_realisasi" class="form-label">Tanggal Realisasi</label>
                                <input type="date" class="form-control" id="tanggal_realisasi" name="tanggal_realisasi" 
                                       value="{{ old('tanggal_realisasi') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kompensasi" class="form-label">Kompensasi</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="kompensasi" id="pemeliharaan" 
                                               value="Pemeliharaan" {{ old('kompensasi') == 'Pemeliharaan' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="pemeliharaan">
                                            Pemeliharaan
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="kompensasi" id="pembangunan" 
                                               value="Pembangunan" {{ old('kompensasi') == 'Pembangunan' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="pembangunan">
                                            Pembangunan
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="rp_kompensasi" class="form-label">Rp. Kompensasi</label>
                                <input type="number" class="form-control" id="rp_kompensasi" name="rp_kompensasi" 
                                       placeholder="Contoh: 1000000" min="0" step="1000" value="{{ old('rp_kompensasi') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" 
                                          placeholder="Deskripsikan detail realisasi" required>{{ old('deskripsi') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="lokasi_kantor" class="form-label">Lokasi Kantor</label>
                                <select class="form-select" id="lokasi_kantor" name="lokasi_kantor">
                                    <option value="">Pilih Lokasi</option>
                                    <option value="UIW" {{ old('lokasi_kantor') == 'UIW' ? 'selected' : '' }}>UIW</option>
                                    <option value="UID" {{ old('lokasi_kantor') == 'UID' ? 'selected' : '' }}>UID</option>
                                    <option value="UIP" {{ old('lokasi_kantor') == 'UIP' ? 'selected' : '' }}>UIP</option>
                                    <option value="UIT" {{ old('lokasi_kantor') == 'UIT' ? 'selected' : '' }}>UIT</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="upload_berita_acara" class="form-label">Upload Berita Acara</label>
                                <input type="file" class="form-control" id="upload_berita_acara" name="upload_berita_acara" 
                                       accept=".pdf,.doc,.docx">
                                <div class="form-text">Format: PDF, DOC, DOCX (Max: 2MB)</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3" 
                                          placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Realisasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function loadKontrakData() {
    const select = document.getElementById('kontrak_id');
    const kontrakData = document.getElementById('kontrak-data');
    const selectedOption = select.options[select.selectedIndex];
    
    if (select.value) {
        // Tampilkan data kontrak
        kontrakData.style.display = 'block';
        
        // Isi data kontrak
        document.getElementById('no_perjanjian_pihak_1').value = selectedOption.getAttribute('data-no-pihak-1');
        document.getElementById('no_perjanjian_pihak_2').value = selectedOption.getAttribute('data-no-pihak-2');
        document.getElementById('tanggal_mulai').value = selectedOption.getAttribute('data-tanggal-mulai');
        document.getElementById('tanggal_selesai').value = selectedOption.getAttribute('data-tanggal-selesai');
    } else {
        // Sembunyikan data kontrak
        kontrakData.style.display = 'none';
    }
}

// Load data kontrak jika ada old value
document.addEventListener('DOMContentLoaded', function() {
    const kontrakId = '{{ old("kontrak_id") }}';
    if (kontrakId) {
        loadKontrakData();
    }
});
</script>
@endpush
