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
    :root {
        --pln-blue: #21618C;
        --pln-blue-dark: #1A4D73;
        --pln-blue-light: #2E86AB;
        --pln-blue-lighter: #E8F4F8;
        --pln-blue-bg: #F5FAFC;
        --text-dark: #1A1A1A;
        --text-gray: #6C757D;
    }

    /* Card Statistik - PLN Blue Theme */
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.75rem;
        box-shadow: 0 2px 8px rgba(33, 97, 140, 0.1);
        border: 1px solid rgba(33, 97, 140, 0.15);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--pln-blue);
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 20px rgba(33, 97, 140, 0.2);
        border-color: var(--pln-blue);
    }

    .stat-icon {
        width: 64px;
        height: 64px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        color: var(--pln-blue);
        background: var(--pln-blue-lighter);
        flex-shrink: 0;
    }

    .stat-lantai .stat-icon {
        background: var(--pln-blue-lighter);
        color: var(--pln-blue);
    }

    .stat-ruang .stat-icon {
        background: var(--pln-blue-lighter);
        color: var(--pln-blue);
    }

    .stat-bidang .stat-icon {
        background: var(--pln-blue-lighter);
        color: var(--pln-blue);
    }

    .stat-sub-bidang .stat-icon {
        background: var(--pln-blue-lighter);
        color: var(--pln-blue);
    }

    .stat-content {
        flex: 1;
    }

    .stat-number {
        font-size: 2.25rem;
        font-weight: 800;
        color: var(--pln-blue);
        margin: 0;
        line-height: 1;
    }

    .stat-label {
        color: var(--text-gray);
        font-size: 0.875rem;
        margin: 0.5rem 0 0 0;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Card Okupansi */
    .okupansi-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(33, 97, 140, 0.1);
        border: 1px solid rgba(33, 97, 140, 0.15);
        overflow: hidden;
    }

    .okupansi-header {
        background: white;
        padding: 1.25rem 1.75rem;
        border-bottom: 2px solid var(--pln-blue);
    }

    .okupansi-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--pln-blue);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .okupansi-title i {
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

    .okupansi-body {
        padding: 1.75rem;
    }

    .okupansi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
    }

    .okupansi-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.25rem;
        border-radius: 10px;
        background: var(--pln-blue-bg);
        border: 1px solid rgba(33, 97, 140, 0.1);
        transition: all 0.3s ease;
    }

    .okupansi-item:hover {
        background: var(--pln-blue-lighter);
        border-color: var(--pln-blue);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(33, 97, 140, 0.15);
    }

    .okupansi-icon {
        width: 56px;
        height: 56px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        flex-shrink: 0;
    }

    .okupansi-icon.organik {
        background: var(--pln-blue);
    }

    .okupansi-icon.tad {
        background: var(--pln-blue-light);
    }

    .okupansi-icon.kontrak {
        background: #2E86AB;
    }

    .okupansi-icon.rata {
        background: var(--pln-blue-dark);
    }

    .okupansi-content {
        flex: 1;
    }

    .okupansi-number {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--pln-blue);
        margin: 0;
        line-height: 1;
    }

    .okupansi-label {
        color: var(--text-gray);
        font-size: 0.875rem;
        margin: 0.375rem 0 0 0;
        font-weight: 600;
    }

    /* Card Chart */
    .chart-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(33, 97, 140, 0.1);
        border: 1px solid rgba(33, 97, 140, 0.15);
        overflow: hidden;
        height: 100%;
    }

    .chart-header {
        background: white;
        padding: 1.25rem 1.75rem;
        border-bottom: 2px solid var(--pln-blue);
    }

    .chart-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--pln-blue);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .chart-title i {
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

    .chart-body {
        padding: 1.75rem;
    }

    .chart-container {
        position: relative;
        height: 320px;
    }

    /* Card Aktivitas */
    .activity-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(33, 97, 140, 0.1);
        border: 1px solid rgba(33, 97, 140, 0.15);
        overflow: hidden;
        height: 100%;
    }

    .activity-header {
        background: white;
        padding: 1.25rem 1.75rem;
        border-bottom: 2px solid var(--pln-blue);
    }

    .activity-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--pln-blue);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .activity-title i {
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

    .activity-body {
        padding: 1.5rem;
        max-height: 420px;
        overflow-y: auto;
    }

    .activity-list {
        display: flex;
        flex-direction: column;
        gap: 0.875rem;
    }

    .activity-item {
        display: flex;
        align-items: flex-start;
        gap: 0.875rem;
        padding: 1rem;
        border-radius: 10px;
        background: var(--pln-blue-bg);
        border: 1px solid rgba(33, 97, 140, 0.1);
        transition: all 0.2s ease;
    }

    .activity-item:hover {
        background: var(--pln-blue-lighter);
        border-color: var(--pln-blue);
        transform: translateX(4px);
    }

    .activity-icon {
        flex-shrink: 0;
        margin-top: 0.125rem;
    }

    .activity-icon i {
        color: var(--pln-blue);
        font-size: 0.625rem;
    }

    .activity-content {
        flex: 1;
    }

    .activity-text {
        color: var(--text-dark);
        font-size: 0.9375rem;
        margin-bottom: 0.375rem;
    }

    .activity-text strong {
        color: var(--pln-blue);
        font-weight: 700;
    }

    .activity-detail {
        color: var(--text-gray);
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }

    .activity-time {
        color: var(--text-gray);
        font-size: 0.8125rem;
        font-style: italic;
    }

    .empty-activity {
        text-align: center;
        color: var(--text-gray);
        padding: 3rem 1rem;
    }

    .empty-activity i {
        color: var(--pln-blue-lighter);
    }

    /* Aksi Header */
    .header-actions {
        display: flex;
        gap: 0.75rem;
    }

    .btn-modern {
        border-radius: 10px;
        padding: 0.625rem 1.25rem;
        font-weight: 600;
        transition: all 0.2s ease;
        border: 1px solid transparent;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .btn-modern:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(33, 97, 140, 0.2);
    }

    .btn-modern.btn-primary {
        background: var(--pln-blue);
        color: white;
        border: 1px solid var(--pln-blue);
        box-shadow: 0 2px 6px rgba(33, 97, 140, 0.15);
    }

    .btn-modern.btn-primary:hover {
        background: var(--pln-blue-dark);
        border-color: var(--pln-blue-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(33, 97, 140, 0.25);
    }

    .btn-modern.btn-success {
        background: #28a745;
        color: white;
        border: 1px solid #28a745;
    }

    .btn-modern.btn-success:hover {
        background: #218838;
        border-color: #218838;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.25);
    }

    /* Desain Responsive */
    @media (max-width: 768px) {
        .stat-card {
            flex-direction: column;
            text-align: center;
            padding: 1.25rem;
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            font-size: 1.5rem;
        }

        .stat-number {
            font-size: 1.75rem;
        }

        .okupansi-grid {
            grid-template-columns: 1fr;
        }

        .okupansi-item {
            flex-direction: column;
            text-align: center;
        }

        .chart-container {
            height: 280px;
        }

        .activity-body {
            max-height: 350px;
        }

        .header-actions {
            flex-direction: column;
        }

        .header-actions .btn-modern {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 576px) {
        .stat-card {
            padding: 1rem;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            font-size: 1.25rem;
        }

        .stat-number {
            font-size: 1.5rem;
        }

        .chart-container {
            height: 240px;
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
// Data Chart
const chartDataEl = document.getElementById('chartData');
const kantorStatusData = JSON.parse(chartDataEl.dataset.kantorStatus);
const kontrakStatusData = JSON.parse(chartDataEl.dataset.kontrakStatus);
const okupansiBidangData = JSON.parse(chartDataEl.dataset.okupansiBidang);

// PLN Blue Color Palette
const plnBlue = '#21618C';
const plnBlueDark = '#1A4D73';
const plnBlueLight = '#2E86AB';
const plnSuccess = '#28a745';
const plnWarning = '#ffc107';
const plnDanger = '#dc3545';

// Chart Status Kantor
const kantorCtx = document.getElementById('kantorStatusChart').getContext('2d');
new Chart(kantorCtx, {
    type: 'doughnut',
    data: {
        labels: Object.keys(kantorStatusData),
        datasets: [{
            data: Object.values(kantorStatusData),
            backgroundColor: [plnSuccess, plnDanger, plnWarning],
            borderWidth: 3,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 15,
                    font: {
                        size: 12,
                        weight: '600'
                    },
                    color: '#1A1A1A'
                }
            },
            tooltip: {
                backgroundColor: plnBlue,
                padding: 12,
                cornerRadius: 8,
                titleFont: {
                    size: 13,
                    weight: '700'
                },
                bodyFont: {
                    size: 12
                }
            }
        }
    }
});

// Chart Status Kontrak
const kontrakCtx = document.getElementById('kontrakStatusChart').getContext('2d');
new Chart(kontrakCtx, {
    type: 'pie',
    data: {
        labels: Object.keys(kontrakStatusData),
        datasets: [{
            data: Object.values(kontrakStatusData),
            backgroundColor: [plnBlue, plnSuccess, plnDanger],
            borderWidth: 3,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 15,
                    font: {
                        size: 12,
                        weight: '600'
                    },
                    color: '#1A1A1A'
                }
            },
            tooltip: {
                backgroundColor: plnBlue,
                padding: 12,
                cornerRadius: 8,
                titleFont: {
                    size: 13,
                    weight: '700'
                },
                bodyFont: {
                    size: 12
                }
            }
        }
    }
});

// Chart Okupansi berdasarkan Bidang
const okupansiCtx = document.getElementById('okupansiBidangChart').getContext('2d');
new Chart(okupansiCtx, {
    type: 'bar',
    data: {
        labels: okupansiBidangData.map(item => item.bidang),
        datasets: [{
            label: 'Total Pegawai',
            data: okupansiBidangData.map(item => item.total_pegawai),
            backgroundColor: plnBlue,
            borderColor: plnBlueDark,
            borderWidth: 2,
            borderRadius: 8,
            hoverBackgroundColor: plnBlueLight
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    font: {
                        size: 11,
                        weight: '600'
                    },
                    color: '#6C757D'
                },
                grid: {
                    color: 'rgba(33, 97, 140, 0.1)',
                    drawBorder: false
                }
            },
            x: {
                ticks: {
                    font: {
                        size: 11,
                        weight: '600'
                    },
                    color: '#6C757D'
                },
                grid: {
                    display: false
                }
            }
        },
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: plnBlue,
                padding: 12,
                cornerRadius: 8,
                titleFont: {
                    size: 13,
                    weight: '700'
                },
                bodyFont: {
                    size: 12
                }
            }
        }
    }
});

// Fungsi-fungsi
function refreshCharts() {
    location.reload();
}

function exportData() {
    // Implementasi fungsi export
    alert('Export functionality akan diimplementasi');
}
</script>
@endpush
