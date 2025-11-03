@extends('layouts.app')

@section('title', 'Peta Lokasi - PLN Icon Plus')
@section('page-title', 'Peta Lokasi')

@section('content')
<div class="peta-wrapper">
    <!-- Header -->
    <div class="peta-header">
        <div class="header-left">
            <img src="{{ asset('images/pln-icon.png') }}" alt="PLN Icon Plus" class="pln-logo">
            <h1>Peta Lokasi</h1>
        </div>
        <div class="header-nav">
            <a href="{{ route('home') }}" class="nav-item">Home</a>
            <a href="{{ route('peta') }}" class="nav-item active">Peta</a>
            <a href="{{ route('directory') }}" class="nav-item">Directory</a>
        </div>
    </div>

    <!-- Map Controls -->
    <div class="map-controls">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-simple" onclick="toggleLayer('kantor')">
                <i class="fas fa-building"></i> Kantor
            </button>
            <button type="button" class="btn btn-simple" onclick="toggleLayer('gedung')">
                <i class="fas fa-home"></i> Gedung
            </button>
            <button type="button" class="btn btn-simple" onclick="showAllLocations()">
                <i class="fas fa-eye"></i> Semua
            </button>
        </div>
    </div>

@section('content')
<div class="container-fluid">
    <!-- Map Section -->
    <div class="row">
        <div class="col-12">
            <div class="map-card">
                <div class="map-header">
                    <div class="nav-section">
                        <div class="nav-links">
                            <a href="{{ route('home') }}" class="nav-link">Home</a>
                            <a href="{{ route('peta') }}" class="nav-link active">Peta</a>
                            <a href="{{ route('directory') }}" class="nav-link">Directory</a>
                        </div>
                    </div>
                    <div class="map-title-section">
                        <h5 class="map-title">
                            <i class="fas fa-map-marked-alt"></i>
                            Peta Lokasi Kantor & Gedung PLN Icon Plus
                        </h5>
                        <div class="map-legend">
                            <div class="legend-item">
                                <span class="legend-dot pusat"></span>
                                <span>Pusat</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-dot perwakilan"></span>
                                <span>Perwakilan</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-dot sbu"></span>
                                <span>SBU</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-dot gudang"></span>
                                <span>Gudang</span>
                            </div>
                        </div>
                    </div>
                    <div class="map-search-section">
                        <div class="search-container">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" class="search-input" id="searchInput" placeholder="Cari lokasi kantor..." oninput="searchLocations()">
                            <div class="loading-indicator"></div>
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
    /* Styling Map Modern */
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

    /* Card Map */
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
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        width: 300px;
        animation: fadeIn 0.5s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
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

    /* Card Filter */
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
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .modern-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        transform: translateY(-1px);
    }

    .modern-select:hover {
        border-color: #3b82f6;
        transform: translateY(-1px);
    }


    /* Card Lokasi */
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

    /* Marker Custom */
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

    /* Styling Popup */
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

    /* State Loading Map */
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

    /* State Button */
    .btn-modern:active {
        transform: translateY(0);
    }

    .btn-modern:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* State Error */
    .error-message {
        background: #fee2e2;
        color: #991b1b;
        padding: 1rem;
        border-radius: 0.75rem;
        margin: 1rem 0;
        border-left: 4px solid #ef4444;
    }

    /* State Success */
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
// Ambil data dari HTML data attributes

let map;
let kantorLayer;
let gedungLayer;
let allMarkers = [];
let filteredMarkers = [];
let isFullscreen = false;

// Inisialisasi map
document.addEventListener('DOMContentLoaded', function() {
    try {
        console.log('Initializing map...');
        
        // Inisialisasi Leaflet map terpusat di Indonesia
        map = L.map('map').setView([-2.5489, 118.0149], 5);
        
        // Tambah tile OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        
        // Buat grup layer
        kantorLayer = L.layerGroup().addTo(map);
        gedungLayer = L.layerGroup().addTo(map);
        
        // Sembunyikan state loading
        const loadingElement = document.querySelector('.map-loading');
        if (loadingElement) {
            loadingElement.style.display = 'none';
        }
        
        // Load lokasi
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
        // Ambil data dari HTML data attributes
        const mapElement = document.getElementById('map');
        const kantorData = JSON.parse(mapElement.dataset.kantor || '[]');
        const gedungData = JSON.parse(mapElement.dataset.gedung || '[]');
        
        console.log('Kantor data:', kantorData);
        console.log('Gedung data:', gedungData);
        
        // Add kantor markers with enhanced styling
        if (Array.isArray(kantorData)) {
            kantorData.forEach(function(kantor) {
                if (kantor && kantor.latitude && kantor.longitude) {
                    const markerIcon = L.divIcon({
                        className: `custom-marker ${kantor.jenis_kantor?.nama_jenis?.toLowerCase() || 'default'}-marker`,
                        html: `<div class="marker-inner">
                                <i class="fas fa-building"></i>
                                <div class="marker-pulse"></div>
                              </div>`,
                        iconSize: [40, 40],
                        iconAnchor: [20, 20]
                    });
                    const marker = L.marker([parseFloat(kantor.latitude), parseFloat(kantor.longitude)], {
                        icon: markerIcon
                    }).bindPopup(`
                        <div class="popup-container">
                            <div class="popup-header">
                                <h5>${kantor.nama_kantor || 'N/A'}</h5>
                                <div class="popup-badges">
                                    <span class="badge" style="background: var(--pln-primary)">${kantor.jenis_kantor?.nama_jenis || 'N/A'}</span>
                                    <span class="badge" style="background: var(--pln-${kantor.status_kantor === 'aktif' ? 'success' : 'danger'})">${kantor.status_kantor || 'N/A'}</span>
                                </div>
                            </div>
                            <div class="popup-content">
                                <div class="popup-info">
                                    <div class="info-item">
                                        <i class="fas fa-building"></i>
                                        <div class="info-content">
                                            <label>Kode</label>
                                            <span>${kantor.kode_kantor || 'N/A'}</span>
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <div class="info-content">
                                            <label>Alamat</label>
                                            <span>${kantor.alamat || 'N/A'}</span>
                                        </div>
                                    </div>
                                </div>
                                <div id="expiring-badge-${kantor.id}" class="expiring-section"></div>
                                <div id="expiring-list-${kantor.id}" class="expiring-list"></div>
                            </div>
                        </div>
                    `);
                    kantorLayer.addLayer(marker);
                    allMarkers.push(marker);

                    // Fetch expiring contracts when popup opens
                    marker.on('popupopen', function() {
                        fetchExpiringContracts(kantor.id);
                    });
                }
            });
        }
        
        // Add gedung markers with enhanced styling
        if (Array.isArray(gedungData)) {
            gedungData.forEach(function(gedung) {
                if (gedung && gedung.latitude && gedung.longitude) {
                    const markerIcon = L.divIcon({
                        className: 'custom-marker gedung-marker',
                        html: `<div class="marker-inner">
                                <i class="fas fa-home"></i>
                                <div class="marker-ripple"></div>
                              </div>`,
                        iconSize: [35, 35],
                        iconAnchor: [17.5, 17.5]
                    });
                    const marker = L.marker([parseFloat(gedung.latitude), parseFloat(gedung.longitude)], {
                        icon: markerIcon
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

// Debounce function untuk mencegah terlalu banyak request
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Fungsi pencarian yang di-debounce
const debouncedSearch = debounce(function() {
    const searchContainer = document.querySelector('.search-container');
    const searchInput = document.getElementById('searchInput');
    const searchTerm = searchInput.value.toLowerCase().trim();
    
    try {
        searchContainer.classList.add('searching');
        
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
        
        // Show/hide no results message
        const noResultsMessage = document.getElementById('noResultsMessage');
        if (foundCount === 0 && searchTerm !== '') {
            if (!noResultsMessage) {
                const message = document.createElement('div');
                message.id = 'noResultsMessage';
                message.className = 'alert alert-info mt-3';
                message.innerHTML = `<i class="fas fa-info-circle"></i> Tidak ditemukan lokasi dengan kata kunci "${searchTerm}"`;
                document.querySelector('.map-header').insertAdjacentElement('afterend', message);
            }
        } else if (noResultsMessage) {
            noResultsMessage.remove();
        }
        
        // Center map on results if found
        if (foundCount > 0) {
            const bounds = L.featureGroup([kantorLayer, gedungLayer]).getBounds();
            map.fitBounds(bounds, { padding: [50, 50] });
        }
        
    } catch (error) {
        console.error('Error in searchLocations:', error);
    } finally {
        searchContainer.classList.remove('searching');
    }
}, 300);

// Event listener untuk pencarian
document.getElementById('searchInput').addEventListener('input', debouncedSearch);

// Fungsi filter yang dioptimalkan
function applyFilters() {
    const statusFilter = document.getElementById('statusKantorFilter').value;
    const jenisFilter = document.getElementById('jenisKantorFilter').value;
    const kotaFilter = document.getElementById('kotaFilter').value;
    
    try {
        // Clear existing markers
        kantorLayer.clearLayers();
        gedungLayer.clearLayers();
        
        let filteredCount = 0;
        
        // Filter and show matching locations
        allMarkers.forEach(function(marker) {
            const popup = marker.getPopup();
            const content = popup.getContent();
            
            let matchStatus = !statusFilter || content.toLowerCase().includes(statusFilter);
            let matchJenis = !jenisFilter || content.toLowerCase().includes(jenisFilter);
            let matchKota = !kotaFilter || content.toLowerCase().includes(kotaFilter);
            
            if (matchStatus && matchJenis && matchKota) {
                if (content.includes('fas fa-building')) {
                    kantorLayer.addLayer(marker);
                } else {
                    gedungLayer.addLayer(marker);
                }
                filteredCount++;
            }
        });
        
        // Show/hide no results message
        const noResultsMessage = document.getElementById('noResultsMessage');
        if (filteredCount === 0 && (statusFilter || jenisFilter || kotaFilter)) {
            if (!noResultsMessage) {
                const message = document.createElement('div');
                message.id = 'noResultsMessage';
                message.className = 'alert alert-info mt-3';
                message.innerHTML = '<i class="fas fa-info-circle"></i> Tidak ditemukan lokasi yang sesuai dengan filter yang dipilih';
                document.querySelector('.map-header').insertAdjacentElement('afterend', message);
            }
        } else if (noResultsMessage) {
            noResultsMessage.remove();
        }
        
        // Center map on filtered results
        if (filteredCount > 0) {
            const bounds = L.featureGroup([kantorLayer, gedungLayer]).getBounds();
            map.fitBounds(bounds, { padding: [50, 50] });
        }
        
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

// Fetch expiring contracts for a kantor and render badge + list
async function fetchExpiringContracts(kantorId) {
    try {
        const url = `{{ route('peta.kontrak-expiring') }}?kantor_id=${encodeURIComponent(kantorId)}&window=all`;
        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
        if (!res.ok) {
            return;
        }
        const data = await res.json();
        if (!data || !data.success) {
            return;
        }

        const counts = data.counts || { m6: 0, m3: 0, m1: 0 };
        const total = (counts.m6 || 0) + (counts.m3 || 0) + (counts.m1 || 0);

        const badgeEl = document.getElementById(`expiring-badge-${kantorId}`);
        if (badgeEl) {
            if (total > 0) {
                badgeEl.style.display = 'block';
                badgeEl.innerHTML = `
                    <span class="badge bg-info" style="margin-right:6px;">6 bln: ${counts.m6 || 0}</span>
                    <span class="badge bg-warning text-dark" style="margin-right:6px;">3 bln: ${counts.m3 || 0}</span>
                    <span class="badge bg-danger">1 bln: ${counts.m1 || 0}</span>
                `;
            } else {
                badgeEl.style.display = 'none';
            }
        }

        const listEl = document.getElementById(`expiring-list-${kantorId}`);
        if (listEl) {
            const groups = data.data || {};
            const items = [...(groups.m1 || []), ...(groups.m3 || []), ...(groups.m6 || [])].slice(0, 5);
            if (items.length > 0) {
                const rows = items.map(item => {
                    const days = item.days_to_end;
                    let cls = 'bg-info';
                    if (days <= 31) cls = 'bg-danger';
                    else if (days <= 92) cls = 'bg-warning text-dark';
                    return `
                        <div style="display:flex; align-items:center; justify-content:space-between; gap:8px; margin-bottom:6px;">
                            <div style="flex:1; min-width:0;">
                                <div style="font-weight:600; color:#1f2937; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">${item.nama_perjanjian}</div>
                                <div style="font-size:12px; color:#6b7280;">Selesai: ${item.tanggal_selesai}</div>
                            </div>
                            <span class="badge ${cls}" title="Sisa hari">Sisa ${days} hari</span>
                        </div>`;
                }).join('');
                listEl.innerHTML = `<div style="border-top:1px solid #e5e7eb; padding-top:8px;">${rows}</div>`;
                listEl.style.display = 'block';
            } else {
                listEl.style.display = 'none';
            }
        }
    } catch (e) {
        // silent fail to keep popup clean
        console.warn('Failed loading expiring contracts', e);
    }
}
</script>
@endpush
