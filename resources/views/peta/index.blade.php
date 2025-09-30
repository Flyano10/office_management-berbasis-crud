@extends('layouts.app')

@section('title', 'Peta Lokasi - PLN Icon Plus Kantor Management')
@section('page-title', 'Peta Lokasi Kantor & Gedung')

@section('page-actions')
    <div class="map-controls">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-modern btn-kantor" onclick="toggleLayer('kantor')">
                <i class="fas fa-building"></i> Kantor
            </button>
            <button type="button" class="btn btn-modern btn-gedung" onclick="toggleLayer('gedung')">
                <i class="fas fa-home"></i> Gedung
            </button>
            <button type="button" class="btn btn-modern btn-all" onclick="showAllLocations()">
                <i class="fas fa-eye"></i> Tampilkan Semua
            </button>
            <button type="button" class="btn btn-modern btn-fit" onclick="fitToBounds()">
                <i class="fas fa-expand-arrows-alt"></i> Fit to Bounds
            </button>
            <button type="button" class="btn btn-modern btn-fullscreen" onclick="toggleFullscreen()">
                <i class="fas fa-expand"></i> Fullscreen
            </button>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Map Section -->
    <div class="row">
        <div class="col-12">
            <div class="map-card">
                <div class="map-header">
                    <div class="map-title-section">
                        <h5 class="map-title">
                            <i class="fas fa-map-marked-alt"></i>
                            Peta Lokasi Kantor & Gedung PLN Icon Plus
                        </h5>
                    </div>
                    <div class="map-search-section">
                        <div class="search-container">
                            <input type="text" class="search-input" id="searchInput" placeholder="Cari lokasi...">
                            <button class="search-btn" type="button" onclick="searchLocations()">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="map-body">
                    <!-- Map Container -->
                    <div id="map" 
                         class="map-container" 
                         data-kantor="{{ json_encode($kantorData ?? []) }}"
                         data-gedung="{{ json_encode($gedungData ?? []) }}">
                        <div class="map-loading">
                            <i class="fas fa-spinner"></i>
                            Memuat peta...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Panel -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="filter-card">
                <div class="filter-header">
                    <h6 class="filter-title">
                        <i class="fas fa-filter"></i>
                        Filter Lokasi
                    </h6>
                </div>
                <div class="filter-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Status Kantor</label>
                            <select class="form-select modern-select" id="statusKantorFilter" onchange="applyFilters()">
                                <option value="">Semua Status</option>
                                <option value="aktif">Aktif</option>
                                <option value="tidak_aktif">Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Jenis Kantor</label>
                            <select class="form-select modern-select" id="jenisKantorFilter" onchange="applyFilters()">
                                <option value="">Semua Jenis</option>
                                <option value="Pusat">Pusat</option>
                                <option value="SBU">SBU</option>
                                <option value="KP">KP</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Kota</label>
                            <select class="form-select modern-select" id="kotaFilter" onchange="applyFilters()">
                                <option value="">Semua Kota</option>
                                @foreach($kantorData ?? [] as $kantor)
                                    @if($kantor->kota)
                                        <option value="{{ $kantor->kota->nama_kota }}">{{ $kantor->kota->nama_kota }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Aksi</label>
                            <div class="d-grid">
                                <button class="btn btn-modern btn-clear" onclick="clearFilters()">
                                    <i class="fas fa-times"></i> Clear Filters
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="stat-card stat-kantor">
                <div class="stat-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stat-content">
                    <h5 class="stat-number">{{ $totalKantor ?? 0 }}</h5>
                    <p class="stat-label">Total Kantor</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card stat-gedung">
                <div class="stat-icon">
                    <i class="fas fa-home"></i>
                </div>
                <div class="stat-content">
                    <h5 class="stat-number">{{ $totalGedung ?? 0 }}</h5>
                    <p class="stat-label">Total Gedung</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card stat-kontrak">
                <div class="stat-icon">
                    <i class="fas fa-file-contract"></i>
                </div>
                <div class="stat-content">
                    <h5 class="stat-number">{{ $totalKontrak ?? 0 }}</h5>
                    <p class="stat-label">Total Kontrak</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card stat-pegawai">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h5 class="stat-number">{{ $totalPegawai ?? 0 }}</h5>
                    <p class="stat-label">Total Pegawai</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Location List -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="location-card">
                <div class="location-header">
                    <h5 class="location-title">
                        <i class="fas fa-list"></i>
                        Daftar Lokasi
                    </h5>
                </div>
                <div class="location-body">
                    <div class="table-responsive">
                        <table class="table modern-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Jenis</th>
                                    <th>Alamat</th>
                                    <th>Kota</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($locations ?? [] as $location)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $location['nama'] }}</strong>
                                        @if(isset($location['kode']))
                                            <br><small class="text-muted">{{ $location['kode'] }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge modern-badge {{ $location['jenis'] == 'Kantor' ? 'badge-kantor' : 'badge-gedung' }}">
                                            {{ $location['jenis'] }}
                                        </span>
                                    </td>
                                    <td>{{ $location['alamat'] }}</td>
                                    <td>{{ $location['kota'] }}</td>
                                    <td>
                                        <span class="badge modern-badge {{ $location['status'] == 'aktif' ? 'badge-success' : 'badge-danger' }}">
                                            {{ ucfirst($location['status']) }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-modern btn-show-location show-location-btn" 
                                                data-lat="{{ $location['lat'] }}" 
                                                data-lng="{{ $location['lng'] }}" 
                                                data-nama="{{ $location['nama'] }}">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        <i class="fas fa-info-circle"></i>
                                        Belum ada data lokasi
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Modern Map Styling */
    .map-controls {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .btn-modern {
        border-radius: 0.75rem;
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .btn-kantor {
        background: linear-gradient(135deg, #3b82f6, #60a5fa);
        color: white;
        border-color: #3b82f6;
    }

    .btn-kantor:hover {
        background: linear-gradient(135deg, #2563eb, #3b82f6);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-gedung {
        background: linear-gradient(135deg, #10b981, #34d399);
        color: white;
        border-color: #10b981;
    }

    .btn-gedung:hover {
        background: linear-gradient(135deg, #059669, #10b981);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-all {
        background: linear-gradient(135deg, #06b6d4, #22d3ee);
        color: white;
        border-color: #06b6d4;
    }

    .btn-all:hover {
        background: linear-gradient(135deg, #0891b2, #06b6d4);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(6, 182, 212, 0.3);
    }

    .btn-fit {
        background: linear-gradient(135deg, #f59e0b, #fbbf24);
        color: white;
        border-color: #f59e0b;
    }

    .btn-fit:hover {
        background: linear-gradient(135deg, #d97706, #f59e0b);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }

    .btn-fullscreen {
        background: linear-gradient(135deg, #64748b, #94a3b8);
        color: white;
        border-color: #64748b;
    }

    .btn-fullscreen:hover {
        background: linear-gradient(135deg, #475569, #64748b);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(100, 116, 139, 0.3);
    }

    .btn-clear {
        background: linear-gradient(135deg, #ef4444, #f87171);
        color: white;
        border-color: #ef4444;
    }

    .btn-clear:hover {
        background: linear-gradient(135deg, #dc2626, #ef4444);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    /* Map Card */
    .map-card {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .map-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .map-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .map-title i {
        color: #3b82f6;
    }

    .search-container {
        position: relative;
        display: flex;
        align-items: center;
    }

    .search-input {
        padding: 0.75rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 0.75rem;
        background: white;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        width: 300px;
    }

    .search-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .search-btn {
        position: absolute;
        right: 0.5rem;
        background: #3b82f6;
        color: white;
        border: none;
        border-radius: 0.5rem;
        padding: 0.5rem;
        transition: all 0.3s ease;
    }

    .search-btn:hover {
        background: #2563eb;
        transform: scale(1.05);
    }

    .map-body {
        padding: 0;
    }

    .map-container {
        height: 500px;
        width: 100%;
        border-radius: 0;
    }

    /* Filter Card */
    .filter-card {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .filter-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .filter-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-title i {
        color: #3b82f6;
    }

    .filter-body {
        padding: 1.5rem;
    }

    .modern-select {
        border: 2px solid #e2e8f0;
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
        background: white;
        transition: all 0.3s ease;
    }

    .modern-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Statistics Cards */
    .stat-card {
        background: white;
        border-radius: 1.5rem;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }

    .stat-kantor .stat-icon {
        background: linear-gradient(135deg, #3b82f6, #60a5fa);
    }

    .stat-gedung .stat-icon {
        background: linear-gradient(135deg, #10b981, #34d399);
    }

    .stat-kontrak .stat-icon {
        background: linear-gradient(135deg, #06b6d4, #22d3ee);
    }

    .stat-pegawai .stat-icon {
        background: linear-gradient(135deg, #f59e0b, #fbbf24);
    }

    .stat-content {
        flex: 1;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 800;
        color: #1e293b;
        margin: 0;
        line-height: 1;
    }

    .stat-label {
        color: #64748b;
        font-size: 0.9rem;
        margin: 0.5rem 0 0 0;
        font-weight: 500;
    }

    /* Location Card */
    .location-card {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .location-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .location-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .location-title i {
        color: #3b82f6;
    }

    .location-body {
        padding: 0;
    }

    .modern-table {
        margin: 0;
    }

    .modern-table thead th {
        background: #f8fafc;
        border: none;
        padding: 1rem;
        font-weight: 600;
        color: #1e293b;
        border-bottom: 2px solid #e2e8f0;
    }

    .modern-table tbody td {
        padding: 1rem;
        border: none;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .modern-table tbody tr:hover {
        background: #f8fafc;
    }

    .modern-badge {
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-weight: 500;
        font-size: 0.8rem;
    }

    .badge-kantor {
        background: linear-gradient(135deg, #3b82f6, #60a5fa);
        color: white;
    }

    .badge-gedung {
        background: linear-gradient(135deg, #10b981, #34d399);
        color: white;
    }

    .badge-success {
        background: linear-gradient(135deg, #10b981, #34d399);
        color: white;
    }

    .badge-danger {
        background: linear-gradient(135deg, #ef4444, #f87171);
        color: white;
    }

    .btn-show-location {
        background: linear-gradient(135deg, #3b82f6, #60a5fa);
        color: white;
        border: none;
        border-radius: 0.5rem;
        padding: 0.5rem;
        transition: all 0.3s ease;
    }

    .btn-show-location:hover {
        background: linear-gradient(135deg, #2563eb, #3b82f6);
        transform: scale(1.05);
    }

    /* Custom Markers */
    .custom-marker {
        background: white;
        border: 2px solid;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        color: white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    }

    .kantor-marker {
        background: linear-gradient(135deg, #3b82f6, #60a5fa);
        border-color: #2563eb;
    }

    .gedung-marker {
        background: linear-gradient(135deg, #10b981, #34d399);
        border-color: #059669;
    }

    /* Popup Styling */
    .leaflet-popup-content {
        margin: 0;
        padding: 0;
    }

    .popup-content {
        padding: 1rem;
        min-width: 200px;
    }

    .popup-content h6 {
        margin-bottom: 0.75rem;
        color: #1e293b;
        font-weight: 600;
    }

    .popup-content p {
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        color: #64748b;
    }

    .popup-content .badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }

    /* Map Loading State */
    .map-loading {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 500px;
        background: #f8fafc;
        color: #64748b;
        font-size: 1.1rem;
    }

    .map-loading i {
        margin-right: 0.5rem;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    /* Button States */
    .btn-modern:active {
        transform: translateY(0);
    }

    .btn-modern:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Error States */
    .error-message {
        background: #fee2e2;
        color: #991b1b;
        padding: 1rem;
        border-radius: 0.75rem;
        margin: 1rem 0;
        border-left: 4px solid #ef4444;
    }

    /* Success States */
    .success-message {
        background: #d1fae5;
        color: #065f46;
        padding: 1rem;
        border-radius: 0.75rem;
        margin: 1rem 0;
        border-left: 4px solid #10b981;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .map-header {
            flex-direction: column;
            align-items: stretch;
        }

        .search-input {
            width: 100%;
        }

        .map-controls {
            justify-content: center;
        }

        .stat-card {
            flex-direction: column;
            text-align: center;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            font-size: 1.25rem;
        }

        .custom-marker {
            width: 25px;
            height: 25px;
            font-size: 12px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
// Get data from HTML data attributes

let map;
let kantorLayer;
let gedungLayer;
let allMarkers = [];
let filteredMarkers = [];
let isFullscreen = false;

// Initialize map
document.addEventListener('DOMContentLoaded', function() {
    try {
        console.log('Initializing map...');
        
        // Initialize Leaflet map centered on Indonesia
        map = L.map('map').setView([-2.5489, 118.0149], 5);
        
        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        
        // Create layer groups
        kantorLayer = L.layerGroup().addTo(map);
        gedungLayer = L.layerGroup().addTo(map);
        
        // Hide loading state
        const loadingElement = document.querySelector('.map-loading');
        if (loadingElement) {
            loadingElement.style.display = 'none';
        }
        
        // Load locations
        loadLocations();
        
        // Add event listeners for location buttons
        document.addEventListener('click', function(e) {
            if (e.target.closest('.show-location-btn')) {
                const btn = e.target.closest('.show-location-btn');
                const lat = parseFloat(btn.dataset.lat);
                const lng = parseFloat(btn.dataset.lng);
                const nama = btn.dataset.nama;
                showLocation(lat, lng, nama);
            }
        });
        
        // Add event listener for search input
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    searchLocations();
                }
            });
        }
        
        console.log('Map initialized successfully');
    } catch (error) {
        console.error('Error initializing map:', error);
        const loadingElement = document.querySelector('.map-loading');
        if (loadingElement) {
            loadingElement.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Error memuat peta';
        }
    }
});

// Load locations from server
function loadLocations() {
    try {
        // Get data from HTML data attributes
        const mapElement = document.getElementById('map');
        const kantorData = JSON.parse(mapElement.dataset.kantor || '[]');
        const gedungData = JSON.parse(mapElement.dataset.gedung || '[]');
        
        console.log('Kantor data:', kantorData);
        console.log('Gedung data:', gedungData);
        
        // Add kantor markers
        if (Array.isArray(kantorData)) {
            kantorData.forEach(function(kantor) {
                if (kantor && kantor.latitude && kantor.longitude) {
                    const marker = L.marker([parseFloat(kantor.latitude), parseFloat(kantor.longitude)], {
                        icon: L.divIcon({
                            className: 'custom-marker kantor-marker',
                            html: '<i class="fas fa-building"></i>',
                            iconSize: [30, 30],
                            iconAnchor: [15, 15]
                        })
                    }).bindPopup(`
                        <div class="popup-content">
                            <h6><i class="fas fa-building text-primary"></i> ${kantor.nama_kantor || 'N/A'}</h6>
                            <p><strong>Kode:</strong> ${kantor.kode_kantor || 'N/A'}</p>
                            <p><strong>Alamat:</strong> ${kantor.alamat || 'N/A'}</p>
                            <p><strong>Jenis:</strong> ${kantor.jenis_kantor?.nama_jenis || 'N/A'}</p>
                            <p><strong>Status:</strong> <span class="badge bg-${kantor.status_kantor === 'aktif' ? 'success' : 'danger'}">${kantor.status_kantor || 'N/A'}</span></p>
                        </div>
                    `);
                    kantorLayer.addLayer(marker);
                    allMarkers.push(marker);
                }
            });
        }
        
        // Add gedung markers
        if (Array.isArray(gedungData)) {
            gedungData.forEach(function(gedung) {
                if (gedung && gedung.latitude && gedung.longitude) {
                    const marker = L.marker([parseFloat(gedung.latitude), parseFloat(gedung.longitude)], {
                        icon: L.divIcon({
                            className: 'custom-marker gedung-marker',
                            html: '<i class="fas fa-home"></i>',
                            iconSize: [30, 30],
                            iconAnchor: [15, 15]
                        })
                    }).bindPopup(`
                        <div class="popup-content">
                            <h6><i class="fas fa-home text-success"></i> ${gedung.nama_gedung || 'N/A'}</h6>
                            <p><strong>Alamat:</strong> ${gedung.alamat || 'N/A'}</p>
                            <p><strong>Kota:</strong> ${gedung.kota?.nama_kota || 'N/A'}</p>
                            <p><strong>Status:</strong> <span class="badge bg-${gedung.status_gedung === 'aktif' ? 'success' : 'danger'}">${gedung.status_gedung || 'N/A'}</span></p>
                        </div>
                    `);
                    gedungLayer.addLayer(marker);
                    allMarkers.push(marker);
                }
            });
        }
        
        console.log('Total markers loaded:', allMarkers.length);
    } catch (error) {
        console.error('Error loading locations:', error);
    }
}

// Toggle layer visibility
function toggleLayer(layerType) {
    if (layerType === 'kantor') {
        if (map.hasLayer(kantorLayer)) {
            map.removeLayer(kantorLayer);
        } else {
            map.addLayer(kantorLayer);
        }
    } else if (layerType === 'gedung') {
        if (map.hasLayer(gedungLayer)) {
            map.removeLayer(gedungLayer);
        } else {
            map.addLayer(gedungLayer);
        }
    }
}

// Show all locations
function showAllLocations() {
    map.addLayer(kantorLayer);
    map.addLayer(gedungLayer);
    
    // Fit map to show all markers
    if (allMarkers.length > 0) {
        const group = new L.featureGroup(allMarkers);
        map.fitBounds(group.getBounds().pad(0.1));
    }
}

// Show specific location
function showLocation(lat, lng, nama) {
    map.setView([lat, lng], 15);
    
    // Find and open popup for this location
    allMarkers.forEach(function(marker) {
        if (marker.getLatLng().lat === lat && marker.getLatLng().lng === lng) {
            marker.openPopup();
        }
    });
}

// Search locations
function searchLocations() {
    try {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase().trim();
        console.log('Searching for:', searchTerm);
        
        if (!searchTerm) {
            showAllLocations();
            return;
        }
        
        // Clear existing markers
        kantorLayer.clearLayers();
        gedungLayer.clearLayers();
        
        let foundCount = 0;
        
        // Filter and show matching locations
        allMarkers.forEach(function(marker) {
            const popup = marker.getPopup();
            const content = popup.getContent();
            if (content.toLowerCase().includes(searchTerm)) {
                if (content.includes('fas fa-building')) {
                    kantorLayer.addLayer(marker);
                } else {
                    gedungLayer.addLayer(marker);
                }
                foundCount++;
            }
        });
        
        console.log('Found', foundCount, 'locations matching:', searchTerm);
        
        // Show message if no results
        if (foundCount === 0) {
            console.log('No locations found for:', searchTerm);
        }
    } catch (error) {
        console.error('Error in searchLocations:', error);
    }
}

// Apply filters
function applyFilters() {
    try {
        const statusFilter = document.getElementById('statusKantorFilter').value;
        const jenisFilter = document.getElementById('jenisKantorFilter').value;
        const kotaFilter = document.getElementById('kotaFilter').value;
        
        console.log('Applying filters:', { statusFilter, jenisFilter, kotaFilter });
        
        // Clear existing markers
        kantorLayer.clearLayers();
        gedungLayer.clearLayers();
        
        let filteredCount = 0;
        
        // Filter and show matching locations
        allMarkers.forEach(function(marker) {
            const popup = marker.getPopup();
            const content = popup.getContent();
            let showMarker = true;
            
            // Check status filter
            if (statusFilter && !content.toLowerCase().includes(statusFilter.toLowerCase())) {
                showMarker = false;
            }
            
            // Check jenis filter
            if (jenisFilter && !content.toLowerCase().includes(jenisFilter.toLowerCase())) {
                showMarker = false;
            }
            
            // Check kota filter
            if (kotaFilter && !content.toLowerCase().includes(kotaFilter.toLowerCase())) {
                showMarker = false;
            }
            
            if (showMarker) {
                if (content.includes('fas fa-building')) {
                    kantorLayer.addLayer(marker);
                } else {
                    gedungLayer.addLayer(marker);
                }
                filteredCount++;
            }
        });
        
        console.log('Filtered to', filteredCount, 'locations');
    } catch (error) {
        console.error('Error in applyFilters:', error);
    }
}

// Clear filters
function clearFilters() {
    document.getElementById('statusKantorFilter').value = '';
    document.getElementById('jenisKantorFilter').value = '';
    document.getElementById('kotaFilter').value = '';
    document.getElementById('searchInput').value = '';
    showAllLocations();
}

// Fit to bounds
function fitToBounds() {
    if (allMarkers.length > 0) {
        const group = new L.featureGroup(allMarkers);
        map.fitBounds(group.getBounds().pad(0.1));
    }
}

// Toggle fullscreen
function toggleFullscreen() {
    try {
        const mapContainer = document.getElementById('map');
        
        if (!isFullscreen) {
            if (mapContainer.requestFullscreen) {
                mapContainer.requestFullscreen();
            } else if (mapContainer.webkitRequestFullscreen) {
                mapContainer.webkitRequestFullscreen();
            } else if (mapContainer.msRequestFullscreen) {
                mapContainer.msRequestFullscreen();
            } else {
                console.log('Fullscreen not supported');
                return;
            }
            isFullscreen = true;
            console.log('Entered fullscreen');
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
            isFullscreen = false;
            console.log('Exited fullscreen');
        }
    } catch (error) {
        console.error('Error in toggleFullscreen:', error);
    }
}
</script>
@endpush
