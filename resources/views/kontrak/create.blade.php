@extends('layouts.app')

@section('title', 'Tambah Kontrak - PLN Icon Plus Kantor Management')
@section('page-title', 'Tambah Kontrak Baru')

@section('page-actions')
    <a href="{{ route('kontrak.index') }}" class="btn btn-outline-secondary">
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
                    Form Tambah Kontrak
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

                <form action="{{ route('kontrak.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama_perjanjian" class="form-label">Nama Perjanjian</label>
                                <input type="text" class="form-control" id="nama_perjanjian" name="nama_perjanjian" 
                                       placeholder="Contoh: Sewa Gedung Menara Jamsostek" value="{{ old('nama_perjanjian') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kantor_id" class="form-label">Kantor</label>
                                <select class="form-select" id="kantor_id" name="kantor_id" required>
                                    <option value="">Pilih Kantor</option>
                                    @foreach($kantor as $k)
                                        <option value="{{ $k->id }}" {{ old('kantor_id') == $k->id ? 'selected' : '' }}>{{ $k->kode_kantor }} - {{ $k->nama_kantor }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                                <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nilai_kontrak" class="form-label">Nilai Kontrak</label>
                                <input type="number" class="form-control" id="nilai_kontrak" name="nilai_kontrak" 
                                       placeholder="Contoh: 500000000" value="{{ old('nilai_kontrak') }}" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status_perjanjian" class="form-label">Status Perjanjian</label>
                                <select class="form-select" id="status_perjanjian" name="status_perjanjian" required>
                                    <option value="Baru" {{ old('status_perjanjian', 'Baru') == 'Baru' ? 'selected' : '' }}>Baru</option>
                                    <option value="Amandemen" {{ old('status_perjanjian') == 'Amandemen' ? 'selected' : '' }}>Amandemen</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="Aktif" {{ old('status', 'Aktif') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Tidak Aktif" {{ old('status') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                    <option value="Batal" {{ old('status') == 'Batal' ? 'selected' : '' }}>Batal</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="no_perjanjian_pihak_1" class="form-label">No Perjanjian Pihak 1</label>
                                <input type="text" class="form-control" id="no_perjanjian_pihak_1" name="no_perjanjian_pihak_1" 
                                       placeholder="Contoh: ICONNET-2025-01" value="{{ old('no_perjanjian_pihak_1') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="no_perjanjian_pihak_2" class="form-label">No Perjanjian Pihak 2</label>
                                <input type="text" class="form-control" id="no_perjanjian_pihak_2" name="no_perjanjian_pihak_2" 
                                       placeholder="Contoh: PLN-2025-01" value="{{ old('no_perjanjian_pihak_2') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="asset_owner" class="form-label">Asset Owner</label>
                                <input type="text" class="form-control" id="asset_owner" name="asset_owner" 
                                       placeholder="Contoh: PT PLN Icon Plus" value="{{ old('asset_owner') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="sbu" class="form-label" id="sbu_label">Parent Kantor</label>
                                <div class="input-group">
                                    <select class="form-select" id="sbu_type" name="sbu_type" style="max-width: 120px;">
                                        <option value="">Pilih</option>
                                        <option value="Pusat" {{ old('sbu_type') == 'Pusat' ? 'selected' : '' }}>Pusat</option>
                                        <option value="SBU" {{ old('sbu_type') == 'SBU' ? 'selected' : '' }}>SBU</option>
                                        <option value="Perwakilan" {{ old('sbu_type') == 'Perwakilan' ? 'selected' : '' }}>Perwakilan</option>
                                        <option value="Gudang" {{ old('sbu_type') == 'Gudang' ? 'selected' : '' }}>Gudang</option>
                                    </select>
                                    <input type="text" class="form-control" id="sbu" name="sbu" 
                                           placeholder="Contoh: SBU Jakarta" value="{{ old('sbu') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="ruang_lingkup" class="form-label">Ruang Lingkup</label>
                                <textarea class="form-control" id="ruang_lingkup" name="ruang_lingkup" rows="3" 
                                          placeholder="Deskripsikan ruang lingkup perjanjian">{{ old('ruang_lingkup') }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="peruntukan_kantor" class="form-label">Peruntukan Kantor</label>
                                <select class="form-select" id="peruntukan_kantor" name="peruntukan_kantor">
                                    <option value="">Pilih Peruntukan</option>
                                    <option value="Kantor SBU" {{ old('peruntukan_kantor') == 'Kantor SBU' ? 'selected' : '' }}>Kantor SBU</option>
                                    <option value="Kantor KP" {{ old('peruntukan_kantor') == 'Kantor KP' ? 'selected' : '' }}>Kantor KP</option>
                                    <option value="Gudang" {{ old('peruntukan_kantor') == 'Gudang' ? 'selected' : '' }}>Gudang</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3" 
                                  placeholder="Masukkan alamat lengkap" required>{{ old('alamat') }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="berita_acara" class="form-label">Berita Acara (PDF)</label>
                                <input type="file" class="form-control" id="berita_acara" name="berita_acara" accept=".pdf">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="3" 
                                          placeholder="Masukkan keterangan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Kontrak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sbuTypeSelect = document.getElementById('sbu_type');
    const sbuInput = document.getElementById('sbu');

    sbuTypeSelect.addEventListener('change', function() {
        const selectedValue = this.value;
        
        if (selectedValue && selectedValue !== '') {
            // Update placeholder berdasarkan pilihan
            switch(selectedValue) {
                case 'Pusat':
                    sbuInput.placeholder = 'Contoh: Pusat Jakarta Selatan';
                    break;
                case 'SBU':
                    sbuInput.placeholder = 'Contoh: SBU Jakarta';
                    break;
                case 'Perwakilan':
                    sbuInput.placeholder = 'Contoh: Perwakilan Jakarta';
                    break;
                case 'Gudang':
                    sbuInput.placeholder = 'Contoh: Gudang Jakarta';
                    break;
            }
            
            // Jika input kosong, isi dengan pilihan yang dipilih
            if (sbuInput.value === '') {
                sbuInput.value = selectedValue + ' ';
                sbuInput.focus();
                // Pindahkan cursor ke akhir
                sbuInput.setSelectionRange(sbuInput.value.length, sbuInput.value.length);
            }
        } else {
            sbuInput.placeholder = 'Contoh: SBU Jakarta';
        }
    });
});
</script>
@endsection