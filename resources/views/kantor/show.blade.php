@extends('layouts.app')

@section('title', 'Detail Kantor - PLN Icon Plus Kantor Management')
@section('page-title', 'Detail Kantor')

@section('page-actions')
    <a href="{{ route('kantor.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
    <a href="{{ route('kantor.edit', $kantor->id) }}" class="btn btn-warning">
        <i class="fas fa-edit"></i> Edit
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-building"></i>
                    Informasi Kantor
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Kode Kantor:</th>
                                <td><span class="badge bg-primary">{{ $kantor->kode_kantor }}</span></td>
                            </tr>
                            <tr>
                                <th>Nama Kantor:</th>
                                <td><strong>{{ $kantor->nama_kantor }}</strong></td>
                            </tr>
                            <tr>
                                <th>Jenis Kantor:</th>
                                <td><span class="badge bg-info">{{ $kantor->jenisKantor->nama_jenis }}</span></td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    <span class="badge bg-{{ $kantor->status_kantor == 'aktif' ? 'success' : 'danger' }}">
                                        {{ ucfirst($kantor->status_kantor) }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Kota:</th>
                                <td>{{ $kantor->kota->nama_kota }}</td>
                            </tr>
                            <tr>
                                <th>Provinsi:</th>
                                <td>{{ $kantor->kota->provinsi->nama_provinsi }}</td>
                            </tr>
                            @if($kantor->parentKantor)
                            <tr>
                                <th>Parent Kantor:</th>
                                <td>{{ $kantor->parentKantor->nama_kantor }}</td>
                            </tr>
                            @endif
                            @if($kantor->latitude && $kantor->longitude)
                            <tr>
                                <th>Koordinat:</th>
                                <td>{{ $kantor->latitude }}, {{ $kantor->longitude }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
                
                <div class="mt-3">
                    <h6>Alamat Lengkap:</h6>
                    <p class="text-muted">{{ $kantor->alamat }}</p>
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
                @if($kantor->latitude && $kantor->longitude)
                    <div id="map" style="height: 300px; width: 100%;"></div>
                @else
                    <p class="text-muted text-center">
                        <i class="fas fa-info-circle"></i><br>
                        Koordinat belum diisi
                    </p>
                @endif
            </div>
        </div>
        
        @if($kantor->childKantor->count() > 0)
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-sitemap"></i>
                    Kantor Cabang
                </h5>
            </div>
            <div class="card-body">
                @foreach($kantor->childKantor as $child)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <strong>{{ $child->nama_kantor }}</strong><br>
                        <small class="text-muted">{{ $child->kode_kantor }}</small>
                    </div>
                    <a href="{{ route('kantor.show', $child->id) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
@if($kantor->latitude && $kantor->longitude)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const lat = parseFloat('{{ $kantor->latitude }}');
    const lng = parseFloat('{{ $kantor->longitude }}');
    const nama = '{{ addslashes($kantor->nama_kantor) }}';
    const alamat = '{{ addslashes($kantor->alamat) }}';
    
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
