@extends('layouts.app')

@section('title', 'Analytics Dashboard - PLN Icon Plus Kantor Management')
@section('page-title', 'Analytics Dashboard')
@section('page-subtitle', 'Analisis mendalam data kantor PLN Icon Plus')

@section('page-actions')
    <div class="header-actions">
        <button type="button" class="btn btn-modern btn-primary" onclick="refreshCharts()">
            <i class="fas fa-sync-alt"></i> Refresh
        </button>
        <button type="button" class="btn btn-modern btn-success" onclick="exportData()">
            <i class="fas fa-download"></i> Export
        </button>
    </div>
@endsection

@section('content')
<!-- Analytics Content -->
<div class="container-fluid">
    <!-- Advanced Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card stat-lantai">
                <div class="stat-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="stat-content">
                    <h5 class="stat-number">{{ $stats['total_lantai'] }}</h5>
                    <p class="stat-label">Total Lantai</p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card stat-ruang">
                <div class="stat-icon">
                    <i class="fas fa-door-open"></i>
                </div>
                <div class="stat-content">
                    <h5 class="stat-number">{{ $stats['total_ruang'] }}</h5>
                    <p class="stat-label">Total Ruang</p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card stat-bidang">
                <div class="stat-icon">
                    <i class="fas fa-sitemap"></i>
                </div>
                <div class="stat-content">
                    <h5 class="stat-number">{{ $stats['total_bidang'] }}</h5>
                    <p class="stat-label">Total Bidang</p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card stat-sub-bidang">
                <div class="stat-icon">
                    <i class="fas fa-project-diagram"></i>
                </div>
                <div class="stat-content">
                    <h5 class="stat-number">{{ $stats['total_sub_bidang'] }}</h5>
                    <p class="stat-label">Total Sub Bidang</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Okupansi Statistics -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="okupansi-card">
                <div class="okupansi-header">
                    <h6 class="okupansi-title">
                        <i class="fas fa-users"></i>
                        Statistik Okupansi
                    </h6>
                </div>
                <div class="okupansi-body">
                    <div class="okupansi-grid">
                        <div class="okupansi-item">
                            <div class="okupansi-icon organik">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="okupansi-content">
                                <h4 class="okupansi-number">{{ $okupansiStats['total_organik'] }}</h4>
                                <p class="okupansi-label">Pegawai Organik</p>
                            </div>
                        </div>
                        <div class="okupansi-item">
                            <div class="okupansi-icon tad">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div class="okupansi-content">
                                <h4 class="okupansi-number">{{ $okupansiStats['total_tad'] }}</h4>
                                <p class="okupansi-label">Pegawai TAD</p>
                            </div>
                        </div>
                        <div class="okupansi-item">
                            <div class="okupansi-icon kontrak">
                                <i class="fas fa-user-clock"></i>
                            </div>
                            <div class="okupansi-content">
                                <h4 class="okupansi-number">{{ $okupansiStats['total_kontrak'] }}</h4>
                                <p class="okupansi-label">Pegawai Kontrak</p>
                            </div>
                        </div>
                        <div class="okupansi-item">
                            <div class="okupansi-icon rata">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="okupansi-content">
                                <h4 class="okupansi-number">{{ $okupansiStats['avg_okupansi'] }}%</h4>
                                <p class="okupansi-label">Rata-rata Okupansi</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-xl-6 col-lg-6">
            <div class="chart-card">
                <div class="chart-header">
                    <h6 class="chart-title">
                        <i class="fas fa-chart-doughnut"></i>
                        Status Kantor
                    </h6>
                </div>
                <div class="chart-body">
                    <div class="chart-container">
                        <canvas id="kantorStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6">
            <div class="chart-card">
                <div class="chart-header">
                    <h6 class="chart-title">
                        <i class="fas fa-chart-pie"></i>
                        Status Kontrak
                    </h6>
                </div>
                <div class="chart-body">
                    <div class="chart-container">
                        <canvas id="kontrakStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Okupansi by Bidang Chart -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="chart-card">
                <div class="chart-header">
                    <h6 class="chart-title">
                        <i class="fas fa-chart-bar"></i>
                        Okupansi per Bidang
                    </h6>
                </div>
                <div class="chart-body">
                    <div class="chart-container">
                        <canvas id="okupansiBidangChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-xl-6 col-lg-6">
            <div class="activity-card">
                <div class="activity-header">
                    <h6 class="activity-title">
                        <i class="fas fa-file-contract"></i>
                        Kontrak Terbaru
                    </h6>
                </div>
                <div class="activity-body">
                    <div class="activity-list">
                        @forelse($recentKontrak as $kontrak)
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-circle"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-text">
                                    <strong>{{ $kontrak->nama_perjanjian }}</strong>
                                </div>
                                <div class="activity-detail">
                                    {{ $kontrak->kantor->nama_kantor ?? 'N/A' }}
                                </div>
                                <div class="activity-time">
                                    {{ $kontrak->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="empty-activity">
                            <i class="fas fa-info-circle fa-2x mb-2"></i>
                            <p>Belum ada kontrak</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6">
            <div class="activity-card">
                <div class="activity-header">
                    <h6 class="activity-title">
                        <i class="fas fa-chart-line"></i>
                        Realisasi Terbaru
                    </h6>
                </div>
                <div class="activity-body">
                    <div class="activity-list">
                        @forelse($recentRealisasi as $realisasi)
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-circle"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-text">
                                    <strong>{{ $realisasi->kompensasi }}</strong>
                                </div>
                                <div class="activity-detail">
                                    Rp {{ number_format($realisasi->rp_kompensasi, 0, ',', '.') }}
                                </div>
                                <div class="activity-time">
                                    {{ $realisasi->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="empty-activity">
                            <i class="fas fa-info-circle fa-2x mb-2"></i>
                            <p>Belum ada realisasi</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<!-- Chart.js CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.css">

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

    .stat-lantai .stat-icon {
        background: linear-gradient(135deg, #3b82f6, #60a5fa);
    }

    .stat-ruang .stat-icon {
        background: linear-gradient(135deg, #06b6d4, #22d3ee);
    }

    .stat-bidang .stat-icon {
        background: linear-gradient(135deg, #06b6d4, #22d3ee);
    }

    .stat-sub-bidang .stat-icon {
        background: linear-gradient(135deg, #10b981, #34d399);
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

    /* Okupansi Card */
    .okupansi-card {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .okupansi-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .okupansi-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .okupansi-title i {
        color: #3b82f6;
    }

    .okupansi-body {
        padding: 1.5rem;
    }

    .okupansi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }

    .okupansi-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        border-radius: 1rem;
        background: #f8fafc;
        transition: all 0.3s ease;
    }

    .okupansi-item:hover {
        background: #f1f5f9;
        transform: translateY(-2px);
    }

    .okupansi-icon {
        width: 50px;
        height: 50px;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: white;
    }

    .okupansi-icon.organik {
        background: linear-gradient(135deg, #3b82f6, #60a5fa);
    }

    .okupansi-icon.tad {
        background: linear-gradient(135deg, #10b981, #34d399);
    }

    .okupansi-icon.kontrak {
        background: linear-gradient(135deg, #f59e0b, #fbbf24);
    }

    .okupansi-icon.rata {
        background: linear-gradient(135deg, #3b82f6, #60a5fa);
    }

    .okupansi-content {
        flex: 1;
    }

    .okupansi-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        line-height: 1;
    }

    .okupansi-label {
        color: #64748b;
        font-size: 0.9rem;
        margin: 0.25rem 0 0 0;
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

    /* Activity Cards */
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

    .activity-detail {
        color: #64748b;
        font-size: 0.85rem;
        margin-bottom: 0.25rem;
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

    /* Header Actions */
    .header-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-modern {
        padding: 0.5rem 1rem;
        border-radius: 0.75rem;
        border: none;
        font-weight: 500;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-modern.btn-primary {
        background: linear-gradient(135deg, #3b82f6, #60a5fa);
        color: white;
    }

    .btn-modern.btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    }

    .btn-modern.btn-success {
        background: linear-gradient(135deg, #10b981, #34d399);
        color: white;
    }

    .btn-modern.btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
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

        .okupansi-grid {
            grid-template-columns: 1fr;
        }

        .okupansi-item {
            flex-direction: column;
            text-align: center;
        }

        .chart-container {
            height: 250px;
        }

        .activity-body {
            max-height: 300px;
        }

        .header-actions {
            flex-direction: column;
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
    }
</style>
@endpush

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<!-- Chart Data Container -->
<div id="chartData" 
     data-kantor-status="{{ json_encode($kantorByStatus) }}"
     data-kontrak-status="{{ json_encode($kontrakByStatus) }}"
     data-okupansi-bidang="{{ json_encode($okupansiByBidang) }}"
     style="display: none;">
</div>

<script>
// Chart data
const chartDataEl = document.getElementById('chartData');
const kantorStatusData = JSON.parse(chartDataEl.dataset.kantorStatus);
const kontrakStatusData = JSON.parse(chartDataEl.dataset.kontrakStatus);
const okupansiBidangData = JSON.parse(chartDataEl.dataset.okupansiBidang);

// Kantor Status Chart
const kantorCtx = document.getElementById('kantorStatusChart').getContext('2d');
new Chart(kantorCtx, {
    type: 'doughnut',
    data: {
        labels: Object.keys(kantorStatusData),
        datasets: [{
            data: Object.values(kantorStatusData),
            backgroundColor: ['#28a745', '#dc3545', '#ffc107'],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Kontrak Status Chart
const kontrakCtx = document.getElementById('kontrakStatusChart').getContext('2d');
new Chart(kontrakCtx, {
    type: 'pie',
    data: {
        labels: Object.keys(kontrakStatusData),
        datasets: [{
            data: Object.values(kontrakStatusData),
            backgroundColor: ['#007bff', '#28a745', '#dc3545'],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Okupansi by Bidang Chart
const okupansiCtx = document.getElementById('okupansiBidangChart').getContext('2d');
new Chart(okupansiCtx, {
    type: 'bar',
    data: {
        labels: okupansiBidangData.map(item => item.bidang),
        datasets: [{
            label: 'Total Pegawai',
            data: okupansiBidangData.map(item => item.total_pegawai),
            backgroundColor: '#17a2b8',
            borderColor: '#138496',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

// Functions
function refreshCharts() {
    location.reload();
}

function exportData() {
    // Implement export functionality
    alert('Export functionality akan diimplementasi');
}
</script>
@endpush
