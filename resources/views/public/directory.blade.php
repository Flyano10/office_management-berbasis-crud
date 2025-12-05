@extends('layouts.public')

@section('title', 'Directory Kantor PLN Icon Plus')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Card -->
    <div class="directory-header-card mb-4">
        <div class="directory-header-icon">
            <i class="fas fa-list"></i>
        </div>
        <div class="directory-header-content">
            <h1 class="directory-title">Directory Kantor PLN</h1>
            <p class="directory-subtitle">Daftar lengkap kantor PLN dengan informasi kontak</p>
        </div>
    </div>

    <!-- View Toggle -->
    <div class="directory-view-toggle mb-4">
        <div class="d-flex justify-content-center">
            <div class="btn-group" role="group">
                <button type="button" class="btn directory-toggle-btn active" id="listViewBtn">
                    <i class="fas fa-list me-1"></i>Daftar Kantor
                </button>
                <button type="button" class="btn directory-toggle-btn" id="mapViewBtn">
                    <i class="fas fa-map me-1"></i>Lihat Peta
                </button>
            </div>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="directory-search-card mb-4">
        <div class="directory-search-header">
            <h4 class="directory-search-title">
                <i class="fas fa-search me-2"></i>Filter Pencarian
            </h4>
            <p class="directory-search-subtitle">Temukan kantor PLN sesuai kebutuhan Anda</p>
        </div>
        <div class="directory-search-content">
            <form method="GET" action="{{ route('public.directory') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="directory-form-label">Cari Kantor</label>
                    <input type="text" class="directory-form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Nama kantor atau alamat...">
                </div>
                <div class="col-md-3">
                    <label for="kota_id" class="directory-form-label">Kota</label>
                    <select class="directory-form-select" id="kota_id" name="kota_id">
                        <option value="">Semua Kota</option>
                        @foreach($kota as $k)
                            <option value="{{ $k->id }}" {{ request('kota_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kota }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="jenis_kantor_id" class="directory-form-label">Jenis Kantor</label>
                    <select class="directory-form-select" id="jenis_kantor_id" name="jenis_kantor_id">
                        <option value="">Semua Jenis</option>
                        @foreach($jenisKantor as $jk)
                            <option value="{{ $jk->id }}" {{ request('jenis_kantor_id') == $jk->id ? 'selected' : '' }}>
                                {{ $jk->nama_jenis }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="directory-form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn directory-btn-primary">
                            <i class="fas fa-search me-1"></i>Cari
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- List View -->
    <div id="listView" class="directory-view-content">
        <div class="directory-list-card">
            <div class="directory-list-header">
                <h4 class="directory-list-title">
                    <i class="fas fa-list me-2"></i>Daftar Kantor
                </h4>
                <p class="directory-list-subtitle">Total {{ $kantor->total() }} kantor ditemukan</p>
            </div>
            <div class="directory-list-content">
                @if($kantor->count() > 0)
                    <div class="directory-table-responsive">
                        <table class="directory-table">
                            <thead>
                                <tr>
                                    <th class="directory-th-sticky">Nama Kantor</th>
                                    <th>Jenis</th>
                                    <th>Kota</th>
                                    <th>Telepon</th>
                                    <th>Lihat di Peta</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kantor as $k)
                                    <tr class="directory-table-row">
                                        <td class="directory-td-sticky">
                                            <div class="directory-kantor-info">
                                                <strong class="directory-kantor-name">{{ $k->nama_kantor }}</strong>
                                                @if($k->alamat)
                                                    <p class="directory-kantor-address">{{ $k->alamat }}</p>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="directory-badge directory-badge-{{ strtolower($k->jenisKantor->nama_jenis ?? 'default') }}">
                                                {{ $k->jenisKantor->nama_jenis ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="directory-kantor-city">{{ $k->kota->nama_kota ?? '-' }}</td>
                                        <td class="directory-kantor-phone">{{ $k->telepon ?? '-' }}</td>
                                        <td>
                                            @if($k->latitude && $k->longitude)
                                                <button class="btn directory-btn-outline show-map-btn" 
                                                        data-lat="{{ $k->latitude }}" 
                                                        data-lng="{{ $k->longitude }}" 
                                                        data-name="{{ $k->nama_kantor }}">
                                                    <i class="fas fa-map-marker-alt me-1"></i>Lihat
                                                </button>
                                            @else
                                                <span class="directory-no-location">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="directory-pagination">
                        {{ $kantor->links() }}
                    </div>
                @else
                    <div class="directory-empty-state">
                        <i class="fas fa-search directory-empty-icon"></i>
                        <h5 class="directory-empty-title">Tidak ada kantor ditemukan</h5>
                        <p class="directory-empty-subtitle">Coba ubah filter pencarian Anda</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Map View -->
    <div id="mapView" class="directory-view-content" style="display: none;">
        <div class="directory-map-card">
            <div class="directory-map-header">
                <h4 class="directory-map-title">
                    <i class="fas fa-map me-2"></i>Peta Kantor PLN
                </h4>
                <p class="directory-map-subtitle">Lihat lokasi kantor PLN di peta interaktif</p>
            </div>
            <div class="directory-map-content">
                <div id="map" class="directory-map-container"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let map;
let markers = [];

// View Toggle
document.getElementById('listViewBtn').addEventListener('click', function() {
    document.getElementById('listView').style.display = 'block';
    document.getElementById('mapView').style.display = 'none';
    this.classList.add('active');
    document.getElementById('mapViewBtn').classList.remove('active');
});

document.getElementById('mapViewBtn').addEventListener('click', function() {
    document.getElementById('listView').style.display = 'none';
    document.getElementById('mapView').style.display = 'block';
    this.classList.add('active');
    document.getElementById('listViewBtn').classList.remove('active');
    
    // Initialize map if not already done
    if (!map) {
        initMap();
    }
});

// Initialize Map
function initMap() {
    map = L.map('map').setView([-6.200000, 106.816666], 6);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    // Load kantor data
    fetch('{{ route("public.api.kantor") }}')
        .then(response => response.json())
        .then(data => {
            data.forEach(kantor => {
                const marker = L.marker([kantor.latitude, kantor.longitude])
                    .addTo(map)
                    .bindPopup(`
                        <div>
                            <h6><strong>${kantor.nama_kantor}</strong></h6>
                            <p class="mb-1">${kantor.alamat}</p>
                            <p class="mb-1"><i class="fas fa-phone"></i> ${kantor.telepon}</p>
                            <p class="mb-1"><i class="fas fa-envelope"></i> ${kantor.email}</p>
                            <p class="mb-0"><small class="text-muted">${kantor.kota} - ${kantor.jenis}</small></p>
                        </div>
                    `);
                markers.push(marker);
            });
            
            // Fit map to show all markers
            if (markers.length > 0) {
                const group = new L.featureGroup(markers);
                map.fitBounds(group.getBounds().pad(0.1));
            }
        });
}

// Show specific kantor on map
function showOnMap(lat, lng, nama) {
    // Switch to map view
    document.getElementById('listView').style.display = 'none';
    document.getElementById('mapView').style.display = 'block';
    document.getElementById('listViewBtn').classList.remove('active');
    document.getElementById('mapViewBtn').classList.add('active');
    
    // Initialize map if not already done
    if (!map) {
        initMap();
    }
    
    // Center map on specific kantor
    map.setView([lat, lng], 15);
    
    // Add marker for specific kantor
    L.marker([lat, lng]).addTo(map)
        .bindPopup(`<strong>${nama}</strong>`)
        .openPopup();
}

// Add event listeners for show map buttons
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.show-map-btn').forEach(button => {
        button.addEventListener('click', function() {
            const lat = parseFloat(this.dataset.lat);
            const lng = parseFloat(this.dataset.lng);
            const name = this.dataset.name;
            showOnMap(lat, lng, name);
        });
    });
});
</script>
@endpush
