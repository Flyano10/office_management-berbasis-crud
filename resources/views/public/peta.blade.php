@extends('layouts.public')

@section('title', 'Peta Kantor PLN')

@push('styles')
<style>
 /* Popup Leaflet - Clean look */
 .leaflet-popup.custom-popup .leaflet-popup-content-wrapper {
  background: #ffffff;
  border: 1px solid #1D5C7F;
  border-radius: 14px;
  box-shadow: 0 16px 42px rgba(15, 61, 87, 0.2);
  padding: 0;
  overflow: hidden;
 }
 .leaflet-popup.custom-popup .leaflet-popup-tip {
  background: #ffffff;
  border: 1px solid #1D5C7F;
  box-shadow: none;
 }
 .leaflet-popup.custom-popup .leaflet-popup-content {
  margin: 0;
 }
.custom-popup .popup-container {
  width: 400px;
  max-height: 600px;
  overflow-y: auto;
}
 .custom-popup .popup-header {
  background: #1D5C7F;
  padding: 10px 12px;
  border-bottom: 1px solid rgba(0, 0, 0, 0.06);
  border-radius: 12px 12px 0 0;
 }
 .custom-popup .popup-header h6 { margin: 0 0 2px; font-weight: 700; font-size: 0.95rem; color:#ffffff; }
 .custom-popup .popup-header p { margin: 0; color: rgba(255,255,255,0.88); display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
 .custom-popup .popup-header .text-white { color:#ffffff !important; }
 .custom-popup .popup-header .text-white-50 { color: rgba(255,255,255,0.75) !important; }
 .custom-popup .popup-header .employee-summary { background: rgba(255,255,255,0.12); border-color: rgba(255,255,255,0.28); color:#ffffff; }
 .custom-popup .employee-summary { background: rgba(29, 92, 127, 0.06); border:1px solid #1D5C7F; border-radius: 10px; color:#1D5C7F; }
 .custom-popup .nav.nav-tabs.nav-sm {
  border-bottom: 1px solid #1D5C7F;
  padding: 0 12px;
  gap: 8px;
 }
 .custom-popup .nav.nav-tabs.nav-sm .nav-link {
  border: none;
  border-bottom: 2px solid transparent;
  padding: 8px 8px;
  font-size: .8rem;
  color: rgba(29,92,127,0.8);
  background: transparent;
  border-radius: 0;
 }
 .custom-popup .nav.nav-tabs.nav-sm .nav-link.active {
  color: #1D5C7F;
  font-weight: 600;
  border-bottom-color: #1D5C7F;
  background: transparent;
 }
.custom-popup .tab-content { 
  padding: 12px; 
  max-height: 400px;
  overflow-y: auto;
}
.custom-popup .popup-body { 
  padding: 8px 0; 
}
 .custom-popup .badge { border-radius: 999px; font-weight: 600; padding:4px 8px; line-height: 1; }
 .custom-popup .badge i { font-size: .8rem; }
 .custom-popup .d-flex.align-items-center { gap: 6px; }
 .custom-popup i.fas { font-size: .85rem; }
 /* Two-color scheme for badges and text inside popup */
 .custom-popup .bg-warning-subtle,
 .custom-popup .bg-success-subtle,
 .custom-popup .bg-info-subtle,
 .custom-popup .bg-primary-subtle,
 .custom-popup .bg-secondary-subtle { background: rgba(29, 92, 127, 0.1) !important; color:#1D5C7F !important; border:1px solid #1D5C7F !important; }
 .custom-popup .text-warning-emphasis,
 .custom-popup .text-success-emphasis,
 .custom-popup .text-info-emphasis,
 .custom-popup .text-primary-emphasis,
 .custom-popup .text-secondary-emphasis { color:#1D5C7F !important; }
 .custom-popup .text-primary,
 .custom-popup .text-info,
 .custom-popup .text-success,
 .custom-popup .text-warning,
 .custom-popup .text-secondary { color:#1D5C7F !important; }
 .custom-popup .btn { border-radius: 10px; box-shadow: none !important; }
 .custom-popup .btn.btn-primary { background:#1D5C7F; border-color:#1D5C7F; }
 .custom-popup .btn.btn-outline-info { border-color:#1D5C7F; color:#1D5C7F; }
 .custom-popup .btn.btn-outline-info:hover { background:#1D5C7F; color:#fff; }
 .custom-popup small { color:#1D5C7F; }
 .custom-popup .icon.icon-18 { width:14px; height:14px; }
 @media (max-width: 420px){ .custom-popup .popup-container{ width: 300px; } }
</style>
@endpush

@section('content')
<div class="peta-page">
    <div class="container-fluid px-0 peta-fullscreen">
        <div class="peta-dashboard">
            <aside class="peta-dashboard-panel" data-panel="dashboard">
                <header class="panel-header">
                    <div>
                        <span class="panel-tag">Dashboard Kantor</span>
                        <h1 class="panel-title">Ringkasan Lokasi</h1>
                    </div>
                    <button class="panel-collapse" type="button" aria-label="Sembunyikan panel" data-panel-toggle>
                        <i class="fas fa-chevron-left"></i>
                    </button>
                </header>
                <div class="panel-divider"></div>
                <div class="panel-body">
                    <div class="panel-section stats-overview">
                        <div class="stat-card">
                            <div class="stat-icon"><i class="fas fa-building"></i></div>
                            <div>
                                <span class="stat-label">Kantor Pusat</span>
                                <span class="stat-value" id="stat-pusat">-</span>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon"><i class="fas fa-network-wired"></i></div>
                            <div>
                                <span class="stat-label">Kantor SBU</span>
                                <span class="stat-value" id="stat-sbu">-</span>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon"><i class="fas fa-flag"></i></div>
                            <div>
                                <span class="stat-label">Perwakilan</span>
                                <span class="stat-value" id="stat-perwakilan">-</span>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon"><i class="fas fa-warehouse"></i></div>
                            <div>
                                <span class="stat-label">Gudang</span>
                                <span class="stat-value" id="stat-gudang">-</span>
                            </div>
                        </div>
                    </div>
                    <div class="panel-summary">
                        <span class="summary-label">Total Kantor</span>
                        <span class="summary-value" id="stat-total">-</span>
                    </div>
                    <div class="panel-divider"></div>
                    <div class="panel-actions">
                        <button class="panel-btn primary" type="button" onclick="openSearchModal()">
                            <i class="fas fa-search me-2"></i>Cari Kantor
                        </button>
                        <button class="panel-btn" type="button" data-map-action="fit">
                            <i class="fas fa-location-arrow me-2"></i>Fokus Semua Marker
                        </button>
                    </div>
                    <div class="panel-divider"></div>
                    <div class="panel-legend">
                        <span class="legend-title">Legenda</span>
                        <ul class="legend-list">
                            <li><span class="legend-dot" style="background: #DC2626;"></span>Pusat</li>
                            <li><span class="legend-dot" style="background: #2563EB;"></span>SBU</li>
                            <li><span class="legend-dot" style="background: #F59E0B;"></span>Perwakilan</li>
                            <li><span class="legend-dot" style="background: #7C3AED;"></span>Gudang</li>
                        </ul>
                    </div>
                    <div class="panel-divider"></div>
                    <div class="panel-tips">
                        <div class="peta-side-tip">
                            <i class="fas fa-lightbulb"></i>
                            <p>Klik marker untuk detail lengkap kantor, termasuk inventaris & kontrak.</p>
                        </div>
                        <div class="peta-quick-section">
                            <h4>Fitur Cepat</h4>
                            <div class="peta-quick-list">
                                <div class="peta-quick-item">
                                    <span class="peta-quick-icon"><i class="fas fa-sync"></i></span>
                                    <div>
                                        <strong>Refresh Data</strong>
                                        <p>Muat ulang peta untuk memastikan data terkini.</p>
                                    </div>
                                </div>
                                <div class="peta-quick-item">
                                    <span class="peta-quick-icon"><i class="fas fa-filter"></i></span>
                                    <div>
                                        <strong>Filter Dinamis</strong>
                                        <p>Gunakan pencarian untuk saring kota dan jenis kantor.</p>
                                    </div>
                                </div>
                                <div class="peta-quick-item">
                                    <span class="peta-quick-icon"><i class="fas fa-route"></i></span>
                                    <div>
                                        <strong>Rute Cepat</strong>
                                        <p>Buka popup marker untuk navigasi via Google Maps.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
            <div class="peta-dashboard-map">
                <div id="map" class="peta-map"></div>
                <div class="map-controls">
                    <button type="button" class="map-control" data-map-action="zoom-in" aria-label="Perbesar">+</button>
                    <button type="button" class="map-control" data-map-action="zoom-out" aria-label="Perkecil">-</button>
                    <button type="button" class="map-control" data-map-action="fit" aria-label="Fokus semua marker">
                        <i class="fas fa-crosshairs"></i>
                    </button>
                    <button type="button" class="map-control" data-panel-toggle aria-label="Tampilkan panel">
                        <i class="fas fa-list-ul"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search Modal -->
<div class="modal fade" id="searchModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content peta-modal">
            <div class="modal-header peta-modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-search me-2"></i>Pencarian Kantor PLN
                </h5>
                <button type="button" class="btn-close" onclick="closeSearchModal()"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label peta-label">
                            <i class="fas fa-search me-2"></i>Cari Kantor
                        </label>
                        <input type="text" class="form-control peta-input" id="searchBox" 
                               placeholder="Masukkan nama kantor, alamat, atau kota...">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label peta-label">
                            <i class="fas fa-map-marker-alt me-2"></i>Kota
                        </label>
                        <select class="form-select peta-select" id="filterKota">
                            <option value="">Semua Kota</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label peta-label">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke="currentColor" class="icon icon-18 me-2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                            </svg>Jenis Kantor
                        </label>
                        <select class="form-select peta-select" id="filterJenis">
                            <option value="">Semua Jenis</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" onclick="clearFilters()">
                    <i class="fas fa-redo me-2"></i>Reset
                </button>
                <button type="button" class="btn btn-primary" onclick="searchKantor()">
                    <i class="fas fa-search me-2"></i>Cari Kantor
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let map;
let markers = [];
let searchResults = [];
let currentPopup = null;
const PANEL_STATE_KEY = 'petaDashboardPanelCollapsed';
const INDONESIA_CENTER = { lat: -2.5489, lng: 118.0149 };

function formatOwnership(value) {
    if (!value) {
        return '-';
    }
    return value
        .toString()
        .replace(/_/g, ' ')
        .replace(/\b\w/g, (char) => char.toUpperCase());
}

function formatArea(value) {
    if (value === null || value === undefined || value === '') {
        return '-';
    }
    const numeric = Number(value);
    if (Number.isNaN(numeric)) {
        return '-';
    }
    return `${numeric.toLocaleString('id-ID', { maximumFractionDigits: 2 })} m²`;
}

function formatLayoutLink(layoutUrl) {
    if (!layoutUrl) {
        return '';
    }
    return `
        <a href="${layoutUrl}" target="_blank" class="btn btn-outline-info btn-sm w-100 mt-2">
            <i class="fas fa-file-image me-1"></i> Lihat Layout Gedung
        </a>
    `;
}

function normalizeJenisLabel(value) {
    if (!value) {
        return '';
    }

    const lower = value.toString().toLowerCase();

    switch (lower) {
        case 'kp':
        case 'kantor perwakilan':
            return 'Perwakilan';
        case 'pusat':
            return 'Pusat';
        case 'sbu':
            return 'SBU';
        case 'gudang':
            return 'Gudang';
        default:
            return value;
    }
}

// Initialize Map
function initMap() {
    // Center map on Indonesia
    const indonesiaBounds = getIndonesiaBounds();

    map = L.map('map', {
        zoomControl: false,
        minZoom: 4,
        maxZoom: 18,
        maxBounds: indonesiaBounds.pad(0.05),
        maxBoundsViscosity: 1.0
    }).setView([INDONESIA_CENTER.lat, INDONESIA_CENTER.lng], 5);

    map.on('dragend', clampMapToIndonesia);
    map.on('zoomend', clampMapToIndonesia);

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Load kantor data
    loadKantorData(true);

    clampMapToIndonesia();

    initMapControls();
    restorePanelState();
}

// Load kantor data from API
function loadKantorData(focusAfterLoad = false) {
    fetch('{{ route("public.api.kantor") }}', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(rawData => {
            if (!rawData || !Array.isArray(rawData)) {
                console.error('Invalid data format:', rawData);
                return;
            }
            
            const normalizedData = (rawData || []).map(kantor => ({
                ...kantor,
                jenis: normalizeJenisLabel(kantor.jenis)
            }));

            searchResults = normalizedData;
            addMarkersToMap(normalizedData);
            updateStatistics(normalizedData);
            populateFilterOptions(normalizedData);
            if (focusAfterLoad) {
                focusAllMarkers();
                setTimeout(() => map && map.invalidateSize(), 250);
            }
        })
        .catch(error => {
            console.error('Error loading kantor data:', error);
            alert('Error loading kantor data. Please refresh the page.');
        });
}

// Populate filter options
function populateFilterOptions(data) {
    const kotaSelect = document.getElementById('filterKota');
    const jenisSelect = document.getElementById('filterJenis');

    if (!kotaSelect || !jenisSelect) {
        return;
    }

    const uniqueKota = [...new Set(data.map(k => k.kota))].filter(Boolean).sort();
    const uniqueJenis = [...new Set(data.map(k => k.jenis))].filter(Boolean).sort();

    // Clear existing options except placeholder
    kotaSelect.length = 1;
    jenisSelect.length = 1;

    uniqueKota.forEach(kota => {
        const option = document.createElement('option');
        option.value = kota;
        option.textContent = kota;
        kotaSelect.appendChild(option);
    });
    
    uniqueJenis.forEach(jenis => {
        const option = document.createElement('option');
        option.value = jenis;
        option.textContent = jenis;
        jenisSelect.appendChild(option);
    });
}


// Add markers to map
function addMarkersToMap(kantorData) {
    // Clear existing markers
    markers.forEach(marker => map.removeLayer(marker));
    markers = [];
    
    if (!kantorData || !Array.isArray(kantorData)) {
        console.error('Invalid kantorData:', kantorData);
        return;
    }
    
    let markerCount = 0;
    kantorData.forEach((kantor) => {
        const lat = Number(kantor.latitude);
        const lng = Number(kantor.longitude);

        if (Number.isFinite(lat) && Number.isFinite(lng)) {
            if (!isCoordinateInIndonesia(lat, lng)) {
                return;
            }
            // Determine marker color class based on kantor type
            let markerClass = 'marker-gudang'; // Default

            // Check if jenis exists (data comes as 'jenis' from backend)
            if (kantor.jenis) {
                const jenisLower = kantor.jenis.toLowerCase();
                const normalizedJenis = jenisLower === 'kp' ? 'perwakilan' : jenisLower;
                
                switch (normalizedJenis) {
                    case 'pusat':
                        markerClass = 'marker-pusat';
                        break;
                    case 'sbu':
                        markerClass = 'marker-sbu';
                        break;
                    case 'perwakilan':
                        markerClass = 'marker-perwakilan';
                        break;
                    case 'gudang':
                        markerClass = 'marker-gudang';
                        break;
                    default:
                        markerClass = 'marker-gudang'; // Default
                        break;
                }
            }

            // Create custom colored marker icons based on kantor type
            let iconUrl = 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-icon.png';
            
            // Use different colored marker icons based on type
            switch (markerClass) {
                case 'marker-pusat':
                    // Red marker for PUSAT
                    iconUrl = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png';
                    break;
                case 'marker-sbu':
                    // Blue marker for SBU
                    iconUrl = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-blue.png';
                    break;
                case 'marker-perwakilan':
                    // Orange marker for PERWAKILAN
                    iconUrl = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-orange.png';
                    break;
                case 'marker-gudang':
                    // Dark blue marker for GUDANG
                    iconUrl = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-violet.png';
                    break;
            }
            
            const customIcon = L.icon({
                iconUrl: iconUrl,
                shadowUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });
            
            const marker = L.marker([lat, lng], {
                icon: customIcon
            })
                .addTo(map)
                .bindPopup(createEnhancedPopup(kantor), {
                    maxWidth: 400,
                    className: 'custom-popup',
                    offset: [0, -10],
                    autoPan: true,
                    keepInView: true
                })
                .on('popupopen', function() {
                    // Load employee data when popup opens
                    loadEmployeeData(kantor.id);
                    // Load expiring contracts badge
                    loadExpiringBadge(kantor.id);
                    updateDetailPanel(kantor);
                });
            
            // Add click event to marker
            marker.on('click', function(e) {
                // Update detail panel if function exists
                if (typeof updateDetailPanel === 'function') {
                    updateDetailPanel(kantor);
                } else {
                    // Fallback to just open popup
                    marker.openPopup();
                }
            });
            
            markers.push(marker);
            markerCount++;
            console.log(`Marker ${markerCount} added for:`, kantor.nama_kantor);
        }
    });
    
    // Fit map to show all markers
    if (markerCount > 0) {
        focusAllMarkers();
    }
}

// Create enhanced popup with tabs
function createEnhancedPopup(kantor) {
    return `
        <div class="popup-container">
            <div class="popup-header">
                <h6 class="fw-bold mb-2 text-white">${kantor.nama_kantor}</h6>
                <p class="mb-2 text-white-50 small"><i class="fas fa-map-marker-alt me-1"></i> ${kantor.alamat}</p>
                <div class="employee-summary mb-2" id="employee-summary-${kantor.id}">
                    <div class="text-center py-2">
                        <div class="spinner-border spinner-border-sm text-white" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <small class="text-white-50 ms-2">Memuat data pegawai...</small>
                    </div>
                </div>
                <div id="expiring-badge-${kantor.id}" class="mb-2" style="display:none"></div>
                <p class="mb-1 text-white-50 text-center"><small>${kantor.kota} - ${kantor.jenis}</small></p>
            </div>
            
            <!-- Tab Navigation -->
            <ul class="nav nav-tabs nav-sm" id="popupTabs-${kantor.id}" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="umum-tab-${kantor.id}" data-bs-toggle="tab" 
                            data-bs-target="#umum-${kantor.id}" type="button" role="tab">
                        <i class="fas fa-info-circle me-1"></i>Umum
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="inventaris-tab-${kantor.id}" data-bs-toggle="tab" 
                            data-bs-target="#inventaris-${kantor.id}" type="button" role="tab"
                            onclick="if (!window.inventarisLoaded_${kantor.id}) { loadInventaris(${kantor.id}); window.inventarisLoaded_${kantor.id} = true; }">
                        <i class="fas fa-box me-1"></i>Inventaris
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="kontrak-tab-${kantor.id}" data-bs-toggle="tab" 
                            data-bs-target="#kontrak-${kantor.id}" type="button" role="tab"
                            onclick="if (!window.kontrakLoaded_${kantor.id}) { loadKontrak(${kantor.id}); window.kontrakLoaded_${kantor.id} = true; }">
                        <i class="fas fa-file-contract me-1"></i>Kontrak
                        <span id="kontrak-expiring-count-${kantor.id}" class="badge bg-danger ms-1" style="display:none">0</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="laporan-tab-${kantor.id}" data-bs-toggle="tab" 
                            data-bs-target="#laporan-${kantor.id}" type="button" role="tab">
                        <i class="fas fa-chart-bar me-1"></i>Laporan Inventaris
                    </button>
                </li>
            </ul>
            
            <!-- Tab Content -->
            <div class="tab-content" id="popupTabContent-${kantor.id}">
                <!-- Umum Tab -->
                <div class="tab-pane fade show active" id="umum-${kantor.id}" role="tabpanel">
                    <div class="popup-body">
                        <!-- Location Info -->
                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke="currentColor" class="icon icon-18 text-primary me-2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                                    </svg>
                                    <small><strong>Kota:</strong> ${kantor.kota}</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke="currentColor" class="icon icon-18 text-info me-2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                                    </svg>
                                    <small><strong>Jenis:</strong> ${kantor.jenis}</small>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            <span class="badge bg-warning-subtle text-warning-emphasis fw-semibold px-3 py-2">
                                <i class="fas fa-key me-2"></i>Status: ${formatOwnership(kantor.status_kepemilikan)}
                            </span>
                            ${kantor.daya_listrik_va ? `
                            <span class="badge bg-success-subtle text-success-emphasis fw-semibold px-3 py-2">
                                <i class="fas fa-bolt me-2"></i>Daya: ${kantor.daya_listrik_va} VA
                            </span>` : ''}
                            ${kantor.kapasitas_genset_kva ? `
                            <span class="badge bg-info-subtle text-info-emphasis fw-semibold px-3 py-2">
                                <i class="fas fa-bolt me-2"></i>Genset: ${kantor.kapasitas_genset_kva} kVA
                            </span>` : ''}
                            ${kantor.jumlah_sumur ? `
                            <span class="badge bg-primary-subtle text-primary-emphasis fw-semibold px-3 py-2">
                                <i class="fas fa-tint me-2"></i>Sumur: ${kantor.jumlah_sumur}
                            </span>` : ''}
                            ${kantor.jumlah_septictank ? `
                            <span class="badge bg-warning-subtle text-warning-emphasis fw-semibold px-3 py-2">
                                <i class="fas fa-toilet me-2"></i>Septik: ${kantor.jumlah_septictank}
                            </span>` : ''}
                            <span class="badge bg-secondary-subtle text-secondary-emphasis fw-semibold px-3 py-2">
                                <i class="fas fa-wallet me-2"></i>Kepemilikan: ${formatOwnership(kantor.jenis_kepemilikan || 'Tidak Diketahui')}
                            </span>
                        </div>
                        <div class="row g-2 mt-2">
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-vector-square text-primary me-2"></i>
                                    <small><strong>Luas Tanah:</strong> ${formatArea(kantor.luas_tanah)}</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-building text-primary me-2"></i>
                                    <small><strong>Luas Bangunan:</strong> ${formatArea(kantor.luas_bangunan)}</small>
                                </div>
                            </div>
                        </div>
                        ${kantor.layout_url ? `
                            <div class="mt-2">
                                <a href="${kantor.layout_url}" target="_blank" class="btn btn-outline-info btn-sm w-100">
                                    <i class="fas fa-file-image me-1"></i> Lihat Layout Gedung
                                </a>
                            </div>
                        ` : ''}

                        <div class="mt-2">
                            <a href="https://www.google.com/maps?q=${kantor.latitude},${kantor.longitude}" 
                               target="_blank" class="btn btn-sm btn-primary w-100">
                                <i class="fas fa-directions me-1"></i>Petunjuk Arah
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Inventaris Tab -->
                <div class="tab-pane fade" id="inventaris-${kantor.id}" role="tabpanel">
                    <div class="popup-body">
                        <div class="text-center py-3">
                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mb-0 mt-2 loading-text">Memuat data inventaris...</p>
                        </div>
                    </div>
                </div>
                
                <!-- Kontrak Tab -->
                <div class="tab-pane fade" id="kontrak-${kantor.id}" role="tabpanel">
                    <div class="popup-body">
                        <div class="text-center py-3">
                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mb-0 mt-2 loading-text">Memuat data kontrak...</p>
                        </div>
                    </div>
                </div>
                
                        <!-- Laporan Inventaris Tab -->
                        <div class="tab-pane fade" id="laporan-${kantor.id}" role="tabpanel">
                            <div class="popup-body">
                                <div class="row g-2 mb-3">
                                    <div class="col-12">
                                        <div class="d-flex align-items-center justify-content-between p-2 bg-light rounded">
                                            <div>
                                                <i class="fas fa-chart-bar text-primary me-2"></i>
                                                <strong>Laporan Inventaris</strong>
                                            </div>
                                            <span class="badge bg-info">
                                                <i class="fas fa-filter me-1"></i>Filter Tersedia
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button class="btn btn-primary btn-sm w-100" onclick="openLaporanModal(${kantor.id})">
                                        <i class="fas fa-expand me-1"></i>Buka Laporan Inventaris
                                    </button>
                                </div>
                            </div>
                        </div>
            </div>
        </div>
    `;
}

// Load inventaris data
function loadInventaris(kantorId) {
    const tabContent = document.getElementById(`inventaris-${kantorId}`);
    if (!tabContent) return;
    
    // Show loading state
    tabContent.innerHTML = `
        <div class="text-center py-3">
            <div class="spinner-border spinner-border-sm text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mb-0 mt-2 text-muted small">Memuat data inventaris...</p>
        </div>
    `;
    
    fetch(`{{ url('/api/inventaris') }}/${kantorId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (!data || data.length === 0) {
                tabContent.innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-box text-muted fa-3x mb-3"></i>
                        <p class="text-muted mb-0">Tidak ada data inventaris</p>
                    </div>
                `;
            } else {
                const kategoriCount = new Set(data.map(i => i.kategori)).size;
                let html = `
                    <div class="popup-body">
                        <div class="row g-2 mb-3">
                            <div class="col-12">
                                <div class="d-flex align-items-center justify-content-between p-2 bg-light rounded">
                                    <div>
                                        <i class="fas fa-box text-primary me-2"></i>
                                        <strong>Total Inventaris:</strong> ${data.length} item
                                    </div>
                                    <span class="badge bg-info">${kategoriCount} Kategori</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button class="btn btn-primary btn-sm w-100" onclick="openInventarisModal(${kantorId})">
                                <i class="fas fa-expand me-1"></i>Buka Data Inventaris
                            </button>
                        </div>
                    </div>
                `;
                tabContent.innerHTML = html;
            }
        })
        .catch(error => {
            console.error('Error loading inventaris:', error);
            tabContent.innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-exclamation-triangle text-danger fa-3x mb-3"></i>
                    <p class="text-danger mb-1 fw-bold">Gagal memuat data inventaris</p>
                    <p class="text-muted small mb-0">Silakan refresh halaman atau coba lagi</p>
                </div>
            `;
        });
}

// Load kontrak data
function loadKontrak(kantorId) {
    const tabContent = document.getElementById(`kontrak-${kantorId}`);
    if (!tabContent) return;
    
    // Show loading state
    tabContent.innerHTML = `
        <div class="text-center py-3">
            <div class="spinner-border spinner-border-sm text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mb-0 mt-2 text-muted small">Memuat data kontrak...</p>
        </div>
    `;
    
    fetch(`{{ url('/api/kontrak') }}/${kantorId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            // Handle error response
            if (data.error) {
                throw new Error(data.message || 'Gagal memuat data kontrak');
            }
            
            if (!data || data.length === 0) {
                tabContent.innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-file-contract text-muted fa-3x mb-3"></i>
                        <p class="text-muted mb-0">Tidak ada data kontrak</p>
                    </div>
                `;
            } else {
                const aktifCount = data.filter(k => k.status === 'Aktif').length;
                const totalCount = data.length;
                
                let html = `
                    <div class="popup-body">
                        <div class="row g-2 mb-3">
                            <div class="col-12">
                                <div class="d-flex align-items-center justify-content-between p-2 bg-light rounded">
                                    <div>
                                        <i class="fas fa-file-contract text-primary me-2"></i>
                                        <strong>Total Kontrak:</strong> ${totalCount} item
                                    </div>
                                    <span class="badge bg-success">${aktifCount} Aktif</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button class="btn btn-primary btn-sm w-100" onclick="openKontrakModal(${kantorId})">
                                <i class="fas fa-expand me-1"></i>Buka Data Kontrak
                            </button>
                        </div>
                    </div>
                `;
                tabContent.innerHTML = html;
            }
        })
        .catch(error => {
            console.error('Error loading kontrak:', error);
            tabContent.innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-exclamation-triangle text-danger fa-3x mb-3"></i>
                    <p class="text-danger mb-1 fw-bold">Gagal memuat data kontrak</p>
                    <p class="text-muted small mb-0">Silakan refresh halaman atau coba lagi</p>
                </div>
            `;
        });
}

// Open Inventaris Modal
function openInventarisModal(kantorId) {
    // Create modal HTML
    const modalHTML = `
        <div class="laporan-modal show" id="inventarisModal-${kantorId}">
            <div class="laporan-modal-content">
                <div class="laporan-modal-header">
                    <h5 class="mb-0">Data Inventaris</h5>
                    <button class="laporan-modal-close" onclick="closeInventarisModal(${kantorId})">&times;</button>
                </div>
                <div class="laporan-modal-body">
                    <div id="inventarisModalContent-${kantorId}">
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mb-0 mt-3">Memuat data inventaris...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    // Load inventaris data
    loadInventarisModal(kantorId);
}

// Close Inventaris Modal
function closeInventarisModal(kantorId) {
    const modal = document.getElementById(`inventarisModal-${kantorId}`);
    if (modal) {
        modal.remove();
    }
}

// Load Inventaris in Modal
function loadInventarisModal(kantorId) {
    const modalContent = document.getElementById(`inventarisModalContent-${kantorId}`);
    
    // Fetch data
    fetch(`{{ url('/api/inventaris') }}/${kantorId}`)
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) {
                modalContent.innerHTML = `
                    <div class="text-center py-5">
                        <i class="fas fa-inbox text-muted" style="font-size: 3rem;"></i>
                        <p class="mb-0 mt-3 text-muted">Tidak ada data inventaris</p>
                    </div>
                `;
        return;
    }
    
            // Build inventaris HTML
            let inventarisHTML = `
                <div class="inventaris-container">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-primary">
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Kode</th>
                                    <th>Kategori</th>
                                    <th>Jumlah</th>
                                    <th>Kondisi</th>
                                    <th>Lokasi</th>
                                </tr>
                            </thead>
                            <tbody>
            `;
            
            data.forEach(item => {
                const kondisiClass = getKondisiClass(item.kondisi);
                inventarisHTML += `
                    <tr>
                        <td><strong>${item.nama_barang}</strong></td>
                        <td><span class="badge bg-info">${item.kode_inventaris}</span></td>
                        <td><span class="badge bg-secondary">${item.kategori}</span></td>
                        <td><span class="badge bg-primary">${item.jumlah} unit</span></td>
                        <td><span class="badge ${kondisiClass}">${item.kondisi}</span></td>
                        <td><small>${item.lokasi_gedung || '-'}${item.lokasi_lantai ? ' - ' + item.lokasi_lantai : ''}${item.lokasi_lantai_nomor ? ' (Lantai ' + item.lokasi_lantai_nomor + ')' : ''}${item.lokasi_ruang ? ' - ' + item.lokasi_ruang : ''}</small></td>
                    </tr>
                `;
            });
            
            inventarisHTML += `
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
            
            modalContent.innerHTML = inventarisHTML;
        })
        .catch(error => {
            console.error('Error loading inventaris:', error);
            modalContent.innerHTML = `
                <div class="text-center py-5">
                    <i class="fas fa-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                    <p class="mb-0 mt-3 text-danger">Gagal memuat data inventaris</p>
                </div>
            `;
        });
}

// Open Kontrak Modal
function openKontrakModal(kantorId) {
    // Create modal HTML
    const modalHTML = `
        <div class="laporan-modal show" id="kontrakModal-${kantorId}">
            <div class="laporan-modal-content">
                <div class="laporan-modal-header">
                    <h5 class="mb-0">Data Kontrak</h5>
                    <button class="laporan-modal-close" onclick="closeKontrakModal(${kantorId})">&times;</button>
                </div>
                <div class="laporan-modal-body">
                    <div id="kontrakModalContent-${kantorId}">
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mb-0 mt-3">Memuat data kontrak...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    // Load kontrak data
    loadKontrakModal(kantorId);
}

// Close Kontrak Modal
function closeKontrakModal(kantorId) {
    const modal = document.getElementById(`kontrakModal-${kantorId}`);
    if (modal) {
        modal.remove();
    }
}

// Load Kontrak in Modal
function loadKontrakModal(kantorId) {
    const modalContent = document.getElementById(`kontrakModalContent-${kantorId}`);
    
    // Fetch data
    fetch(`{{ url('/api/kontrak') }}/${kantorId}`)
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) {
                modalContent.innerHTML = `
                    <div class="text-center py-5">
                        <i class="fas fa-inbox text-muted" style="font-size: 3rem;"></i>
                        <p class="mb-0 mt-3 text-muted">Tidak ada data kontrak</p>
                    </div>
                `;
                return;
            }
            
            // Sort data by tanggal_mulai (newest first)
            data.sort((a, b) => {
                const dateA = a.tanggal_mulai ? new Date(a.tanggal_mulai.split('/').reverse().join('-')) : new Date(0);
                const dateB = b.tanggal_mulai ? new Date(b.tanggal_mulai.split('/').reverse().join('-')) : new Date(0);
                return dateB - dateA;
            });
            
            // Build kontrak HTML
            let kontrakHTML = `
                <div class="kontrak-container">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">
                            <i class="fas fa-file-contract me-2"></i>Data Kontrak (${data.length} item)
                        </h6>
                        <button class="btn btn-success" onclick="exportKontrakToExcel(${kantorId})">
                            <i class="fas fa-file-excel me-1"></i>Export Excel
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-primary">
                                <tr>
                                    <th>Nama Perjanjian</th>
                                    <th>No Perjanjian Pihak 1</th>
                                    <th>No Perjanjian Pihak 2</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Durasi</th>
                                    <th>Nilai Kontrak</th>
                                    <th>Status Perjanjian</th>
                                    <th>Status</th>
                                    <th>Asset Owner</th>
                                    <th>Parent Kantor</th>
                                    <th>Ruang Lingkup</th>
                                    <th>Peruntukan Kantor</th>
                                    <th>Alamat</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
            `;
            
            data.forEach(kontrak => {
                const statusClass = getStatusClass(kontrak.status_perjanjian);
                const statusKontrakClass = getStatusKontrakClass(kontrak.status);
                const days = (typeof kontrak.days_to_end === 'number') ? kontrak.days_to_end : null;
                let chipClass = 'bg-info';
                if (days !== null) {
                    if (days <= 31) chipClass = 'bg-danger';
                    else if (days <= 92) chipClass = 'bg-warning text-dark';
                }
                kontrakHTML += `
                    <tr>
                        <td><strong>${kontrak.nama_perjanjian}</strong></td>
                        <td><span class="badge bg-primary">${kontrak.no_perjanjian_pihak_1 || '-'}</span></td>
                        <td><span class="badge bg-primary">${kontrak.no_perjanjian_pihak_2 || '-'}</span></td>
                        <td><small>${kontrak.tanggal_mulai || '-'}</small></td>
                        <td>
                            <small>${kontrak.tanggal_selesai || '-'}</small>
                            ${days !== null ? `<span class="badge ${chipClass} ms-1">Sisa ${days} hari</span>` : ''}
                        </td>
                        <td><span class="badge bg-secondary">${kontrak.durasi_hari || 0} hari</span></td>
                        <td><strong class="text-success">${kontrak.nilai_kontrak || '-'}</strong></td>
                        <td><span class="badge ${statusClass}">${kontrak.status_perjanjian || '-'}</span></td>
                        <td><span class="badge ${statusKontrakClass}">${kontrak.status || '-'}</span></td>
                        <td><span class="badge bg-success">${kontrak.asset_owner || '-'}</span></td>
                        <td><span class="badge bg-info">${kontrak.parent_kantor || '-'}</span></td>
                        <td><small>${kontrak.ruang_lingkup || '-'}</small></td>
                        <td><small>${kontrak.peruntukan_kantor || '-'}</small></td>
                        <td><small>${kontrak.alamat || '-'}</small></td>
                        <td><small>${kontrak.keterangan || '-'}</small></td>
                    </tr>
                `;
            });
            
            kontrakHTML += `
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
            
            modalContent.innerHTML = kontrakHTML;
        })
        .catch(error => {
            console.error('Error loading kontrak:', error);
            modalContent.innerHTML = `
                <div class="text-center py-5">
                    <i class="fas fa-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                    <p class="mb-0 mt-3 text-danger">Gagal memuat data kontrak</p>
                </div>
            `;
        });
}

// Update detail panel function
function updateDetailPanel(kantor) {
    // Find detail panel elements
    const detailPanel = document.querySelector('.detail-panel');
    if (!detailPanel) return;
    
    // Update panel content
    const title = detailPanel.querySelector('.detail-title');
    const alamat = detailPanel.querySelector('.detail-alamat');
    const kota = detailPanel.querySelector('.detail-kota');
    const jenis = detailPanel.querySelector('.detail-jenis');
    const status = detailPanel.querySelector('.detail-status');
    const dayaListrik = detailPanel.querySelector('.detail-daya-listrik');
    const kapasitasGenset = detailPanel.querySelector('.detail-kapasitas-genset');
    const jumlahSumur = detailPanel.querySelector('.detail-jumlah-sumur');
    const jumlahSeptik = detailPanel.querySelector('.detail-jumlah-septik');
    
    // Update values
    if (title) title.textContent = kantor.nama_kantor || 'Nama Kantor';
    if (alamat) alamat.textContent = kantor.alamat || 'Alamat tidak tersedia';
    if (kota) kota.textContent = kantor.kota || 'Kota tidak tersedia';
    if (jenis) jenis.textContent = kantor.jenis || 'Jenis tidak tersedia';
    if (status) status.textContent = formatOwnership(kantor.status_kepemilikan) || 'Status tidak tersedia';
    if (dayaListrik) dayaListrik.textContent = kantor.daya_listrik_va ? `${kantor.daya_listrik_va} VA` : 'Tidak ada data';
    if (kapasitasGenset) kapasitasGenset.textContent = kantor.kapasitas_genset_kva ? `${kantor.kapasitas_genset_kva} kVA` : 'Tidak ada data';
    if (jumlahSumur) jumlahSumur.textContent = kantor.jumlah_sumur || '0';
    if (jumlahSeptik) jumlahSeptik.textContent = kantor.jumlah_septictank || '0';
    
    // Show the panel
    detailPanel.classList.add('show');
}

// Helper functions
function getKondisiClass(kondisi) {
    const classes = {
        'Baru': 'bg-success',
        'Baik': 'bg-primary',
        'Rusak Ringan': 'bg-warning',
        'Rusak Berat': 'bg-danger'
    };
    return classes[kondisi] || 'bg-secondary';
}

function getStatusClass(status) {
    const classes = {
        'Baru': 'bg-success',
        'Amandemen': 'bg-warning'
    };
    return classes[status] || 'bg-secondary';
}

function getStatusKontrakClass(status) {
    const classes = {
        'Aktif': 'bg-success',
        'Tidak Aktif': 'bg-warning',
        'Batal': 'bg-danger'
    };
    return classes[status] || 'bg-secondary';
}

function formatOwnership(status) {
    if (!status) return 'Tidak Diketahui';
    const statusMap = {
        'milik_sendiri': 'Milik Sendiri',
        'sewa': 'Sewa',
        'pinjam_pakai': 'Pinjam Pakai',
        'milik': 'Milik Sendiri',
        'sewa_menyewa': 'Sewa',
        'pinjam': 'Pinjam Pakai'
    };
    return statusMap[status.toLowerCase()] || status;
}

// Enhanced search function
function searchKantor() {
    const searchTerm = document.getElementById('searchBox').value.toLowerCase();
    const filterKota = document.getElementById('filterKota').value;
    const filterJenis = document.getElementById('filterJenis').value;
    
    let filteredData = searchResults;
    
    if (searchTerm) {
        filteredData = filteredData.filter(kantor => 
        kantor.nama_kantor.toLowerCase().includes(searchTerm) ||
        kantor.alamat.toLowerCase().includes(searchTerm) ||
        kantor.kota.toLowerCase().includes(searchTerm) ||
        kantor.jenis.toLowerCase().includes(searchTerm)
    );
    }
    
    if (filterKota) {
        filteredData = filteredData.filter(kantor => kantor.kota === filterKota);
    }
    
    if (filterJenis) {
        filteredData = filteredData.filter(kantor => kantor.jenis === filterJenis);
    }
    
    // Update map with filtered results
    addMarkersToMap(filteredData);
    
    updateStatistics(filteredData);

    // Show search results count
    if (filteredData.length === 0) {
        showToast.warning('Tidak ada kantor yang ditemukan dengan filter yang dipilih');
    } else {
        showToast.success(`Ditemukan ${filteredData.length} kantor`);
    }
}

// Clear filters
function clearFilters() {
    document.getElementById('searchBox').value = '';
    document.getElementById('filterKota').value = '';
    document.getElementById('filterJenis').value = '';
    addMarkersToMap(searchResults);
    updateStatistics(searchResults);
    showToast.info('Filter telah direset');
}

// Show all markers
function showAllMarkers() {
    addMarkersToMap(searchResults);
    updateStatistics(searchResults);
    showToast.info('Menampilkan semua kantor');
}

function initMapControls() {
    const mapActionButtons = document.querySelectorAll('[data-map-action]');
    mapActionButtons.forEach(button => {
        button.addEventListener('click', event => {
            event.preventDefault();
            const action = button.getAttribute('data-map-action');
            handleMapAction(action);
        });
    });

    const panelToggleButtons = document.querySelectorAll('[data-panel-toggle]');
    panelToggleButtons.forEach(button => {
        button.addEventListener('click', event => {
            event.preventDefault();
            toggleDashboardPanel();
        });
    });
}

function handleMapAction(action) {
    if (!map) {
        return;
    }

    switch (action) {
        case 'zoom-in':
            map.zoomIn();
            break;
        case 'zoom-out':
            map.zoomOut();
            break;
        case 'fit':
            focusAllMarkers();
            break;
        default:
            break;
    }
}

function toggleDashboardPanel(forceState = null) {
    const panel = document.querySelector('.peta-dashboard-panel');
    if (!panel) {
        return;
    }

    const shouldCollapse = forceState !== null ? forceState : !panel.classList.contains('collapsed');
    panel.classList.toggle('collapsed', shouldCollapse);

    updatePanelCollapseIcon(shouldCollapse);
    localStorage.setItem(PANEL_STATE_KEY, shouldCollapse ? 'collapsed' : 'expanded');

    if (map) {
        setTimeout(() => map.invalidateSize(), 320);
    }
}

function updatePanelCollapseIcon(isCollapsed) {
    const collapseButtonIcon = document.querySelector('.panel-collapse i');
    if (collapseButtonIcon) {
        collapseButtonIcon.classList.toggle('fa-chevron-left', !isCollapsed);
        collapseButtonIcon.classList.toggle('fa-chevron-right', isCollapsed);
    }
}

function restorePanelState() {
    const savedState = localStorage.getItem(PANEL_STATE_KEY);
    if (!savedState) {
        updatePanelCollapseIcon(false);
        return;
    }
    toggleDashboardPanel(savedState === 'collapsed');
}

function clampMapToIndonesia() {
    if (!map) {
        return;
    }
    const bounds = getIndonesiaBounds();
    if (!bounds.contains(map.getCenter())) {
        map.panInsideBounds(bounds, { animate: true });
    }
}

function getIndonesiaBounds() {
    return L.latLngBounds(
        L.latLng(6.2744, 94.9724),   // Northwest corner
        L.latLng(-11.1085, 141.0213) // Southeast corner
    );
}

function isCoordinateInIndonesia(lat, lng) {
    return getIndonesiaBounds().contains([lat, lng]);
}

// Export kontrak to Excel
function exportKontrakToExcel(kantorId) {
    // Show loading state
    const exportBtn = event.target;
    const originalText = exportBtn.innerHTML;
    exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Exporting...';
    exportBtn.disabled = true;
    
    fetch(`{{ url('/api/kontrak') }}/${kantorId}`)
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) {
                showToast.warning('Tidak ada data kontrak untuk diexport');
                return;
            }
            
            // Create Excel with professional styling
            createStyledExcel(data, kantorId);
            
            showToast.success(`Berhasil mengexport ${data.length} data kontrak`);
        })
        .catch(error => {
            showToast.error('Gagal mengexport data kontrak');
            console.error('Export error:', error);
        })
        .finally(() => {
            // Reset button state
            exportBtn.innerHTML = originalText;
            exportBtn.disabled = false;
        });
}


// Create professional PLN Icon Plus Excel template
async function createStyledExcel(data, kantorId) {
    // Create workbook
    const workbook = new ExcelJS.Workbook();
    
    // ===== SHEET 1: REKAP KONTRAK KOMPENSASI NON TUNAI =====
    const mainSheet = workbook.addWorksheet('Rekap Kontrak Kompensasi Non Tunai');
    
    // Define headers sesuai dengan data admin
    const headers = [
        'No',
        'Nama Perjanjian',
        'Kantor',
        'Tanggal Mulai',
        'Tanggal Selesai',
        'Nilai Kontrak',
        'Status Perjanjian',
        'Status',
        'No Perjanjian Pihak 1',
        'No Perjanjian Pihak 2',
        'Asset Owner',
        'Parent Kantor',
        'Ruang Lingkup',
        'Peruntukan Kantor',
        'Alamat',
        'Keterangan'
    ];
    
    // Add title row with proper styling
    const titleRow = mainSheet.addRow(['REKONSILIASI KOMPENSASI NON TUNAI DAN TINDAKLANJUT PELAPORAN ATAS PINJAM PAKAI ASET PROPERTI OLEH PLN ICON PLUS SEMESTER 1 TAHUN 2025']);
    titleRow.height = 40;
    titleRow.getCell(1).font = { bold: true, size: 14, color: { argb: 'FF000000' } };
    titleRow.getCell(1).alignment = { horizontal: 'center', vertical: 'middle' };
    titleRow.getCell(1).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFFFFFF' } };
    mainSheet.mergeCells('A1:O1');
    
    // Subtitle row removed as requested by user
    
    // Add empty row
    mainSheet.addRow([]);
    
    // Add header row with dark blue styling
    const headerRow = mainSheet.addRow(headers);
    headerRow.height = 30;
    headerRow.eachCell((cell, colNumber) => {
        cell.font = { bold: true, color: { argb: 'FFFFFFFF' }, size: 11, name: 'Calibri' };
        cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF1E3A8A' } }; // Dark Blue
        cell.alignment = { horizontal: 'center', vertical: 'middle' };
        cell.border = {
            top: { style: 'thin', color: { argb: 'FF000000' } },
            left: { style: 'thin', color: { argb: 'FF000000' } },
            bottom: { style: 'thin', color: { argb: 'FF000000' } },
            right: { style: 'thin', color: { argb: 'FF000000' } }
        };
    });
    
    // Add data rows sesuai dengan data admin
    data.forEach((kontrak, rowIndex) => {
        const row = mainSheet.addRow([
            rowIndex + 1, // No
            kontrak.nama_perjanjian,
            kontrak.kantor,
            kontrak.tanggal_mulai,
            kontrak.tanggal_selesai,
            kontrak.nilai_kontrak,
            kontrak.status_perjanjian || 'Baru',
            kontrak.status || 'Aktif',
            kontrak.no_perjanjian_pihak_1,
            kontrak.no_perjanjian_pihak_2,
            kontrak.asset_owner,
            kontrak.parent_kantor,
            kontrak.ruang_lingkup || '-',
            kontrak.peruntukan_kantor || '-',
            kontrak.alamat || '-',
            kontrak.keterangan || '-'
        ]);
        
        // Style each cell in the row
        row.eachCell((cell, colNumber) => {
            // Base styling
            cell.font = { size: 10, name: 'Calibri', color: { argb: 'FF000000' } };
            cell.alignment = { horizontal: 'left', vertical: 'middle' };
            cell.border = {
                top: { style: 'thin', color: { argb: 'FF000000' } },
                left: { style: 'thin', color: { argb: 'FF000000' } },
                bottom: { style: 'thin', color: { argb: 'FF000000' } },
                right: { style: 'thin', color: { argb: 'FF000000' } }
            };
            
            // Special styling for specific columns
            if (colNumber === 6) { // Nilai Kontrak column
                cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFFE4B5' } }; // Light Orange
                cell.alignment = { horizontal: 'right', vertical: 'middle' };
            } else if (colNumber === 8) { // Status column
                cell.alignment = { horizontal: 'center', vertical: 'middle' };
            } else if (colNumber === 11) { // Asset Owner column
                cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF90EE90' } }; // Light Green
            } else {
                // Alternate row colors for other columns
                if (rowIndex % 2 === 0) {
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFFFFFF' } };
                } else {
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFF8FAFC' } };
                }
            }
            
            // Special formatting for specific columns
            if (colNumber === 6) { // Nilai Kontrak
                cell.numFmt = '"Rp" #,##0';
                cell.alignment = { horizontal: 'right', vertical: 'middle' };
            }
            
            if (colNumber === 1) { // No column
                cell.alignment = { horizontal: 'center', vertical: 'middle' };
            }
            
            // Status column highlighting
            if (colNumber === 10) { // Status column
                const status = kontrak.status_perjanjian;
                if (status === 'berjalan') {
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF10B981' } }; // Green
                    cell.font = { size: 11, name: 'Calibri', bold: true, color: { argb: 'FFFFFFFF' } };
                } else if (status === 'baru') {
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF60A5FA' } }; // Blue
                    cell.font = { size: 11, name: 'Calibri', bold: true, color: { argb: 'FFFFFFFF' } };
                } else if (status === 'selesai') {
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF9CA3AF' } }; // Gray
                    cell.font = { size: 11, name: 'Calibri', bold: true, color: { argb: 'FFFFFFFF' } };
                }
            }
        });
    });
    
    // Set column widths for better readability
    mainSheet.columns = [
        { width: 8 },   // No
        { width: 30 },  // Nama Perjanjian
        { width: 25 },  // Kantor
        { width: 18 },  // Tanggal Mulai
        { width: 18 },  // Tanggal Selesai
        { width: 20 },  // Nilai Kontrak
        { width: 18 },  // Status Perjanjian
        { width: 25 },  // No Perjanjian Pihak 1
        { width: 25 },  // No Perjanjian Pihak 2
        { width: 25 },  // Asset Owner
        { width: 20 },  // Parent Kantor
        { width: 30 },  // Ruang Lingkup
        { width: 20 },  // Peruntukan Kantor
        { width: 30 },  // Alamat
        { width: 35 }   // Keterangan
    ];
    
    // Freeze header row
    mainSheet.views = [{ state: 'frozen', ySplit: 3 }];
    
    // Remove autofilter to eliminate dropdown icons
    // mainSheet.autoFilter = 'A3:R' + (data.length + 3);
    
    // ===== SHEET TAMBAHAN DIHAPUS =====
    // User requested single sheet only (like inventaris)
    
    // Generate and download Excel file
    try {
        const buffer = await workbook.xlsx.writeBuffer();
        const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        const today = new Date().toISOString().split('T')[0].replace(/-/g, '');
        link.download = `Laporan_Data_Kontrak_${today}.xlsx`;
        link.click();
        URL.revokeObjectURL(url);
    } catch (error) {
        console.error('Error creating Excel file:', error);
        alert('Gagal membuat file Excel. Silakan coba lagi.');
    }
}

// Download CSV file (fallback)
function downloadCSV(csvContent, filename) {
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    
    if (link.download !== undefined) {
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', filename);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
}

// Load employee data from okupansi
function loadEmployeeData(kantorId) {
    const summaryElement = document.getElementById(`employee-summary-${kantorId}`);
    const detailsElement = document.getElementById(`employee-details-${kantorId}`);
    
    fetch(`{{ url('/api/pegawai') }}/${kantorId}`)
        .then(response => response.json())
        .then(data => {
            // Update summary in header
            if (summaryElement) {
                summaryElement.innerHTML = `
                    <div class="d-flex justify-content-center align-items-center gap-3 py-1">
                        <small class="text-white-50">
                            <i class="fas fa-user-tie me-1"></i><strong>${data.total_organik}</strong> Organik
                        </small>
                        <small class="text-white-50">
                            <i class="fas fa-user-clock me-1"></i><strong>${data.total_tad}</strong> TAD
                        </small>
                        <small class="text-white-50">
                            <i class="fas fa-user-edit me-1"></i><strong>${data.total_kontrak}</strong> Kontrak
                        </small>
                    </div>
                    <div class="text-center">
                        <strong class="text-white">Total: ${data.total_all} Pegawai</strong>
                    </div>
                `;
            }
            
            // Update details in Umum tab - keep it simple
            if (detailsElement) {
                detailsElement.innerHTML = ``; // Remove employee stats from Umum tab
            }
        })
        .catch(error => {
            console.error('Error loading employee data:', error);
            
            // Show error in summary
            if (summaryElement) {
                summaryElement.innerHTML = `
                    <div class="text-center">
                        <small class="text-white-50"><i class="fas fa-exclamation-triangle me-1"></i>Data pegawai tidak tersedia</small>
                    </div>
                `;
            }
            
            // Show error in details - keep it simple
            if (detailsElement) {
                detailsElement.innerHTML = ``; // Remove error from Umum tab
            }
        });
}

// Toggle search panel dengan scroll behavior yang benar
function toggleSearchPanel() {
    const panel = document.getElementById('searchPanel');
    const toggle = document.querySelector('.peta-search-toggle');
    
    if (panel.style.display === 'none' || panel.style.display === '') {
        panel.style.display = 'block';
        toggle.classList.add('active');
        
        // Pastikan panel tetap di posisi yang benar saat scroll
        updatePanelPosition();
        
        // Tambahkan event listener untuk scroll
        window.addEventListener('scroll', updatePanelPosition);
        window.addEventListener('resize', updatePanelPosition);
    } else {
        panel.style.display = 'none';
        toggle.classList.remove('active');
        
        // Hapus event listener saat panel ditutup
        window.removeEventListener('scroll', updatePanelPosition);
        window.removeEventListener('resize', updatePanelPosition);
    }
}

// Update posisi panel agar tetap nempel di peta
function updatePanelPosition() {
    const panel = document.getElementById('searchPanel');
    const toggle = document.querySelector('.peta-search-toggle');
    
    if (panel && panel.style.display === 'block') {
        const toggleRect = toggle.getBoundingClientRect();
        const mapContainer = document.querySelector('.peta-map-container');
        const mapRect = mapContainer.getBoundingClientRect();
        
        // Pastikan panel tetap dalam area peta
        if (toggleRect.top < mapRect.top || toggleRect.bottom > mapRect.bottom) {
            panel.style.display = 'none';
            toggle.classList.remove('active');
            window.removeEventListener('scroll', updatePanelPosition);
            window.removeEventListener('resize', updatePanelPosition);
        }
    }
}

// Initialize map when page loads
document.addEventListener('DOMContentLoaded', function() {
    initMap();
});

// Load expiring contracts badge and counts into popup header and tab
function loadExpiringBadge(kantorId) {
    const url = `{{ route('public.api.kontrak-expiring', ['kantorId' => 'KID']) }}`.replace('KID', encodeURIComponent(kantorId)) + '?window=all';
    fetch(url)
        .then(r => r.ok ? r.json() : null)
        .then(data => {
            if (!data || !data.success) return;

            const badgeEl = document.getElementById(`expiring-badge-${kantorId}`);
            const countEl = document.getElementById(`kontrak-expiring-count-${kantorId}`);

            const c = data.counts || { m6: 0, m3: 0, m1: 0 };

            // Hitung total unik berdasarkan ID agar tidak double count (1 bln ⊆ 3 bln ⊆ 6 bln)
            let total = 0;
            const groups = data.data || {};
            const idSet = new Set();
            ['m1','m3','m6'].forEach(key => {
                (groups[key] || []).forEach(item => {
                    if (item && item.id != null) idSet.add(item.id);
                });
            });
            total = idSet.size || Math.max(c.m6, c.m3, c.m1);

            if (badgeEl) {
                if (total > 0) {
                    badgeEl.style.display = 'block';
                    badgeEl.innerHTML = `
                        <span class="badge bg-info me-1">6 bln: ${c.m6 || 0}</span>
                        <span class="badge bg-warning text-dark me-1">3 bln: ${c.m3 || 0}</span>
                        <span class="badge bg-danger">1 bln: ${c.m1 || 0}</span>
                    `;
                } else {
                    badgeEl.style.display = 'none';
                }
            }

            if (countEl) {
                if (total > 0) {
                    countEl.style.display = 'inline-block';
                    countEl.textContent = total;
                } else {
                    countEl.style.display = 'none';
                }
            }
        })
        .catch(() => {});
}

// Search on Enter key
document.getElementById('searchBox').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        searchKantor();
    }
});

// Auto search on filter change
document.getElementById('filterKota').addEventListener('change', searchKantor);
document.getElementById('filterJenis').addEventListener('change', searchKantor);

// Load Laporan Inventaris Data
// Load Laporan Inventaris - REMOVED (using modal instead)

// Filter laporan inventaris - REMOVED (using modal instead)

// Helper function to get kondisi color
function getKondisiColor(kondisi) {
    const colors = {
        'Baru': 'success',
        'Baik': 'primary',
        'Rusak Ringan': 'warning',
        'Rusak Berat': 'danger'
    };
    return colors[kondisi] || 'secondary';
}

// Open Laporan Modal
function openLaporanModal(kantorId) {
    // Create modal HTML
    const modalHTML = `
        <div class="laporan-modal show" id="laporanModal-${kantorId}">
            <div class="laporan-modal-content">
                <div class="laporan-modal-header">
                    <h5 class="mb-0">Laporan Inventaris</h5>
                    <button class="laporan-modal-close" onclick="closeLaporanModal(${kantorId})">&times;</button>
                </div>
                <div class="laporan-modal-body">
                    <div id="laporanModalContent-${kantorId}">
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mb-0 mt-3">Memuat laporan inventaris...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    // Load laporan data
    loadLaporanInventarisModal(kantorId);
}

// Close Laporan Modal
function closeLaporanModal(kantorId) {
    const modal = document.getElementById(`laporanModal-${kantorId}`);
    if (modal) {
        modal.remove();
    }
}

// Load Laporan Inventaris in Modal
function loadLaporanInventarisModal(kantorId) {
    const modalContent = document.getElementById(`laporanModalContent-${kantorId}`);
    
    // Fetch data
    fetch(`{{ url('/api/laporan-inventaris') }}/${kantorId}`)
        .then(response => response.json())
        .then(data => {
            if (Object.keys(data.inventaris).length === 0) {
                modalContent.innerHTML = `
                    <div class="text-center py-5">
                        <i class="fas fa-inbox text-muted" style="font-size: 3rem;"></i>
                        <p class="mb-0 mt-3 text-muted">Tidak ada data inventaris</p>
                    </div>
                `;
                return;
            }
            
            // Build kategori options dynamically
            let kategoriOptions = '<option value="">Semua Kategori</option>';
            data.kategori_options.forEach(kategori => {
                kategoriOptions += `<option value="${kategori.id}">${kategori.nama_kategori}</option>`;
            });
            
            // Build barang options dynamically
            let barangOptions = '<option value="">Semua Barang</option>';
            data.barang_options.forEach(barang => {
                barangOptions += `<option value="${barang}">${barang}</option>`;
            });
            
            // Build laporan HTML
            let laporanHTML = `
                <div class="laporan-inventaris-container">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Filter Kategori:</label>
                            <select class="form-select" id="kategori-select-modal-${kantorId}" onchange="filterLaporanInventarisModal(${kantorId})">
                                ${kategoriOptions}
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Pilih Barang:</label>
                            <select class="form-select" id="search-barang-modal-${kantorId}" onchange="filterLaporanInventarisModal(${kantorId})">
                                ${barangOptions}
                            </select>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-primary">
                                <tr>
                                    <th>Lokasi</th>
                                    <th>Nama Barang</th>
                                    <th>Kode</th>
                                    <th>Jumlah</th>
                                    <th>Kondisi</th>
                                    <th>Merk</th>
                                    <th>Harga</th>
                                    <th>Tahun</th>
                                    <th>Tanggal Pembelian</th>
                                    <th>Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody id="laporan-table-modal-${kantorId}">
            `;
            
            // Sort inventaris by nomor_lantai
            const sortedLokasi = Object.keys(data.inventaris).sort((a, b) => {
                const itemA = data.inventaris[a][0];
                const itemB = data.inventaris[b][0];
                const nomorA = itemA && itemA.lantai_nomor !== null ? parseInt(itemA.lantai_nomor) : 9999;
                const nomorB = itemB && itemB.lantai_nomor !== null ? parseInt(itemB.lantai_nomor) : 9999;
                return nomorA - nomorB;
            });
            
            // Add data rows
            sortedLokasi.forEach(lokasi => {
                laporanHTML += `
                    <tr class="table-secondary">
                        <td colspan="10" class="fw-bold">${lokasi}</td>
                    </tr>
                `;
                
                data.inventaris[lokasi].forEach(item => {
                    laporanHTML += `
                        <tr>
                            <td>
                                <div>${item.ruang || '-'}</div>
                                <small class="text-muted">${item.lantai_label || ''}</small>
                            </td>
                            <td>${item.nama_barang}</td>
                            <td><span class="badge bg-info">${item.kode_inventaris}</span></td>
                            <td><span class="badge bg-secondary">${item.jumlah}</span></td>
                            <td>
                                <span class="badge bg-${getKondisiColor(item.kondisi)}">${item.kondisi}</span>
                            </td>
                            <td>${item.merk || '-'}</td>
                            <td>${item.harga ? 'Rp ' + new Intl.NumberFormat('id-ID').format(item.harga) : '-'}</td>
                            <td>${item.tahun || '-'}</td>
                            <td>${item.tanggal_pembelian ? new Date(item.tanggal_pembelian).toLocaleDateString('id-ID') : '-'}</td>
                            <td>${item.deskripsi || '-'}</td>
                        </tr>
                    `;
                });
            });
            
            laporanHTML += `
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4 text-center">
                        <button class="btn btn-success btn-lg" onclick="exportLaporanInventarisToExcelModal(${kantorId})">
                            <i class="fas fa-file-excel me-2"></i>Export Excel
                        </button>
                    </div>
                </div>
            `;
            
            modalContent.innerHTML = laporanHTML;
        })
        .catch(error => {
            console.error('Error loading laporan inventaris:', error);
            modalContent.innerHTML = `
                <div class="text-center py-5">
                    <i class="fas fa-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                    <p class="mb-0 mt-3 text-danger">Gagal memuat data laporan inventaris</p>
                </div>
            `;
        });
}

// Filter Laporan Inventaris in Modal
function filterLaporanInventarisModal(kantorId) {
    const kategoriId = document.getElementById(`kategori-select-modal-${kantorId}`).value;
    const searchBarang = document.getElementById(`search-barang-modal-${kantorId}`).value;
    
    // Fetch filtered data
    fetch(`{{ url('/api/laporan-inventaris') }}/${kantorId}?kategori_id=${kategoriId}&search_barang=${searchBarang}`)
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById(`laporan-table-modal-${kantorId}`);
            
            if (Object.keys(data.inventaris).length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="10" class="text-center py-4">
                            <i class="fas fa-inbox text-muted"></i>
                            <p class="mb-0 mt-2 text-muted">Tidak ada data yang sesuai dengan filter</p>
                        </td>
                    </tr>
                `;
                return;
            }
            
            // Sort inventaris by nomor_lantai
            const sortedLokasi = Object.keys(data.inventaris).sort((a, b) => {
                const itemA = data.inventaris[a][0];
                const itemB = data.inventaris[b][0];
                const nomorA = itemA && itemA.lantai_nomor !== null ? parseInt(itemA.lantai_nomor) : 9999;
                const nomorB = itemB && itemB.lantai_nomor !== null ? parseInt(itemB.lantai_nomor) : 9999;
                return nomorA - nomorB;
            });
            
            let tableHTML = '';
            sortedLokasi.forEach(lokasi => {
                tableHTML += `
                    <tr class="table-secondary">
                        <td colspan="10" class="fw-bold">${lokasi}</td>
                    </tr>
                `;
                
                data.inventaris[lokasi].forEach(item => {
                    tableHTML += `
                        <tr>
                            <td>
                                <div>${item.ruang || '-'}</div>
                                <small class="text-muted">${item.lantai_label || ''}</small>
                            </td>
                            <td>${item.nama_barang}</td>
                            <td><span class="badge bg-info">${item.kode_inventaris}</span></td>
                            <td><span class="badge bg-secondary">${item.jumlah}</span></td>
                            <td>
                                <span class="badge bg-${getKondisiColor(item.kondisi)}">${item.kondisi}</span>
                            </td>
                            <td>${item.merk || '-'}</td>
                            <td>${item.harga ? 'Rp ' + new Intl.NumberFormat('id-ID').format(item.harga) : '-'}</td>
                            <td>${item.tahun || '-'}</td>
                            <td>${item.tanggal_pembelian ? new Date(item.tanggal_pembelian).toLocaleDateString('id-ID') : '-'}</td>
                            <td>${item.deskripsi || '-'}</td>
                        </tr>
                    `;
                });
            });
            
            tableBody.innerHTML = tableHTML;
        })
        .catch(error => {
            console.error('Error filtering laporan inventaris:', error);
        });
}

// Export Laporan Inventaris to Excel from Modal
async function exportLaporanInventarisToExcelModal(kantorId) {
    const kategoriId = document.getElementById(`kategori-select-modal-${kantorId}`).value;
    const kategoriNama = document.getElementById(`kategori-select-modal-${kantorId}`).selectedOptions[0].text;
    const searchBarang = document.getElementById(`search-barang-modal-${kantorId}`).value;
    
    try {
        if (typeof ExcelJS === 'undefined') {
            alert('ExcelJS library tidak ter-load. Silakan refresh halaman.');
            return;
        }
        
        // Fetch data untuk export
        const response = await fetch(`{{ url('/api/laporan-inventaris') }}/${kantorId}?kategori_id=${kategoriId}&search_barang=${searchBarang}`);
        const data = await response.json();
        
        if (Object.keys(data.inventaris).length === 0) {
            alert('Tidak ada data untuk diexport');
            return;
        }
        
        // Create Excel workbook
        const workbook = new ExcelJS.Workbook();
        const worksheet = workbook.addWorksheet('Laporan Inventaris');
        
        // Set column widths
        worksheet.columns = [
            { width: 20 }, // Lokasi
            { width: 25 }, // Nama Barang
            { width: 15 }, // Kode Inventaris
            { width: 10 }, // Jumlah
            { width: 15 }, // Kondisi
            { width: 15 }, // Merk
            { width: 20 }, // Harga
            { width: 10 }, // Tahun
            { width: 15 }, // Tanggal Pembelian
            { width: 30 }  // Deskripsi
        ];
        
        // Title row
        let titleText = 'LAPORAN INVENTARIS';
        if (kategoriNama && kategoriNama !== 'Semua Kategori') {
            titleText += ' - ' + kategoriNama;
        }
        if (searchBarang && searchBarang !== 'Semua Barang') {
            titleText += ' - ' + searchBarang.toUpperCase();
        }
        
        const titleRow = worksheet.addRow([titleText]);
        titleRow.font = { bold: true, size: 16, color: { argb: 'FFFFFFFF' } };
        titleRow.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C6A8F' } };
        titleRow.alignment = { horizontal: 'center', vertical: 'middle' };
        worksheet.mergeCells('A1:J1');
        
        // Empty row
        worksheet.addRow([]);
        
        // Header row
        const headerRow = worksheet.addRow([
            'Lokasi', 'Nama Barang', 'Kode Inventaris', 'Jumlah', 'Kondisi', 'Merk', 'Harga', 'Tahun', 'Tanggal Pembelian', 'Deskripsi'
        ]);
        
        // Style header row
        headerRow.eachCell((cell, colNumber) => {
            cell.font = { bold: true, color: { argb: 'FFFFFFFF' } };
            cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C6A8F' } };
            cell.border = {
                top: { style: 'thin', color: { argb: 'FF000000' } },
                left: { style: 'thin', color: { argb: 'FF000000' } },
                bottom: { style: 'thin', color: { argb: 'FF000000' } },
                right: { style: 'thin', color: { argb: 'FF000000' } }
            };
            cell.alignment = { horizontal: 'center', vertical: 'middle' };
        });
        
        let rowIndex = 4; // Start from row 4 (after title, empty row, and header)
        
        // Add data rows
        Object.keys(data.inventaris).forEach(lokasi => {
            // Location header row
            const locationRow = worksheet.addRow([lokasi, '', '', '', '', '', '', '', '', '']);
            locationRow.eachCell((cell, colNumber) => {
                cell.font = { bold: true };
                cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFE6F3FF' } };
                cell.border = {
                    top: { style: 'thin', color: { argb: 'FF000000' } },
                    left: { style: 'thin', color: { argb: 'FF000000' } },
                    bottom: { style: 'thin', color: { argb: 'FF000000' } },
                    right: { style: 'thin', color: { argb: 'FF000000' } }
                };
            });
            worksheet.mergeCells(`A${rowIndex}:J${rowIndex}`);
            rowIndex++;
            
            // Data rows
            data.inventaris[lokasi].forEach((item, index) => {
                const dataRow = worksheet.addRow([
                    item.ruang,
                    item.nama_barang,
                    item.kode_inventaris,
                    item.jumlah,
                    item.kondisi,
                    item.merk || '-',
                    item.harga ? 'Rp ' + new Intl.NumberFormat('id-ID').format(item.harga) : '-',
                    item.tahun || '-',
                    item.tanggal_pembelian ? new Date(item.tanggal_pembelian).toLocaleDateString('id-ID') : '-',
                    item.deskripsi || '-'
                ]);
                
                // Style data row
                dataRow.eachCell((cell, colNumber) => {
                    cell.border = {
                        top: { style: 'thin', color: { argb: 'FF000000' } },
                        left: { style: 'thin', color: { argb: 'FF000000' } },
                        bottom: { style: 'thin', color: { argb: 'FF000000' } },
                        right: { style: 'thin', color: { argb: 'FF000000' } }
                    };
                    
                    // Set alignment dan warna berdasarkan kolom
                    if (colNumber === 1) { // Lokasi - Rata kiri
                        cell.alignment = { horizontal: 'left', vertical: 'middle' };
                        if (rowIndex % 2 === 0) {
                            cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFF8F9FA' } };
                        }
                    } else if (colNumber === 2) { // Nama Barang - Rata kiri
                        cell.alignment = { horizontal: 'left', vertical: 'middle' };
                        if (rowIndex % 2 === 0) {
                            cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFF8F9FA' } };
                        }
                    } else if (colNumber === 3) { // Kode Inventaris - Rata kiri
                        cell.alignment = { horizontal: 'left', vertical: 'middle' };
                        if (rowIndex % 2 === 0) {
                            cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFF8F9FA' } };
                        }
                    } else if (colNumber === 4) { // Jumlah - Rata tengah
                        cell.alignment = { horizontal: 'center', vertical: 'middle' };
                        cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFFF2CC' } };
                    } else if (colNumber === 5) { // Kondisi - Rata tengah
                        cell.alignment = { horizontal: 'center', vertical: 'middle' };
                        if (rowIndex % 2 === 0) {
                            cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFF8F9FA' } };
                        }
                    } else if (colNumber === 6) { // Merk - Rata kiri
                        cell.alignment = { horizontal: 'left', vertical: 'middle' };
                        if (rowIndex % 2 === 0) {
                            cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFF8F9FA' } };
                        }
                    } else if (colNumber === 7) { // Harga - Rata kanan
                        cell.alignment = { horizontal: 'right', vertical: 'middle' };
                        cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFE1F5FE' } };
                    } else if (colNumber === 8) { // Tahun - Rata tengah
                        cell.alignment = { horizontal: 'center', vertical: 'middle' };
                        if (rowIndex % 2 === 0) {
                            cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFF8F9FA' } };
                        }
                    } else if (colNumber === 9) { // Tanggal Pembelian - Rata tengah
                        cell.alignment = { horizontal: 'center', vertical: 'middle' };
                        if (rowIndex % 2 === 0) {
                            cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFF8F9FA' } };
                        }
                    } else if (colNumber === 10) { // Deskripsi - Rata kiri
                        cell.alignment = { horizontal: 'left', vertical: 'middle' };
                        if (rowIndex % 2 === 0) {
                            cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFF8F9FA' } };
                        }
                    }
                });
                rowIndex++;
            });
        });
        
        // Summary row
        const summaryRow = worksheet.addRow(['TOTAL', '', '', '', '', '', '', '', '', '']);
        summaryRow.eachCell((cell, colNumber) => {
            cell.font = { bold: true };
            cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C6A8F' } };
            cell.font = { bold: true, color: { argb: 'FFFFFFFF' } };
            cell.border = {
                top: { style: 'thin', color: { argb: 'FF000000' } },
                left: { style: 'thin', color: { argb: 'FF000000' } },
                bottom: { style: 'thin', color: { argb: 'FF000000' } },
                right: { style: 'thin', color: { argb: 'FF000000' } }
            };
        });
        worksheet.mergeCells(`A${rowIndex}:J${rowIndex}`);
        
        // Page setup
        worksheet.pageSetup = {
            paperSize: 9, // A4
            orientation: 'landscape',
            margins: {
                left: 0.7,
                right: 0.7,
                top: 0.75,
                bottom: 0.75,
                header: 0.3,
                footer: 0.3
            }
        };
        
        // Generate and download file
        const buffer = await workbook.xlsx.writeBuffer();
        const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
        const url = URL.createObjectURL(blob);
        
        const link = document.createElement('a');
        link.href = url;
        const today = new Date().toISOString().split('T')[0].replace(/-/g, '');
        let filename = 'Laporan_Inventaris';
        if (kategoriNama && kategoriNama !== 'Semua Kategori') {
            filename += '_' + kategoriNama;
        }
        if (searchBarang && searchBarang !== 'Semua Barang') {
            filename += '_' + searchBarang.replace(/\s+/g, '_');
        }
        filename += '_' + today + '.xlsx';
        link.download = filename;
        link.click();
        URL.revokeObjectURL(url);
        
    } catch (error) {
        console.error('Error creating Excel file:', error);
        alert('Gagal membuat file Excel. Silakan coba lagi.');
    }
}

// Export Laporan Inventaris to Excel
async function exportLaporanInventarisToExcel(kantorId) {
    const kategoriId = document.getElementById(`kategori-select-${kantorId}`).value;
    const kategoriNama = document.getElementById(`kategori-select-${kantorId}`).selectedOptions[0].text;
    const searchBarang = document.getElementById(`search-barang-${kantorId}`).value;
    
    try {
        if (typeof ExcelJS === 'undefined') {
            alert('ExcelJS library tidak ter-load. Silakan refresh halaman.');
            return;
        }
        
        // Fetch data untuk export
        const response = await fetch(`{{ url('/api/laporan-inventaris') }}/${kantorId}?kategori_id=${kategoriId}&search_barang=${searchBarang}`);
        const data = await response.json();
        
        if (Object.keys(data.inventaris).length === 0) {
            alert('Tidak ada data untuk diexport');
            return;
        }
        
        // Create Excel workbook
        const workbook = new ExcelJS.Workbook();
        const worksheet = workbook.addWorksheet('Laporan Inventaris');
        
        // Set column widths
        worksheet.columns = [
            { width: 20 }, // Lokasi
            { width: 25 }, // Nama Barang
            { width: 15 }, // Kode Inventaris
            { width: 10 }, // Jumlah
            { width: 15 }, // Kondisi
            { width: 15 }, // Merk
            { width: 20 }, // Harga
            { width: 10 }, // Tahun
            { width: 15 }, // Tanggal Pembelian
            { width: 30 }  // Deskripsi
        ];
        
        // Title row
        let titleText = 'LAPORAN INVENTARIS';
        if (kategoriNama && kategoriNama !== 'Semua Kategori') {
            titleText += ' - ' + kategoriNama;
        }
        if (searchBarang && searchBarang !== 'Semua Barang') {
            titleText += ' - ' + searchBarang.toUpperCase();
        }
        
        const titleRow = worksheet.addRow([titleText]);
        titleRow.font = { bold: true, size: 16, color: { argb: 'FFFFFFFF' } };
        titleRow.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C6A8F' } };
        titleRow.alignment = { horizontal: 'center', vertical: 'middle' };
        worksheet.mergeCells('A1:J1');
        
        // Empty row
        worksheet.addRow([]);
        
        // Header row
        const headerRow = worksheet.addRow([
            'Lokasi', 'Nama Barang', 'Kode Inventaris', 'Jumlah', 'Kondisi', 'Merk', 'Harga', 'Tahun', 'Tanggal Pembelian', 'Deskripsi'
        ]);
        
        // Style header row
        headerRow.eachCell((cell, colNumber) => {
            cell.font = { bold: true, color: { argb: 'FFFFFFFF' } };
            cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C6A8F' } };
            cell.border = {
                top: { style: 'thin', color: { argb: 'FF000000' } },
                left: { style: 'thin', color: { argb: 'FF000000' } },
                bottom: { style: 'thin', color: { argb: 'FF000000' } },
                right: { style: 'thin', color: { argb: 'FF000000' } }
            };
            cell.alignment = { horizontal: 'center', vertical: 'middle' };
        });
        
        let rowIndex = 4; // Start from row 4 (after title, empty row, and header)
        
        
        // Add data rows
        Object.keys(data.inventaris).forEach(lokasi => {
            
            // Location header row
            const locationRow = worksheet.addRow([lokasi, '', '', '', '', '', '', '', '', '']);
            locationRow.eachCell((cell, colNumber) => {
                cell.font = { bold: true };
                cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFE6F3FF' } };
                cell.border = {
                    top: { style: 'thin', color: { argb: 'FF000000' } },
                    left: { style: 'thin', color: { argb: 'FF000000' } },
                    bottom: { style: 'thin', color: { argb: 'FF000000' } },
                    right: { style: 'thin', color: { argb: 'FF000000' } }
                };
            });
            worksheet.mergeCells(`A${rowIndex}:J${rowIndex}`);
            rowIndex++;
            
            // Data rows
            data.inventaris[lokasi].forEach((item, index) => {
                const dataRow = worksheet.addRow([
                    item.ruang,
                    item.nama_barang,
                    item.kode_inventaris,
                    item.jumlah,
                    item.kondisi,
                    item.merk || '-',
                    item.harga ? 'Rp ' + new Intl.NumberFormat('id-ID').format(item.harga) : '-',
                    item.tahun || '-',
                    item.tanggal_pembelian ? new Date(item.tanggal_pembelian).toLocaleDateString('id-ID') : '-',
                    item.deskripsi || '-'
                ]);
                
                // Style data row
                dataRow.eachCell((cell, colNumber) => {
                    cell.border = {
                        top: { style: 'thin', color: { argb: 'FF000000' } },
                        left: { style: 'thin', color: { argb: 'FF000000' } },
                        bottom: { style: 'thin', color: { argb: 'FF000000' } },
                        right: { style: 'thin', color: { argb: 'FF000000' } }
                    };
                    
                    // Set alignment dan warna berdasarkan kolom
                    if (colNumber === 1) { // Lokasi - Rata kiri
                        cell.alignment = { horizontal: 'left', vertical: 'middle' };
                        if (rowIndex % 2 === 0) {
                            cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFF8F9FA' } };
                        }
                    } else if (colNumber === 2) { // Nama Barang - Rata kiri
                        cell.alignment = { horizontal: 'left', vertical: 'middle' };
                        if (rowIndex % 2 === 0) {
                            cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFF8F9FA' } };
                        }
                    } else if (colNumber === 3) { // Kode Inventaris - Rata kiri
                        cell.alignment = { horizontal: 'left', vertical: 'middle' };
                        if (rowIndex % 2 === 0) {
                            cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFF8F9FA' } };
                        }
                    } else if (colNumber === 4) { // Jumlah - Rata tengah
                        cell.alignment = { horizontal: 'center', vertical: 'middle' };
                        cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFFF2CC' } };
                    } else if (colNumber === 5) { // Kondisi - Rata tengah
                        cell.alignment = { horizontal: 'center', vertical: 'middle' };
                        if (rowIndex % 2 === 0) {
                            cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFF8F9FA' } };
                        }
                    } else if (colNumber === 6) { // Merk - Rata kiri
                        cell.alignment = { horizontal: 'left', vertical: 'middle' };
                        if (rowIndex % 2 === 0) {
                            cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFF8F9FA' } };
                        }
                    } else if (colNumber === 7) { // Harga - Rata kanan
                        cell.alignment = { horizontal: 'right', vertical: 'middle' };
                        cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFE1F5FE' } };
                    } else if (colNumber === 8) { // Tahun - Rata tengah
                        cell.alignment = { horizontal: 'center', vertical: 'middle' };
                        if (rowIndex % 2 === 0) {
                            cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFF8F9FA' } };
                        }
                    } else if (colNumber === 9) { // Tanggal Pembelian - Rata tengah
                        cell.alignment = { horizontal: 'center', vertical: 'middle' };
                        if (rowIndex % 2 === 0) {
                            cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFF8F9FA' } };
                        }
                    } else if (colNumber === 10) { // Deskripsi - Rata kiri
                        cell.alignment = { horizontal: 'left', vertical: 'middle' };
                        if (rowIndex % 2 === 0) {
                            cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFF8F9FA' } };
                        }
                    }
                });
                rowIndex++;
            });
        });
        
        // Summary row
        const summaryRow = worksheet.addRow(['TOTAL', '', '', '', '', '', '', '', '', '']);
        summaryRow.eachCell((cell, colNumber) => {
            cell.font = { bold: true };
            cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF2C6A8F' } };
            cell.font = { bold: true, color: { argb: 'FFFFFFFF' } };
            cell.border = {
                top: { style: 'thin', color: { argb: 'FF000000' } },
                left: { style: 'thin', color: { argb: 'FF000000' } },
                bottom: { style: 'thin', color: { argb: 'FF000000' } },
                right: { style: 'thin', color: { argb: 'FF000000' } }
            };
        });
        worksheet.mergeCells(`A${rowIndex}:J${rowIndex}`);
        
        // Page setup
        worksheet.pageSetup = {
            paperSize: 9, // A4
            orientation: 'landscape',
            margins: {
                left: 0.7,
                right: 0.7,
                top: 0.75,
                bottom: 0.75,
                header: 0.3,
                footer: 0.3
            }
        };
        
        // Generate and download file
        const buffer = await workbook.xlsx.writeBuffer();
        const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
        const url = URL.createObjectURL(blob);
        
        const link = document.createElement('a');
        link.href = url;
        const today = new Date().toISOString().split('T')[0].replace(/-/g, '');
        let filename = 'Laporan_Inventaris';
        if (kategoriNama && kategoriNama !== 'Semua Kategori') {
            filename += '_' + kategoriNama;
        }
        if (searchBarang && searchBarang !== 'Semua Barang') {
            filename += '_' + searchBarang.replace(/\s+/g, '_');
        }
        filename += '_' + today + '.xlsx';
        link.download = filename;
        link.click();
        URL.revokeObjectURL(url);
        
    } catch (error) {
        console.error('Error creating Excel file:', error);
        alert('Gagal membuat file Excel. Silakan coba lagi.');
    }
}

// Simple modal functions
function openSearchModal() {
    const modal = new bootstrap.Modal(document.getElementById('searchModal'));
    modal.show();
}

function closeSearchModal() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('searchModal'));
    if (modal) {
        modal.hide();
    }
}

// Update statistics
function updateStatistics(data) {
    const counts = {
        pusat: 0,
        sbu: 0,
        perwakilan: 0,
        gudang: 0
    };
    
    data.forEach(kantor => {
        if (kantor.jenis) {
            let jenis = kantor.jenis.toLowerCase();
            if (jenis === 'kp') {
                jenis = 'perwakilan';
            }
            if (counts.hasOwnProperty(jenis)) {
                counts[jenis]++;
            }
        }
    });
    
    // Update statistics cards
    document.getElementById('stat-pusat').textContent = counts.pusat;
    document.getElementById('stat-sbu').textContent = counts.sbu;
    document.getElementById('stat-perwakilan').textContent = counts.perwakilan;
    document.getElementById('stat-gudang').textContent = counts.gudang;

    const total = counts.pusat + counts.sbu + counts.perwakilan + counts.gudang;
    const totalElement = document.getElementById('stat-total');
    if (totalElement) {
        totalElement.textContent = total;
    }
}

function focusAllMarkers() {
    if (!map || markers.length === 0) {
        map.setView([INDONESIA_CENTER.lat, INDONESIA_CENTER.lng], 5);
        map.closePopup();
        return;
    }

    const group = L.featureGroup(markers);
    map.fitBounds(group.getBounds().pad(0.12));
    map.closePopup();
}

function toggleOverlay(type) {
    const overlay = document.querySelector(`.peta-overlay[data-overlay="${type}"]`);
    if (!overlay) return;

    overlay.classList.toggle('collapsed');
    const icon = overlay.querySelector('.peta-overlay-toggle i');
    if (icon) {
        icon.classList.toggle('fa-chevron-up');
        icon.classList.toggle('fa-chevron-down');
    }

    saveOverlayState();
}

function saveOverlayState() {
    const overlays = document.querySelectorAll('.peta-overlay[data-overlay]');
    const state = {};
    overlays.forEach((overlay) => {
        const key = overlay.getAttribute('data-overlay');
        state[key] = overlay.classList.contains('collapsed');
    });
    localStorage.setItem(overlayStateKey, JSON.stringify(state));
}

function restoreOverlayState() {
    try {
        const raw = localStorage.getItem(overlayStateKey);
        if (!raw) return;
        const state = JSON.parse(raw);
        Object.entries(state).forEach(([key, collapsed]) => {
            const overlay = document.querySelector(`.peta-overlay[data-overlay="${key}"]`);
            if (!overlay) return;
            if (collapsed) {
                overlay.classList.add('collapsed');
            }
            const icon = overlay.querySelector('.peta-overlay-toggle i');
            if (icon) {
                const shouldUp = overlay.classList.contains('collapsed');
                icon.classList.toggle('fa-chevron-up', shouldUp);
                icon.classList.toggle('fa-chevron-down', !shouldUp);
            }
        });
    } catch (error) {
        console.warn('Unable to restore overlay state:', error);
    }
}
</script>

@endpush
