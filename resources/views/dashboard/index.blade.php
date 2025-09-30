@extends('layouts.app')

@section('title', 'Dashboard - PLN Kantor Management')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Sistem Manajemen Kantor PLN Icon Plus')

@section('content')
<!-- Dashboard Content -->
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card stat-kantor">
                <div class="stat-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stat-content">
                    <h5 class="stat-number">{{ $totalKantor }}</h5>
                    <p class="stat-label">Total Kantor</p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card stat-gedung">
                <div class="stat-icon">
                    <i class="fas fa-home"></i>
                </div>
                <div class="stat-content">
                    <h5 class="stat-number">{{ $totalGedung }}</h5>
                    <p class="stat-label">Total Gedung</p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card stat-kontrak">
                <div class="stat-icon">
                    <i class="fas fa-file-contract"></i>
                </div>
                <div class="stat-content">
                    <h5 class="stat-number">{{ $totalKontrak }}</h5>
                    <p class="stat-label">Total Kontrak</p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card stat-okupansi">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h5 class="stat-number">{{ $totalOkupansi }}</h5>
                    <p class="stat-label">Total Okupansi</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Line Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="chart-card">
                <div class="chart-header">
                    <h6 class="chart-title">
                        <i class="fas fa-chart-line"></i>
                        Kontrak & Nilai per Bulan
                    </h6>
                </div>
                <div class="chart-body">
                    <div class="chart-container">
                        <canvas id="lineChart" data-kontrak="{{ json_encode($kontrakByMonth) }}"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Progress Bars -->
        <div class="col-xl-4 col-lg-5">
            <div class="status-card">
                <div class="status-header">
                    <h6 class="status-title">
                        <i class="fas fa-chart-bar"></i>
                        Status Kepemilikan Kantor & Gedung
                    </h6>
                </div>
                <div class="status-body">
                    <div class="status-cards">
                        <div class="status-item kantor-milik">
                            <div class="status-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="status-content">
                                <h3>Kantor Milik</h3>
                                <div class="status-number">{{ $statusStats['kantor_milik'] ?? 0 }}</div>
                                <div class="status-percentage">{{ 
                                    (($statusStats['kantor_milik'] ?? 0) + ($statusStats['kantor_sewa'] ?? 0)) > 0 
                                        ? round((($statusStats['kantor_milik'] ?? 0) / (($statusStats['kantor_milik'] ?? 0) + ($statusStats['kantor_sewa'] ?? 0))) * 100, 1) 
                                        : 0 
                                }}%</div>
                            </div>
                        </div>
                        
                        <div class="status-item kantor-sewa">
                            <div class="status-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="status-content">
                                <h3>Kantor Sewa</h3>
                                <div class="status-number">{{ $statusStats['kantor_sewa'] ?? 0 }}</div>
                                <div class="status-percentage">{{ 
                                    (($statusStats['kantor_milik'] ?? 0) + ($statusStats['kantor_sewa'] ?? 0)) > 0 
                                        ? round((($statusStats['kantor_sewa'] ?? 0) / (($statusStats['kantor_milik'] ?? 0) + ($statusStats['kantor_sewa'] ?? 0))) * 100, 1) 
                                        : 0 
                                }}%</div>
                            </div>
                        </div>
                        
                        <div class="status-item gedung-milik">
                            <div class="status-icon">
                                <i class="fas fa-home"></i>
                            </div>
                            <div class="status-content">
                                <h3>Gedung Milik</h3>
                                <div class="status-number">{{ $statusStats['gedung_milik'] ?? 0 }}</div>
                                <div class="status-percentage">{{ 
                                    (($statusStats['gedung_milik'] ?? 0) + ($statusStats['gedung_sewa'] ?? 0)) > 0 
                                        ? round((($statusStats['gedung_milik'] ?? 0) / (($statusStats['gedung_milik'] ?? 0) + ($statusStats['gedung_sewa'] ?? 0))) * 100, 1) 
                                        : 0 
                                }}%</div>
                            </div>
                        </div>
                        
                        <div class="status-item gedung-sewa">
                            <div class="status-icon">
                                <i class="fas fa-home"></i>
                            </div>
                            <div class="status-content">
                                <h3>Gedung Sewa</h3>
                                <div class="status-number">{{ $statusStats['gedung_sewa'] ?? 0 }}</div>
                                <div class="status-percentage">{{ 
                                    (($statusStats['gedung_milik'] ?? 0) + ($statusStats['gedung_sewa'] ?? 0)) > 0 
                                        ? round((($statusStats['gedung_sewa'] ?? 0) / (($statusStats['gedung_milik'] ?? 0) + ($statusStats['gedung_sewa'] ?? 0))) * 100, 1) 
                                        : 0 
                                }}%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Map and Activities Row -->
    <div class="row">
        <!-- Map -->
        <div class="col-xl-8 col-lg-7">
            <div class="map-card">
                <div class="map-header">
                    <h6 class="map-title">
                        <i class="fas fa-map-marked-alt"></i>
                        Peta Lokasi Kantor
                    </h6>
                </div>
                <div class="map-body">
                    <div id="map" class="map-container" data-kantor="{{ json_encode($kantor) }}">
                        <div id="map-loading" class="map-loading">
                            <div class="loading-content">
                                <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                                <p>Memuat peta...</p>
                            </div>
                        </div>
                    </div>
                    <div id="map-fallback" class="map-fallback">
                        <i class="fas fa-map-marked-alt fa-3x mb-3"></i>
                        <h5>Peta Lokasi Kantor</h5>
                        <p>Gunakan menu navigasi untuk melihat lokasi kantor</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="col-xl-4 col-lg-5">
            <div class="activity-card">
                <div class="activity-header">
                    <h6 class="activity-title">
                        <i class="fas fa-clock"></i>
                        Aktivitas Terbaru
                    </h6>
                </div>
                <div class="activity-body">
                    <div class="activity-list">
                        @forelse($recentActivities as $activity)
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-circle"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-text">
                                    <strong>{{ $activity->formatted_model ?? 'Data' }}</strong> 
                                    {{ $activity->action }}
                                </div>
                                <div class="activity-time">
                                    {{ $activity->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="empty-activity">
                            <i class="fas fa-inbox fa-2x mb-2"></i>
                            <p>Tidak ada aktivitas terbaru</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Line Chart
    initLineChart();
    
    // Initialize Map
    initMap();
});

        function initLineChart() {
            const ctx = document.getElementById('lineChart').getContext('2d');
            const dataAttr = document.getElementById('lineChart').getAttribute('data-kontrak');
            const kontrakData = dataAttr ? JSON.parse(dataAttr) : [];

            const labels = kontrakData.map(item => item.month_name);
            const kontrakCount = kontrakData.map(item => item.total);
            const nilaiData = kontrakData.map(item => (item.total_nilai || 0) / 1000000); // Convert to millions
    
    new Chart(ctx, {
        type: 'line',
            data: {
                labels: labels.length > 0 ? labels : ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                datasets: [
                    {
                        label: 'Kontrak (Count)',
                        data: kontrakCount.length > 0 ? kontrakCount : [0, 0, 0, 0, 0, 0],
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Nilai Kontrak (Juta)',
                        data: nilaiData.length > 0 ? nilaiData : [0, 0, 0, 0, 0, 0],
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4,
                        fill: true,
                        yAxisID: 'y1'
                    }
                ]
            },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    if (context.datasetIndex === 0) {
                                        return 'Kontrak: ' + context.parsed.y + ' records';
                                    } else {
                                        return 'Nilai Kontrak: ' + context.parsed.y.toFixed(1) + ' juta';
                                    }
                                }
                            }
                        }
            },
            scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Kontrak (Count)'
                            },
                            beginAtZero: true
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Nilai Kontrak (Juta)'
                            },
                            beginAtZero: true,
                            grid: {
                                drawOnChartArea: false,
                            }
                        }
            }
        }
    });
}


function initMap() {
    const mapElement = document.getElementById('map');
    const loadingElement = document.getElementById('map-loading');
    const fallbackElement = document.getElementById('map-fallback');
    
    // Check if Leaflet is available
    if (typeof L === 'undefined') {
        showFallback();
        return;
    }
    
    const dataAttr = mapElement.getAttribute('data-kantor');
    const kantorData = dataAttr ? JSON.parse(dataAttr) : [];
    
    if (kantorData.length === 0) {
        showFallback();
        return;
    }
    
    // Find valid coordinates
    const validKantor = kantorData.filter(kantor => 
        kantor.latitude && kantor.longitude && 
        !isNaN(parseFloat(kantor.latitude)) && 
        !isNaN(parseFloat(kantor.longitude))
    );
    
    if (validKantor.length === 0) {
        showFallback();
        return;
    }
    
    // Calculate center point
    const centerLat = validKantor.reduce((sum, kantor) => sum + parseFloat(kantor.latitude), 0) / validKantor.length;
    const centerLng = validKantor.reduce((sum, kantor) => sum + parseFloat(kantor.longitude), 0) / validKantor.length;
    
    // Initialize map
    const map = L.map('map').setView([centerLat, centerLng], 10);
    
    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    // Add markers
    validKantor.forEach(kantor => {
        const lat = parseFloat(kantor.latitude);
        const lng = parseFloat(kantor.longitude);
        
        const marker = L.marker([lat, lng]).addTo(map);
        
        const popupContent = `
            <div style="min-width: 200px;">
                <h6 style="margin: 0 0 8px 0; color: #1f2937;">${kantor.nama_kantor}</h6>
                <p style="margin: 0 0 4px 0; color: #6b7280; font-size: 14px;">${kantor.alamat}</p>
                <p style="margin: 0; color: #6b7280; font-size: 14px;">
                    Status: <span style="color: ${kantor.status_kantor === 'aktif' ? '#10b981' : '#ef4444'}; font-weight: 600;">${kantor.status_kantor || 'Unknown'}</span>
                </p>
            </div>
        `;
        
        marker.bindPopup(popupContent);
    });
    
    // Hide loading and show map
    if (loadingElement) {
        loadingElement.style.display = 'none';
    }
}

function showFallback() {
    const loadingElement = document.getElementById('map-loading');
    const fallbackElement = document.getElementById('map-fallback');
    
    if (loadingElement) {
        loadingElement.style.display = 'none';
    }
    if (fallbackElement) {
        fallbackElement.style.display = 'flex';
    }
}
</script>

@push('styles')
<style>
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
        height: 100%;
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

    .stat-okupansi .stat-icon {
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

    /* Chart Cards */
    .chart-card {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        height: 100%;
    }

    .chart-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .chart-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .chart-title i {
        color: #3b82f6;
    }

    .chart-body {
        padding: 1.5rem;
    }

    .chart-container {
        position: relative;
        height: 300px;
    }

    /* Status Card */
    .status-card {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        height: 100%;
    }

    .status-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .status-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .status-title i {
        color: #3b82f6;
    }

    .status-body {
        padding: 1.5rem;
    }

    .status-cards {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .status-item {
        background: #f8fafc;
        border-radius: 1rem;
        padding: 1.25rem;
        text-align: center;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .status-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .status-item.kantor-milik {
        border-color: #10b981;
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    }

    .status-item.kantor-sewa {
        border-color: #3b82f6;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    }

    .status-item.gedung-milik {
        border-color: #f59e0b;
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
    }

    .status-item.gedung-sewa {
        border-color: #8b5cf6;
        background: linear-gradient(135deg, #faf5ff 0%, #e9d5ff 100%);
    }

    .status-icon {
        font-size: 1.5rem;
        margin-bottom: 0.75rem;
    }

    .status-item.kantor-milik .status-icon {
        color: #10b981;
    }

    .status-item.kantor-sewa .status-icon {
        color: #3b82f6;
    }

    .status-item.gedung-milik .status-icon {
        color: #f59e0b;
    }

    .status-item.gedung-sewa .status-icon {
        color: #8b5cf6;
    }

    .status-content h3 {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .status-number {
        font-size: 1.75rem;
        font-weight: 800;
        margin-bottom: 0.25rem;
        color: #1e293b;
    }

    .status-percentage {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 600;
    }

    /* Map Card */
    .map-card {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        height: 100%;
    }

    .map-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .map-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .map-title i {
        color: #3b82f6;
    }

    .map-body {
        padding: 0;
    }

    .map-container {
        height: 400px;
        width: 100%;
        position: relative;
    }

    .map-loading {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: #6b7280;
    }

    .loading-content {
        text-align: center;
    }

    .map-fallback {
        display: none;
        height: 400px;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        color: #6b7280;
    }

    /* Activity Card */
    .activity-card {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        height: 100%;
    }

    .activity-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .activity-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .activity-title i {
        color: #3b82f6;
    }

    .activity-body {
        padding: 1.5rem;
        max-height: 400px;
        overflow-y: auto;
    }

    .activity-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .activity-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.75rem;
        border-radius: 0.75rem;
        transition: all 0.3s ease;
    }

    .activity-item:hover {
        background: #f8fafc;
    }

    .activity-icon {
        flex-shrink: 0;
        margin-top: 0.25rem;
    }

    .activity-icon i {
        color: #3b82f6;
        font-size: 0.5rem;
    }

    .activity-content {
        flex: 1;
    }

    .activity-text {
        color: #1e293b;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }

    .activity-text strong {
        color: #1e293b;
        font-weight: 600;
    }

    .activity-time {
        color: #64748b;
        font-size: 0.8rem;
    }

    .empty-activity {
        text-align: center;
        color: #64748b;
        padding: 2rem 1rem;
    }

    .empty-activity i {
        color: #94a3b8;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .stat-card {
            flex-direction: column;
            text-align: center;
            padding: 1rem;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            font-size: 1.25rem;
        }

        .stat-number {
            font-size: 1.5rem;
        }

        .chart-container {
            height: 250px;
        }

        .map-container {
            height: 300px;
        }

        .activity-body {
            max-height: 300px;
        }

        .status-cards {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }

        .status-item {
            padding: 1rem;
        }

        .status-number {
            font-size: 1.5rem;
        }
    }

    @media (max-width: 576px) {
        .stat-card {
            padding: 0.75rem;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }

        .stat-number {
            font-size: 1.25rem;
        }

        .chart-container {
            height: 200px;
        }

        .map-container {
            height: 250px;
        }

        .status-item {
            padding: 0.75rem;
        }

        .status-number {
            font-size: 1.25rem;
        }

        .status-icon {
            font-size: 1.25rem;
        }
    }
</style>
@endpush
</div>
@endsection