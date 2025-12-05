@extends('layouts.app')

@section('title', 'Detail Kantor - PLN Icon Plus Kantor Management')
@section('page-title', 'Detail Kantor')

@section('page-actions')
    <a href="{{ route('kantor.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
    @php($actor = Auth::guard('admin')->user())
    @if(($actor && $actor->role === 'super_admin') || ($actor && in_array($actor->role, ['admin_regional','staf']) && (int)$actor->kantor_id === (int)$kantor->id))
    <a href="{{ route('kantor.edit', $kantor->id) }}" class="btn btn-outline-primary">
        <i class="fas fa-edit me-2"></i>Edit
    </a>
    @endif
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    @php($actor = Auth::guard('admin')->user())
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Detail Kantor</h2>
            <p class="text-muted mb-0">Informasi lengkap kantor: {{ $kantor->nama_kantor }}</p>
        </div>
        <div class="d-flex gap-2">
            @if(($actor && $actor->role === 'super_admin') || ($actor && in_array($actor->role, ['admin_regional','staf']) && (int)$actor->kantor_id === (int)$kantor->id))
            <a href="{{ route('kantor.edit', $kantor->id) }}" class="btn btn-outline-primary">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
            @endif
            <a href="{{ route('kantor.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Kantor Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-building me-2"></i>Informasi Kantor
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Kode Kantor</label>
                                <p class="h6 mb-0"><span class="badge bg-light text-dark">{{ $kantor->kode_kantor }}</span></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Nama Kantor</label>
                                <p class="h6 mb-0">{{ $kantor->nama_kantor }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Jenis Kantor</label>
                                <p class="mb-0"><span class="badge bg-light text-dark">{{ $kantor->jenisKantor->nama_jenis }}</span></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Status</label>
                                <p class="mb-0"><span class="badge bg-light text-dark">{{ ucfirst($kantor->status_kantor) }}</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Kota</label>
                                <p class="mb-0">{{ $kantor->kota->nama_kota }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Provinsi</label>
                                <p class="mb-0">{{ $kantor->kota->provinsi->nama_provinsi }}</p>
                            </div>
                        </div>
                    </div>
                    @if($kantor->parentKantor)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Parent Kantor</label>
                                <p class="mb-0">{{ $kantor->parentKantor->nama_kantor }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if($kantor->latitude && $kantor->longitude)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Koordinat</label>
                                <p class="mb-0">{{ $kantor->latitude }}, {{ $kantor->longitude }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="mt-3">
                        <h6 class="text-muted">Alamat Lengkap</h6>
                        <p class="text-muted">{{ $kantor->alamat }}</p>
                    </div>
                </div>
            </div>

            <!-- Activity Information -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock me-2"></i>Informasi Aktivitas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Dibuat</label>
                                <p class="mb-0">{{ $kantor->created_at->format('d/m/Y H:i') }}</p>
                                <small class="text-muted">{{ $kantor->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Terakhir Diupdate</label>
                                <p class="mb-0">{{ $kantor->updated_at->format('d/m/Y H:i') }}</p>
                                <small class="text-muted">{{ $kantor->updated_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>Aksi Cepat
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if(($actor && $actor->role === 'super_admin') || ($actor && in_array($actor->role, ['admin_regional','staf']) && (int)$actor->kantor_id === (int)$kantor->id))
                        <a href="{{ route('kantor.edit', $kantor->id) }}" class="btn btn-light btn-icon">
                            <i class="fas fa-edit me-2"></i>Edit Kantor
                        </a>
                        @endif
                        @if($actor && $actor->role === 'super_admin')
                        <form action="{{ route('kantor.destroy', $kantor->id) }}" method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus kantor ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-light btn-icon w-100">
                                <i class="fas fa-trash me-2"></i>Hapus Kantor
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Location Information -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-map-marker-alt me-2"></i>Lokasi
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
                        <i class="fas fa-sitemap me-2"></i>Kantor Cabang
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($kantor->childKantor as $child)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <strong>{{ $child->nama_kantor }}</strong><br>
                            <small class="text-muted">{{ $child->kode_kantor }}</small>
                        </div>
                        <a href="{{ route('kantor.show', $child->id) }}" class="btn btn-sm btn-light btn-icon">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
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
