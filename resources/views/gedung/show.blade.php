@extends('layouts.app')

@section('title', 'Detail Gedung - PLN Icon Plus Kantor Management')
@section('page-title', 'Detail Gedung')
@section('page-subtitle', 'Informasi lengkap gedung: ' . $gedung->nama_gedung)

@section('page-actions')
    <a href="{{ route('gedung.edit', $gedung->id) }}" class="btn btn-modern btn-primary">
        <i class="fas fa-edit"></i> Edit
    </a>
    <a href="{{ route('gedung.index') }}" class="btn btn-modern btn-clear">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <!-- Informasi Gedung -->
            <div class="detail-card">
                <div class="detail-header">
                    <h5 class="detail-title">
                        <i class="fas fa-building"></i>
                        Informasi Gedung
                    </h5>
                </div>
                <div class="detail-body">
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label class="detail-label">Nama Gedung</label>
                            <div class="detail-value">
                                <strong>{{ $gedung->nama_gedung }}</strong>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Kantor</label>
                            <div class="detail-value">
                                <span class="badge modern-badge badge-kantor">{{ $gedung->kantor->kode_kantor }}</span>
                                <div class="mt-1">
                                    <small>{{ $gedung->kantor->nama_kantor }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Status</label>
                            <div class="detail-value">
                                <span class="badge modern-badge {{ $gedung->status_gedung == 'aktif' ? 'badge-success' : 'badge-danger' }}">
                                    {{ ucfirst($gedung->status_gedung) }}
                                </span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Status Kepemilikan</label>
                            <div class="detail-value">
                                <span class="badge modern-badge badge-kantor">{{ ucfirst($gedung->status_kepemilikan) }}</span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Kota</label>
                            <div class="detail-value">{{ $gedung->kantor->kota->nama_kota }}</div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Provinsi</label>
                            <div class="detail-value">{{ $gedung->kantor->kota->provinsi->nama_provinsi }}</div>
                        </div>
                        @if($gedung->latitude && $gedung->longitude)
                        <div class="detail-item">
                            <label class="detail-label">Koordinat</label>
                            <div class="detail-value detail-coordinate">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $gedung->latitude }}, {{ $gedung->longitude }}
                            </div>
                        </div>
                        @endif
                        <div class="detail-item">
                            <label class="detail-label">Total Lantai</label>
                            <div class="detail-value">
                                <span class="badge modern-badge badge-kantor">{{ $gedung->lantai->count() }} Lantai</span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Total Ruangan</label>
                            <div class="detail-value">
                                <span class="badge modern-badge badge-success">
                                    {{ $gedung->lantai->flatMap->ruang->count() }} Ruang
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="detail-section">
                        <label class="detail-label">Alamat Lengkap</label>
                        <div class="detail-value detail-address">
                            <i class="fas fa-location-dot"></i>
                            {{ $gedung->alamat }}
                        </div>
                    </div>

                    @if($gedung->layout_url)
                    <div class="detail-section">
                        <label class="detail-label">Layout Gedung</label>
                        <div class="detail-value">
                            <a href="{{ $gedung->layout_url }}" target="_blank" class="btn btn-modern btn-info btn-sm">
                                <i class="fas fa-file-download"></i> Lihat / Unduh Layout
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Lokasi -->
            <div class="detail-card">
                <div class="detail-header">
                    <h5 class="detail-title">
                        <i class="fas fa-map-marker-alt"></i>
                        Lokasi
                    </h5>
                </div>
                <div class="detail-body">
                    @if($gedung->latitude && $gedung->longitude)
                        <div id="map" class="detail-map"></div>
                    @else
                        <div class="detail-empty">
                            <i class="fas fa-info-circle"></i>
                            <p>Koordinat belum diisi</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Lantai -->
            @if($gedung->lantai->count() > 0)
            <div class="detail-card">
                <div class="detail-header">
                    <h5 class="detail-title">
                        <i class="fas fa-layer-group"></i>
                        Lantai
                    </h5>
                </div>
                <div class="detail-body">
                    <div class="lantai-list">
                        @foreach($gedung->lantai as $lantai)
                        <div class="lantai-item">
                            <div class="lantai-info">
                                <strong>Lantai {{ $lantai->nomor_lantai }}</strong>
                                <small>{{ $lantai->nama_lantai }}</small>
                            </div>
                            <span class="badge modern-badge badge-success">{{ $lantai->ruang->count() }} Ruang</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
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
    
    // Custom icon dengan PLN blue
    const plnIcon = L.divIcon({
        className: 'pln-marker',
        html: '<div style="background: #21618C; width: 30px; height: 30px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"></div>',
        iconSize: [30, 30],
        iconAnchor: [15, 30]
    });
    
    L.marker([lat, lng], { icon: plnIcon })
        .addTo(map)
        .bindPopup('<div style="font-weight: 600; color: #21618C; margin-bottom: 4px;">' + nama + '</div><div style="font-size: 0.875rem; color: #64748b;">' + alamat + '</div>')
        .openPopup();
});
</script>
@endif
@endpush

<style>
    :root {
        --pln-blue: #21618C;
        --pln-blue-dark: #1A4D73;
        --pln-blue-light: #2E86AB;
        --pln-blue-lighter: #E8F4F8;
        --pln-blue-bg: #F5FAFC;
        --white: #FFFFFF;
        --gray-light: #F8F9FA;
        --gray-border: #E0E0E0;
        --text-dark: #1A1A1A;
        --text-gray: #6C757D;
    }

    /* Detail Card */
    .detail-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(33, 97, 140, 0.1);
        border: 1px solid rgba(33, 97, 140, 0.15);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .detail-header {
        background: white;
        padding: 1.25rem 1.75rem;
        border-bottom: 2px solid var(--pln-blue);
    }

    .detail-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--pln-blue);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .detail-title i {
        color: var(--pln-blue);
        font-size: 1.25rem;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--pln-blue-lighter);
        border-radius: 8px;
    }

    .detail-body {
        padding: 1.75rem;
    }

    /* Detail Grid */
    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .detail-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-gray);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .detail-value {
        font-size: 0.9375rem;
        color: var(--text-dark);
        font-weight: 500;
    }

    .detail-value strong {
        color: var(--pln-blue);
        font-weight: 700;
    }

    .detail-coordinate {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-gray);
        font-family: 'Courier New', monospace;
        font-size: 0.875rem;
    }

    .detail-coordinate i {
        color: var(--pln-blue);
    }

    /* Detail Section */
    .detail-section {
        padding-top: 1.5rem;
        border-top: 1px solid var(--gray-border);
    }

    .detail-address {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        line-height: 1.6;
        color: var(--text-dark);
    }

    .detail-address i {
        color: var(--pln-blue);
        margin-top: 0.25rem;
        flex-shrink: 0;
    }

    /* Badges */
    .badge.modern-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8125rem;
        letter-spacing: 0.3px;
    }

    .badge.badge-kantor {
        background: var(--pln-blue-lighter);
        color: var(--pln-blue);
    }

    .badge.badge-success {
        background: #d4edda;
        color: #155724;
    }

    .badge.badge-danger {
        background: #f8d7da;
        color: #721c24;
    }

    /* Buttons */
    .btn-modern {
        border-radius: 10px;
        padding: 0.625rem 1.25rem;
        font-weight: 600;
        transition: all 0.2s ease;
        border: 1px solid transparent;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .btn-modern.btn-primary {
        background: var(--pln-blue);
        color: white;
        border: 1px solid var(--pln-blue);
    }

    .btn-modern.btn-primary:hover {
        background: var(--pln-blue-dark);
        border-color: var(--pln-blue-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(33, 97, 140, 0.25);
    }

    .btn-modern.btn-info {
        background: var(--pln-blue);
        color: white;
        border: 1px solid var(--pln-blue);
    }

    .btn-modern.btn-info:hover {
        background: var(--pln-blue-dark);
        border-color: var(--pln-blue-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(33, 97, 140, 0.25);
    }

    .btn-modern.btn-clear {
        background: white;
        color: #64748b;
        border: 1px solid #e2e8f0;
    }

    .btn-modern.btn-clear:hover {
        background: #f8f9fa;
        color: #475569;
        border-color: #cbd5e0;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    }

    .btn-modern.btn-sm {
        padding: 0.4rem 0.65rem;
        font-size: 0.75rem;
    }

    /* Map */
    .detail-map {
        height: 300px;
        width: 100%;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    /* Empty State */
    .detail-empty {
        text-align: center;
        padding: 2rem 1rem;
        color: var(--text-gray);
    }

    .detail-empty i {
        font-size: 2rem;
        color: var(--pln-blue-lighter);
        margin-bottom: 0.75rem;
    }

    .detail-empty p {
        margin: 0;
        font-size: 0.875rem;
    }

    /* Lantai List */
    .lantai-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .lantai-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.875rem;
        background: var(--pln-blue-bg);
        border-radius: 8px;
        border: 1px solid var(--gray-border);
        transition: all 0.2s ease;
    }

    .lantai-item:hover {
        background: var(--pln-blue-lighter);
        border-color: var(--pln-blue);
        transform: translateX(4px);
    }

    .lantai-info {
        flex: 1;
    }

    .lantai-info strong {
        display: block;
        color: var(--pln-blue);
        font-size: 0.9375rem;
        margin-bottom: 0.25rem;
    }

    .lantai-info small {
        display: block;
        color: var(--text-gray);
        font-size: 0.8rem;
    }

    /* Responsive */
    @media (max-width: 991px) {
        .detail-grid {
            grid-template-columns: 1fr;
            gap: 1.25rem;
        }

        .detail-body {
            padding: 1.25rem;
        }
    }
</style>
@endsection
