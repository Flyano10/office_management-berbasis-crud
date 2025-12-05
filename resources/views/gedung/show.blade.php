@extends('layouts.app')

@section('title', 'Detail Gedung - PLN Icon Plus Kantor Management')
@section('page-title', 'Detail Gedung')

@section('page-actions')
    <a href="{{ route('gedung.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
    <a href="{{ route('gedung.edit', $gedung->id) }}" class="btn btn-warning">
        <i class="fas fa-edit"></i> Edit
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-sitemap"></i>
                    Struktur Gedung
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="mb-2">Status Kepemilikan</h6>
                    <span class="badge bg-info">{{ ucfirst($gedung->status_kepemilikan) }}</span>
                </div>
                <div class="mb-3">
                    <h6 class="mb-2">Total Lantai</h6>
                    <span class="badge bg-primary">{{ $gedung->lantai->count() }} Lantai</span>
                </div>
                <div class="mb-3">
                    <h6 class="mb-2">Total Ruangan</h6>
                    <span class="badge bg-success">
                        {{ $gedung->lantai->flatMap->ruang->count() }} Ruang
                    </span>
                </div>
                @if($gedung->layout_url)
                    <div class="mt-3">
                        <h6 class="mb-2">Layout Gedung</h6>
                        <a href="{{ $gedung->layout_url }}" target="_blank" class="btn btn-outline-primary btn-sm w-100">
                            <i class="fas fa-file-download me-2"></i>Lihat / Unduh Layout
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-building"></i>
                    Informasi Gedung
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Nama Gedung:</th>
                                <td><strong>{{ $gedung->nama_gedung }}</strong></td>
                            </tr>
                            <tr>
                                <th>Kantor:</th>
                                <td>
                                    <span class="badge bg-primary">{{ $gedung->kantor->kode_kantor }}</span><br>
                                    <small>{{ $gedung->kantor->nama_kantor }}</small>
                                </td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    <span class="badge bg-{{ $gedung->status_gedung == 'aktif' ? 'success' : 'danger' }}">
                                        {{ ucfirst($gedung->status_gedung) }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Kota:</th>
                                <td>{{ $gedung->kantor->kota->nama_kota }}</td>
                            </tr>
                            <tr>
                                <th>Provinsi:</th>
                                <td>{{ $gedung->kantor->kota->provinsi->nama_provinsi }}</td>
                            </tr>
                            @if($gedung->latitude && $gedung->longitude)
                            <tr>
                                <th>Koordinat:</th>
                                <td>{{ $gedung->latitude }}, {{ $gedung->longitude }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
                
                <div class="mt-3">
                    <h6>Alamat Lengkap:</h6>
                    <p class="text-muted">{{ $gedung->alamat }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-map-marker-alt"></i>
                    Lokasi
                </h5>
            </div>
            <div class="card-body">
                @if($gedung->latitude && $gedung->longitude)
                    <div id="map" style="height: 300px; width: 100%;"></div>
                @else
                    <p class="text-muted text-center">
                        <i class="fas fa-info-circle"></i><br>
                        Koordinat belum diisi
                    </p>
                @endif
            </div>
        </div>
        
        @if($gedung->lantai->count() > 0)
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-layer-group"></i>
                    Lantai
                </h5>
            </div>
            <div class="card-body">
                @foreach($gedung->lantai as $lantai)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <strong>Lantai {{ $lantai->nomor_lantai }}</strong><br>
                        <small class="text-muted">{{ $lantai->nama_lantai }}</small>
                    </div>
                    <span class="badge bg-info">{{ $lantai->ruang->count() }} Ruang</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
@if($gedung->latitude && $gedung->longitude)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const lat = parseFloat('{{ $gedung->latitude }}');
    const lng = parseFloat('{{ $gedung->longitude }}');
    const nama = '{{ addslashes($gedung->nama_gedung) }}';
    const alamat = '{{ addslashes($gedung->alamat) }}';
    
    const map = L.map('map').setView([lat, lng], 15);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    L.marker([lat, lng])
        .addTo(map)
        .bindPopup('<strong>' + nama + '</strong><br>' + alamat)
        .openPopup();
});
</script>
@endif
@endpush
@endsection
