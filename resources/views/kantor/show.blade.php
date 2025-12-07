@extends('layouts.app')

@section('title', 'Detail Kantor - PLN Icon Plus Kantor Management')
@section('page-title', 'Detail Kantor')
@section('page-subtitle', 'Informasi lengkap kantor: ' . $kantor->nama_kantor)

@section('page-actions')
    @php($actor = Auth::guard('admin')->user())
    @if(($actor && $actor->role === 'super_admin') || ($actor && in_array($actor->role, ['admin_regional','staf']) && (int)$actor->kantor_id === (int)$kantor->id))
    <a href="{{ route('kantor.edit', $kantor->id) }}" class="btn btn-modern btn-primary">
        <i class="fas fa-edit"></i> Edit
    </a>
    @endif
    <a href="{{ route('kantor.index') }}" class="btn btn-modern btn-clear">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
@endsection

@section('content')
<div class="container-fluid">
    @php($actor = Auth::guard('admin')->user())

    <div class="row">
        <div class="col-lg-8">
            <!-- Kantor Information -->
            <div class="detail-card">
                <div class="detail-header">
                    <h5 class="detail-title">
                        <i class="fas fa-building"></i>
                        Informasi Kantor
                    </h5>
                </div>
                <div class="detail-body">
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label class="detail-label">Kode Kantor</label>
                            <div class="detail-value">
                                <span class="badge modern-badge badge-kantor">{{ $kantor->kode_kantor }}</span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Nama Kantor</label>
                            <div class="detail-value">
                                <strong>{{ $kantor->nama_kantor }}</strong>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Jenis Kantor</label>
                            <div class="detail-value">
                                <span class="badge modern-badge badge-jenis">{{ $kantor->jenisKantor->nama_jenis }}</span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Status</label>
                            <div class="detail-value">
                                <span class="badge modern-badge {{ $kantor->status_kantor == 'aktif' ? 'badge-success' : 'badge-danger' }}">
                                    {{ ucfirst($kantor->status_kantor) }}
                                </span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Kota</label>
                            <div class="detail-value">{{ $kantor->kota->nama_kota }}</div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Provinsi</label>
                            <div class="detail-value">{{ $kantor->kota->provinsi->nama_provinsi }}</div>
                        </div>
                        @if($kantor->parentKantor)
                        <div class="detail-item">
                            <label class="detail-label">Parent Kantor</label>
                            <div class="detail-value">
                                <a href="{{ route('kantor.show', $kantor->parentKantor->id) }}" class="detail-link">
                                    {{ $kantor->parentKantor->nama_kantor }}
                                </a>
                            </div>
                        </div>
                        @endif
                        @if($kantor->latitude && $kantor->longitude)
                        <div class="detail-item">
                            <label class="detail-label">Koordinat</label>
                            <div class="detail-value detail-coordinate">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $kantor->latitude }}, {{ $kantor->longitude }}
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <div class="detail-section">
                        <label class="detail-label">Alamat Lengkap</label>
                        <div class="detail-value detail-address">
                            <i class="fas fa-location-dot"></i>
                            {{ $kantor->alamat }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Information -->
            <div class="detail-card">
                <div class="detail-header">
                    <h5 class="detail-title">
                        <i class="fas fa-clock"></i>
                        Informasi Aktivitas
                    </h5>
                </div>
                <div class="detail-body">
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label class="detail-label">Dibuat</label>
                            <div class="detail-value">
                                <div class="detail-time">
                                    <i class="fas fa-calendar-plus"></i>
                                    <div>
                                        <strong>{{ $kantor->created_at->format('d/m/Y H:i') }}</strong>
                                        <small>{{ $kantor->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label class="detail-label">Terakhir Diupdate</label>
                            <div class="detail-value">
                                <div class="detail-time">
                                    <i class="fas fa-calendar-check"></i>
                                    <div>
                                        <strong>{{ $kantor->updated_at->format('d/m/Y H:i') }}</strong>
                                        <small>{{ $kantor->updated_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="detail-card">
                <div class="detail-header">
                    <h5 class="detail-title">
                        <i class="fas fa-bolt"></i>
                        Aksi Cepat
                    </h5>
                </div>
                <div class="detail-body">
                    <div class="action-buttons-vertical">
                        @if(($actor && $actor->role === 'super_admin') || ($actor && in_array($actor->role, ['admin_regional','staf']) && (int)$actor->kantor_id === (int)$kantor->id))
                        <a href="{{ route('kantor.edit', $kantor->id) }}" class="btn btn-modern btn-primary">
                            <i class="fas fa-edit"></i> Edit Kantor
                        </a>
                        @endif
                        @if($actor && $actor->role === 'super_admin')
                        <form action="{{ route('kantor.destroy', $kantor->id) }}" method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus kantor ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-modern btn-danger w-100">
                                <i class="fas fa-trash"></i> Hapus Kantor
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Location Information -->
            <div class="detail-card">
                <div class="detail-header">
                    <h5 class="detail-title">
                        <i class="fas fa-map-marker-alt"></i>
                        Lokasi
                    </h5>
                </div>
                <div class="detail-body">
                    @if($kantor->latitude && $kantor->longitude)
                        <div id="map" class="detail-map"></div>
                    @else
                        <div class="detail-empty">
                            <i class="fas fa-info-circle"></i>
                            <p>Koordinat belum diisi</p>
                        </div>
                    @endif
                </div>
            </div>
            
            @if($kantor->childKantor->count() > 0)
            <div class="detail-card">
                <div class="detail-header">
                    <h5 class="detail-title">
                        <i class="fas fa-sitemap"></i>
                        Kantor Cabang
                    </h5>
                </div>
                <div class="detail-body">
                    <div class="child-kantor-list">
                        @foreach($kantor->childKantor as $child)
                        <div class="child-kantor-item">
                            <div class="child-kantor-info">
                                <strong>{{ $child->nama_kantor }}</strong>
                                <small>{{ $child->kode_kantor }}</small>
                            </div>
                            <a href="{{ route('kantor.show', $child->id) }}" class="btn btn-modern btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
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

    .detail-link {
        color: var(--pln-blue);
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s ease;
    }

    .detail-link:hover {
        color: var(--pln-blue-dark);
        text-decoration: underline;
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

    /* Detail Time */
    .detail-time {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .detail-time i {
        color: var(--pln-blue);
        margin-top: 0.25rem;
        flex-shrink: 0;
    }

    .detail-time strong {
        display: block;
        color: var(--text-dark);
        margin-bottom: 0.25rem;
    }

    .detail-time small {
        display: block;
        color: var(--text-gray);
        font-size: 0.8rem;
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

    .badge.badge-jenis {
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

    /* Action Buttons Vertical */
    .action-buttons-vertical {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
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

    .btn-modern.btn-danger {
        background: #dc3545;
        color: white;
        border: 1px solid #dc3545;
    }

    .btn-modern.btn-danger:hover {
        background: #c82333;
        border-color: #c82333;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.25);
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
        min-width: 2.25rem;
        height: 2.25rem;
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

    /* Child Kantor List */
    .child-kantor-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .child-kantor-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.875rem;
        background: var(--pln-blue-bg);
        border-radius: 8px;
        border: 1px solid var(--gray-border);
        transition: all 0.2s ease;
    }

    .child-kantor-item:hover {
        background: var(--pln-blue-lighter);
        border-color: var(--pln-blue);
        transform: translateX(4px);
    }

    .child-kantor-info {
        flex: 1;
    }

    .child-kantor-info strong {
        display: block;
        color: var(--pln-blue);
        font-size: 0.9375rem;
        margin-bottom: 0.25rem;
    }

    .child-kantor-info small {
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

