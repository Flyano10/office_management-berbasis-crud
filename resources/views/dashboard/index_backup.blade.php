@extends('layouts.app')

@section('title', 'Dashboard - PLN Icon Plus')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Overview sistem manajemen kantor PLN Icon Plus')

@section('content')
<style>
    :root {
        --primary-blue: #3b82f6;
        --light-blue: #dbeafe;
        --soft-blue: #f0f9ff;
        --text-dark: #1f2937;
        --text-gray: #4b5563;
        --text-light: #6b7280;
        --white: #ffffff;
        --shadow-soft: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        --shadow-medium: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        --shadow-large: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }
    
    body {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    
    .welcome-banner {
        background: linear-gradient(135deg, var(--soft-blue) 0%, var(--light-blue) 100%);
        border-radius: 20px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        border: 1px solid #bfdbfe;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-soft);
    }
    
    .welcome-banner::before {
        content: '';
        position: absolute;
        top: -30%;
        right: -10%;
        width: 150px;
        height: 150px;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 0%, transparent 70%);
        border-radius: 50%;
    }
    
    .welcome-content {
        position: relative;
        z-index: 2;
    }
    
    .welcome-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.75rem;
        line-height: 1.3;
    }
    
    .welcome-subtitle {
        font-size: 1.1rem;
        color: var(--text-gray);
        font-weight: 400;
        margin-bottom: 0;
    }
    
    .welcome-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, var(--primary-blue), #1d4ed8);
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 28px;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-medium);
    }
    
    .illustration {
        position: absolute;
        right: 2rem;
        top: 50%;
        transform: translateY(-50%);
        opacity: 0.15;
        font-size: 5rem;
        color: var(--primary-blue);
        z-index: 1;
    }
    
    .stats-section {
        margin-bottom: 2.5rem;
    }
    
    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .section-title i {
        color: var(--primary-blue);
        font-size: 1.25rem;
    }
    
    .stats-card {
        background: var(--white);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: var(--shadow-soft);
        border: 1px solid #f3f4f6;
        transition: all 0.3s ease;
        height: 100%;
        position: relative;
        overflow: hidden;
    }
    
    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-blue), #1d4ed8);
    }
    
    .stats-card:hover {
        transform: translateY(-6px);
        box-shadow: var(--shadow-medium);
    }
    
    .stats-icon {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: white;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-soft);
    }
    
    .stats-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
        line-height: 1;
    }
    
    .stats-label {
        font-size: 1rem;
        color: var(--text-gray);
        font-weight: 600;
        margin-bottom: 0;
    }
    
    .chart-container {
        background: var(--white);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: var(--shadow-soft);
        border: 1px solid #f3f4f6;
        margin-bottom: 2rem;
    }
    
    .chart-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .chart-title i {
        color: var(--primary-blue);
    }
    
    .map-container {
        background: var(--white);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: var(--shadow-soft);
        border: 1px solid #f3f4f6;
        margin-bottom: 2rem;
    }
    
    #map {
        height: 400px;
        width: 100%;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--shadow-soft);
        background-color: #f8fafc;
        border: 1px solid #e5e7eb;
    }
    
    #map:empty::before {
        content: 'Memuat peta...';
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: #6b7280;
        font-size: 14px;
    }
    
    .progress-container {
        background: #f3f4f6;
        border-radius: 10px;
        height: 10px;
        overflow: hidden;
        margin-bottom: 0.75rem;
    }
    
    .progress-bar {
        height: 100%;
        background: linear-gradient(90deg, var(--primary-blue), #1d4ed8);
        border-radius: 10px;
        transition: width 0.3s ease;
    }
    
    .activity-item {
        padding: 1.25rem;
        border-left: 4px solid var(--primary-blue);
        background: var(--soft-blue);
        border-radius: 0 16px 16px 0;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .activity-item:hover {
        background: var(--light-blue);
        transform: translateX(6px);
        box-shadow: var(--shadow-soft);
    }
    
    .activity-item::before {
        content: '';
        position: absolute;
        left: -2px;
        top: 50%;
        transform: translateY(-50%);
        width: 8px;
        height: 8px;
        background: var(--primary-blue);
        border-radius: 50%;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, var(--primary-blue), #1d4ed8);
        border: none;
        border-radius: 12px;
        padding: 0.875rem 1.75rem;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-soft);
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-medium);
    }
    
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 1.5rem;
    }
    
    .action-btn {
        background: var(--white);
        border: 2px solid #e5e7eb;
        border-radius: 16px;
        padding: 1.25rem;
        text-align: center;
        text-decoration: none;
        color: var(--text-dark);
        transition: all 0.3s ease;
        box-shadow: var(--shadow-soft);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
    }
    
    .action-btn:hover {
        border-color: var(--primary-blue);
        color: var(--primary-blue);
        transform: translateY(-4px);
        box-shadow: var(--shadow-medium);
    }
    
    .action-btn i {
        font-size: 1.5rem;
    }
    
    .notification-btn {
        position: relative;
        background: var(--white);
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 0.875rem;
        color: var(--text-gray);
        transition: all 0.3s ease;
        box-shadow: var(--shadow-soft);
    }
    
    .notification-btn:hover {
        border-color: var(--primary-blue);
        color: var(--primary-blue);
        transform: translateY(-1px);
    }
    
    .notification-badge {
        position: absolute;
        top: -6px;
        right: -6px;
        background: #ef4444;
        color: white;
        border-radius: 50%;
        width: 22px;
        height: 22px;
        font-size: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
    
    .search-container {
        position: relative;
        max-width: 400px;
    }
    
    .search-input {
        width: 100%;
        padding: 0.875rem 1rem 0.875rem 3rem;
        border: 2px solid #e5e7eb;
        border-radius: 16px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: var(--white);
        box-shadow: var(--shadow-soft);
    }
    
    .search-input:focus {
        outline: none;
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-light);
        font-size: 1rem;
    }
</style>

<!-- Welcome Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="welcome-banner">
            <div class="welcome-content">
                <div class="welcome-icon">
                    <i class="fas fa-sun"></i>
                </div>
                <h2 class="welcome-title">
                    Halo {{ auth('admin')->user()->nama_admin ?? 'Admin' }}! Selamat datang kembali
                </h2>
                <p class="welcome-subtitle">Semoga hari Anda menyenangkan</p>
                <p class="welcome-subtitle">Ringkasan sistem manajemen kantor PLN Icon Plus</p>
            </div>
            <div class="illustration">
                <i class="fas fa-building"></i>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-12">
        <h3 class="section-title">
            <i class="fas fa-chart-bar"></i>
            Statistik Overview
        </h3>
    </div>
</div>

<div class="row mb-4">
    <!-- Statistics Cards -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="stats-icon" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
                <i class="fas fa-building"></i>
            </div>
            <h3 class="stats-number">{{ $totalKantor ?? 'N/A' }}</h3>
            <p class="stats-label">Total Kantor</p>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="stats-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                <i class="fas fa-home"></i>
            </div>
            <h3 class="stats-number">{{ $totalGedung }}</h3>
            <p class="stats-label">Total Gedung</p>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="stats-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                <i class="fas fa-door-open"></i>
            </div>
            <h3 class="stats-number">{{ $totalRuang }}</h3>
            <p class="stats-label">Total Ruang</p>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="stats-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                <i class="fas fa-chart-pie"></i>
            </div>
            <h3 class="stats-number">{{ $totalOkupansi }}</h3>
            <p class="stats-label">Total Okupansi</p>
        </div>
    </div>
</div>

<!-- Charts & Analytics -->
<div class="row mb-4">
    <div class="col-12">
        <h3 class="section-title">
            <i class="fas fa-chart-line"></i>
            Grafik & Analitik
        </h3>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg-6 mb-4">
        <div class="chart-container">
            <h4 class="chart-title">
                <i class="fas fa-chart-bar"></i>
                Grafik Batang
            </h4>
            <canvas id="barChart" width="400" height="200" data-realisasi="{{ json_encode($realisasiByMonth) }}" data-debug="{{ json_encode(['count' => $realisasiByMonth->count(), 'data' => $realisasiByMonth->toArray()]) }}"></canvas>
        </div>
    </div>
    
    <div class="col-lg-6 mb-4">
        <div class="chart-container">
            <h4 class="chart-title">
                <i class="fas fa-chart-pie"></i>
                Grafik Lingkaran
            </h4>
            <canvas id="pieChart" width="400" height="200" data-status="{{ json_encode($statusStats) }}" data-analytics="{{ json_encode($analyticsData) }}" data-debug="{{ json_encode(['status' => $statusStats, 'analytics' => $analyticsData]) }}"></canvas>
        </div>
    </div>
</div>

<!-- Progress Bars -->
<div class="row mb-4">
    <div class="col-12">
        <div class="chart-container">
            <h4 class="chart-title">
                <i class="fas fa-tasks"></i>
                Bar Kemajuan
            </h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-semibold">Kontrak Aktif</span>
                            <span class="text-primary fw-bold" id="kontrak-percentage">
                                @php
                                    $totalKontrak = ($statusStats['kontrak_aktif'] ?? 0) + ($statusStats['kontrak_selesai'] ?? 0);
                                    $kontrakPercentage = $totalKontrak > 0 ? round((($statusStats['kontrak_aktif'] ?? 0) / $totalKontrak) * 100) : 0;
                                @endphp
                                {{ $kontrakPercentage }}%
                            </span>
                        </div>
                        <div class="progress-container">
                            <div class="progress-bar" id="kontrak-progress" data-width="{{ $kontrakPercentage }}"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-semibold">Okupansi Ruang</span>
                            <span class="text-success fw-bold" id="okupansi-percentage">
                                @php
                                    $okupansiPercentage = $totalRuang > 0 ? round(($totalOkupansi / $totalRuang) * 100) : 0;
                                @endphp
                                {{ $okupansiPercentage }}%
                            </span>
                        </div>
                        <div class="progress-container">
                            <div class="progress-bar" id="okupansi-progress" data-width="{{ $okupansiPercentage }}"></div>image.png
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Interactive Map -->
<div class="row mb-4">
    <div class="col-12">
        <div class="map-container">
            <h4 class="chart-title">
                <i class="fas fa-map-marked-alt"></i>
                Peta Interaktif
            </h4>
            <div id="map" data-kantor="{{ json_encode($kantor) }}">
                <div id="map-loading" style="display: flex; align-items: center; justify-content: center; height: 100%; color: #6b7280; font-size: 14px;">
                    <i class="fas fa-spinner fa-spin" style="margin-right: 8px;"></i>
                    Memuat peta...
                </div>
            </div>
            
            <!-- Fallback static map if JavaScript fails -->
            <div id="map-fallback" style="display: none; height: 400px; background: #f8fafc; border: 1px solid #e5e7eb; border-radius: 16px; padding: 20px; text-align: center;">
                <div style="margin-top: 150px;">
                    <i class="fas fa-map-marker-alt" style="font-size: 48px; color: #3b82f6; margin-bottom: 20px;"></i>
                    <h5 style="color: #1f2937; margin-bottom: 10px;">Peta Interaktif</h5>
                    <p style="color: #6b7280; margin-bottom: 20px;">Lokasi Kantor PLN Icon Plus</p>
                    @if($kantor->count() > 0)
                        <div style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                            <h6 style="color: #3b82f6; margin-bottom: 8px;">{{ $kantor->first()->nama_kantor }}</h6>
                            <p style="color: #6b7280; font-size: 14px; margin: 0;">{{ $kantor->first()->alamat }}</p>
                            @if($kantor->first()->latitude && $kantor->first()->longitude)
                                <p style="color: #9ca3af; font-size: 12px; margin: 5px 0 0 0;">
                                    Koordinat: {{ $kantor->first()->latitude }}, {{ $kantor->first()->longitude }}
                                </p>
                            @endif
                        </div>
                    @else
                        <div style="background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                            <p style="color: #6b7280; margin: 0;">Belum ada data kantor</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Debug info (remove in production) -->
            <div style="margin-top: 10px; padding: 10px; background: #f3f4f6; border-radius: 8px; font-size: 12px; color: #6b7280;">
                <strong>Debug Info:</strong><br>
                Kantor count: {{ $kantor->count() }}<br>
                @if($kantor->count() > 0)
                    First kantor: {{ $kantor->first()->nama_kantor }}<br>
                    Coordinates: {{ $kantor->first()->latitude }}, {{ $kantor->first()->longitude }}<br>
                @else
                    No kantor data<br>
                @endif
                Realisasi count: {{ $realisasiByMonth->count() }}<br>
                Status stats: {{ json_encode($statusStats) }}<br>
                Analytics data: {{ json_encode($analyticsData) }}
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities & Today Events -->
<div class="row mb-4">
    <div class="col-12">
        <h3 class="section-title">
            <i class="fas fa-clock"></i>
            Aktivitas Terbaru & Acara Hari Ini
        </h3>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg-6 mb-4">
        <div class="chart-container">
            <h4 class="chart-title">
                <i class="fas fa-history"></i>
                Aktivitas Terbaru
            </h4>
            @forelse($recentActivities->take(2) as $activity)
            <div class="activity-item">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="mb-1 fw-semibold">{{ ucfirst($activity->action) }} {{ $activity->formatted_model }}</h6>
                        <p class="mb-1 text-muted">{{ $activity->description ?? 'Aktivitas sistem' }}</p>
                        <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                    </div>
                    <i class="fas fa-{{ $activity->action === 'create' ? 'plus' : ($activity->action === 'update' ? 'edit' : 'trash') }} text-primary"></i>
                </div>
            </div>
            @empty
            <div class="activity-item">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="mb-1 fw-semibold">Dashboard Access</h6>
                        <p class="mb-1 text-muted">Pengguna mengakses dashboard</p>
                        <small class="text-muted">Baru saja</small>
                    </div>
                    <i class="fas fa-eye text-primary"></i>
                </div>
            </div>
            @endforelse
        </div>
    </div>
    
    <div class="col-lg-6 mb-4">
        <div class="chart-container">
            <h4 class="chart-title">
                <i class="fas fa-calendar-day"></i>
                Acara Hari Ini
            </h4>
            <div class="activity-item">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="mb-1 fw-semibold">Meeting Tim</h6>
                        <p class="mb-1 text-muted">Rapat koordinasi mingguan</p>
                        <small class="text-muted">10:00 WIB</small>
                    </div>
                    <i class="fas fa-users text-info"></i>
                </div>
            </div>
            
            <div class="activity-item">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="mb-1 fw-semibold">Review Kontrak</h6>
                        <p class="mb-1 text-muted">Evaluasi kontrak bulanan</p>
                        <small class="text-muted">14:00 WIB</small>
                    </div>
                    <i class="fas fa-file-contract text-warning"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Analytics Summary -->
<div class="row mb-4">
    <div class="col-12">
        <h3 class="section-title">
            <i class="fas fa-chart-line"></i>
            Ringkasan Analitik
        </h3>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="stats-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <h3 class="stats-number">Rp {{ number_format($analyticsData['total_nilai_kontrak'] ?? 0, 0, ',', '.') }}</h3>
            <p class="stats-label">Total Nilai Kontrak</p>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="stats-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                <i class="fas fa-check-circle"></i>
            </div>
            <h3 class="stats-number">Rp {{ number_format($analyticsData['total_nilai_realisasi'] ?? 0, 0, ',', '.') }}</h3>
            <p class="stats-label">Total Nilai Realisasi</p>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="stats-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                <i class="fas fa-percentage"></i>
            </div>
            <h3 class="stats-number">{{ $analyticsData['total_nilai_kontrak'] > 0 ? round(($analyticsData['total_nilai_realisasi'] / $analyticsData['total_nilai_kontrak']) * 100) : 0 }}%</h3>
            <p class="stats-label">Tingkat Realisasi</p>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="stats-icon" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 class="stats-number">{{ $analyticsData['kontrak_by_status']['aktif'] ?? 0 }}</h3>
            <p class="stats-label">Kontrak Aktif</p>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <h3 class="section-title">
            <i class="fas fa-bolt"></i>
            Akses Cepat
        </h3>
        <div class="quick-actions">
            <a href="{{ route('kantor.index') }}" class="action-btn">
                <i class="fas fa-building"></i>
                <span>Kelola Kantor</span>
            </a>
            <a href="{{ route('kontrak.index') }}" class="action-btn">
                <i class="fas fa-file-contract"></i>
                <span>Kelola Kontrak</span>
            </a>
            <a href="{{ route('peta.index') }}" class="action-btn">
                <i class="fas fa-map"></i>
                <span>Lihat Peta</span>
            </a>
            <a href="{{ route('analytics.index') }}" class="action-btn">
                <i class="fas fa-chart-line"></i>
                <span>Analytics</span>
            </a>
        </div>
    </div>
</div>

<script>
// Initialize Charts
document.addEventListener('DOMContentLoaded', function() {
    // Bar Chart - Real Data
    const barCtx = document.getElementById('barChart').getContext('2d');
    const realisasiDataAttr = document.getElementById('barChart').getAttribute('data-realisasi');
    const realisasiData = realisasiDataAttr ? JSON.parse(realisasiDataAttr) : [];
    const labels = realisasiData.map(item => item.month_name);
    const data = realisasiData.map(item => item.total);
    
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: labels.length > 0 ? labels : ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
            datasets: [{
                label: 'Realisasi',
                data: data.length > 0 ? data : [0, 0, 0, 0, 0, 0],
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Pie Chart - Real Data
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    const statusStatsAttr = document.getElementById('pieChart').getAttribute('data-status');
    const statusStats = statusStatsAttr ? JSON.parse(statusStatsAttr) : {};
    const pieLabels = ['Kantor Aktif', 'Kantor Non-Aktif', 'Gedung Aktif', 'Gedung Non-Aktif'];
    const pieData = [
        statusStats.kantor_aktif || 0,
        statusStats.kantor_tidak_aktif || 0,
        statusStats.gedung_aktif || 0,
        statusStats.gedung_tidak_aktif || 0
    ];
    
    // Filter out zero values for better visualization
    const filteredLabels = [];
    const filteredData = [];
    const filteredColors = [
        'rgba(59, 130, 246, 0.8)',
        'rgba(239, 68, 68, 0.8)',
        'rgba(16, 185, 129, 0.8)',
        'rgba(245, 158, 11, 0.8)'
    ];
    
    pieLabels.forEach((label, index) => {
        if (pieData[index] > 0) {
            filteredLabels.push(label);
            filteredData.push(pieData[index]);
        }
    });
    
    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: filteredLabels.length > 0 ? filteredLabels : ['No Data'],
            datasets: [{
                data: filteredData.length > 0 ? filteredData : [1],
                backgroundColor: filteredData.length > 0 ? filteredColors.slice(0, filteredData.length) : ['rgba(156, 163, 175, 0.8)'],
                borderWidth: 0
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

    // Initialize Map with Real Data
    console.log('Starting map initialization...');
    
    // Immediately show fallback if Leaflet is not available
    if (typeof L === 'undefined') {
        console.log('Leaflet not available - showing fallback immediately');
        const loadingElement = document.getElementById('map-loading');
        const fallbackElement = document.getElementById('map-fallback');
        
        if (loadingElement) {
            loadingElement.style.display = 'none';
        }
        if (fallbackElement) {
            fallbackElement.style.display = 'block';
        }
        return;
    }
    
    // Simple timeout to show fallback if map doesn't load
    setTimeout(function() {
        const loadingElement = document.getElementById('map-loading');
        const fallbackElement = document.getElementById('map-fallback');
        
        if (loadingElement && loadingElement.style.display !== 'none') {
            console.log('Map loading timeout - showing fallback');
            loadingElement.style.display = 'none';
            if (fallbackElement) {
                fallbackElement.style.display = 'block';
            }
        }
    }, 1000); // 1 second timeout
    
    // Check if Leaflet is loaded
    if (typeof L === 'undefined') {
        console.error('Leaflet is not loaded!');
        const loadingElement = document.getElementById('map-loading');
        const fallbackElement = document.getElementById('map-fallback');
        
        if (loadingElement) {
            loadingElement.style.display = 'none';
        }
        if (fallbackElement) {
            fallbackElement.style.display = 'block';
        }
        return;
    }
    
    try {
        // Hide loading indicator
        const loadingElement = document.getElementById('map-loading');
        if (loadingElement) {
            loadingElement.style.display = 'none';
        }
        
        const kantorDataAttr = document.getElementById('map').getAttribute('data-kantor');
        const kantorData = kantorDataAttr ? JSON.parse(kantorDataAttr) : [];
        console.log('Kantor data:', kantorData);
        
        // Set initial view
        let initialLat = -6.2088;
        let initialLng = 106.8456;
        let initialZoom = 10;
        
        if (kantorData.length > 0 && kantorData[0].latitude && kantorData[0].longitude) {
            initialLat = parseFloat(kantorData[0].latitude);
            initialLng = parseFloat(kantorData[0].longitude);
            initialZoom = 12;
        }
        
        const map = L.map('map').setView([initialLat, initialLng], initialZoom);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        
        // Add markers
        let hasValidMarkers = false;
        kantorData.forEach(function(kantor) {
            if (kantor.latitude && kantor.longitude && 
                !isNaN(parseFloat(kantor.latitude)) && 
                !isNaN(parseFloat(kantor.longitude))) {
                
                const lat = parseFloat(kantor.latitude);
                const lng = parseFloat(kantor.longitude);
                
                L.marker([lat, lng]).addTo(map)
                    .bindPopup(`
                        <div style="min-width: 200px;">
                            <h6 style="margin: 0 0 8px 0; color: #3b82f6;">${kantor.nama_kantor || 'Kantor'}</h6>
                            <p style="margin: 0 0 4px 0; font-size: 12px; color: #6b7280;">${kantor.alamat || 'Alamat tidak tersedia'}</p>
                            <p style="margin: 0; font-size: 12px; color: #6b7280;">
                                Status: <span style="color: ${kantor.status_kantor === 'aktif' ? '#10b981' : '#ef4444'};">${kantor.status_kantor || 'Unknown'}</span>
                            </p>
                        </div>
                    `);
                hasValidMarkers = true;
            }
        });
        
        // If no valid markers, add default
        if (!hasValidMarkers) {
            L.marker([-6.2088, 106.8456]).addTo(map)
                .bindPopup('Kantor Pusat Jakarta');
        }
        
        console.log('Map initialized successfully');
        
    } catch (error) {
        console.error('Error initializing map:', error);
        // Show fallback
        const loadingElement = document.getElementById('map-loading');
        const fallbackElement = document.getElementById('map-fallback');
        
        if (loadingElement) {
            loadingElement.style.display = 'none';
        }
        if (fallbackElement) {
            fallbackElement.style.display = 'block';
        }
    }
    
    try {
        // Hide loading indicator
        const loadingElement = document.getElementById('map-loading');
        if (loadingElement) {
            loadingElement.style.display = 'none';
        }
        
        const kantorDataAttr = document.getElementById('map').getAttribute('data-kantor');
        const kantorData = kantorDataAttr ? JSON.parse(kantorDataAttr) : [];
        console.log('Kantor data:', kantorData);
        console.log('Kantor data length:', kantorData.length);
        
        // Set initial view to first kantor or default
        let initialLat = -6.2088;
        let initialLng = 106.8456;
        let initialZoom = 10;
        
        if (kantorData.length > 0 && kantorData[0].latitude && kantorData[0].longitude) {
            initialLat = parseFloat(kantorData[0].latitude);
            initialLng = parseFloat(kantorData[0].longitude);
            initialZoom = 12;
        }
        
        const map = L.map('map').setView([initialLat, initialLng], initialZoom);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        
        // Add real kantor markers
        let hasValidMarkers = false;
        kantorData.forEach(function(kantor) {
            if (kantor.latitude && kantor.longitude && 
                !isNaN(parseFloat(kantor.latitude)) && 
                !isNaN(parseFloat(kantor.longitude))) {
                
                const lat = parseFloat(kantor.latitude);
                const lng = parseFloat(kantor.longitude);
                
                const marker = L.marker([lat, lng]).addTo(map);
                
                // Create popup content
                const popupContent = `
                    <div style="min-width: 200px;">
                        <h6 style="margin: 0 0 8px 0; color: #3b82f6; font-weight: 600;">${kantor.nama_kantor || 'Kantor'}</h6>
                        <p style="margin: 0 0 4px 0; font-size: 12px; color: #6b7280;">${kantor.alamat || 'Alamat tidak tersedia'}</p>
                        <p style="margin: 0; font-size: 12px; color: #6b7280;">
                            Status: <span style="color: ${kantor.status_kantor === 'aktif' ? '#10b981' : '#ef4444'}; font-weight: 600;">
                                ${kantor.status_kantor || 'Unknown'}
                            </span>
                        </p>
                        <p style="margin: 4px 0 0 0; font-size: 11px; color: #9ca3af;">
                            Koordinat: ${lat.toFixed(6)}, ${lng.toFixed(6)}
                        </p>
                    </div>
                `;
                
                marker.bindPopup(popupContent);
                hasValidMarkers = true;
            }
        });
        
        // If no valid markers, add default markers
        if (!hasValidMarkers) {
            console.log('No valid markers found, adding default markers');
            L.marker([-6.2088, 106.8456]).addTo(map)
                .bindPopup('<div style="min-width: 200px;"><h6 style="margin: 0 0 8px 0; color: #3b82f6;">Kantor Pusat Jakarta</h6><p style="margin: 0; font-size: 12px; color: #6b7280;">Lokasi default</p></div>');
            
            L.marker([-6.9175, 107.6191]).addTo(map)
                .bindPopup('<div style="min-width: 200px;"><h6 style="margin: 0 0 8px 0; color: #3b82f6;">Kantor Cabang Bandung</h6><p style="margin: 0; font-size: 12px; color: #6b7280;">Lokasi default</p></div>');
        }
        
        console.log('Map initialized successfully');
        
    } catch (error) {
        console.error('Error initializing map:', error);
        
        // Show error in loading element
        const loadingElement = document.getElementById('map-loading');
        if (loadingElement) {
            loadingElement.innerHTML = '<i class="fas fa-exclamation-triangle" style="margin-right: 8px; color: #ef4444;"></i>Error: ' + error.message;
        }
        
        // Fallback: create a simple map with default markers
        try {
            const map = L.map('map').setView([-6.2088, 106.8456], 10);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
            
            L.marker([-6.2088, 106.8456]).addTo(map)
                .bindPopup('Kantor Pusat Jakarta');
                
            console.log('Fallback map created successfully');
        } catch (fallbackError) {
            console.error('Fallback map also failed:', fallbackError);
            if (loadingElement) {
                loadingElement.innerHTML = '<i class="fas fa-exclamation-triangle" style="margin-right: 8px; color: #ef4444;"></i>Error: Tidak dapat memuat peta';
            }
        }
    }
    
    // Timeout fallback - if map doesn't load within 10 seconds
    setTimeout(function() {
        const mapElement = document.getElementById('map');
        const loadingElement = document.getElementById('map-loading');
        const fallbackElement = document.getElementById('map-fallback');
        
        if (loadingElement && loadingElement.style.display !== 'none') {
            console.log('Map loading timeout - showing fallback');
            loadingElement.style.display = 'none';
            if (fallbackElement) {
                fallbackElement.style.display = 'block';
            }
        }
    }, 10000);
    
    // Set progress bars
    const kontrakProgress = document.getElementById('kontrak-progress');
    const okupansiProgress = document.getElementById('okupansi-progress');
    
    if (kontrakProgress) {
        kontrakProgress.style.width = kontrakProgress.getAttribute('data-width') + '%';
    }
    
    if (okupansiProgress) {
        okupansiProgress.style.width = okupansiProgress.getAttribute('data-width') + '%';
    }
    
    });
</script>
@endsection