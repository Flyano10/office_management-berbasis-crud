@extends('layouts.public')

@section('title', 'PLN Icon Plus - Asset & Property Management')
@section('description', 'SIAP (Sistem Informasi Asset & Property) PLN Icon Plus - Platform terintegrasi untuk pengelolaan aset dan properti yang efisien, transparan, dan mudah diakses oleh seluruh stakeholder.')
@section('keywords', 'PLN Icon Plus, SIAP, Asset Management, Property Management, Sistem Informasi, Dashboard Admin, Peta Kantor, Directory Internal, Analytics Laporan')
@section('og_title', 'PLN Icon Plus - SIAP Asset & Property Management')
@section('og_description', 'Platform terintegrasi untuk pengelolaan aset dan properti PLN yang efisien, transparan, dan mudah diakses.')

@push('styles')
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

    /* Modern Hero Section */
    .home-hero {
        position: relative;
        overflow: hidden;
        color: var(--pln-blue);
        padding: 4rem 0;
        min-height: 500px;
        display: flex;
        align-items: center;
        background: linear-gradient(135deg, var(--pln-blue-bg) 0%, #ffffff 100%);
    }
    
    .home-hero::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 55%;
        height: 100%;
        background-image: url("{{ asset('images/logo/property.png') }}");
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        z-index: 1;
        opacity: 0.15;
        clip-path: polygon(25% 0, 100% 0, 100% 100%, 0% 100%);
    }
    
    .hero-container {
        position: relative;
        z-index: 3;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 2rem;
        width: 100%;
    }
    
    .hero-content {
        max-width: 650px;
        text-align: left;
        padding: 2.5rem;
        background: rgba(255, 255, 255, 0.98);
        border-radius: 8px;
        border: 1px solid rgba(33, 97, 140, 0.15);
        box-shadow: 0 4px 16px rgba(33, 97, 140, 0.1);
        position: relative;
        z-index: 4;
    }
    
    .hero-content h1 {
        position: relative;
        display: inline-block;
        margin: 0 0 1rem 0;
        font-size: 3.25rem;
        font-weight: 800;
        line-height: 1.2;
        color: var(--pln-blue);
    }
    
    .hero-content h1::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 0;
        width: 80px;
        height: 4px;
        background: var(--pln-blue);
        border-radius: 2px;
    }
    
    .home-hero .hero-subtitle {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 1.25rem 0 1rem 0;
        color: var(--pln-blue);
        line-height: 1.4;
    }
    
    .hero-content p {
        color: var(--text-gray);
        font-size: 1.0625rem;
        line-height: 1.7;
        margin-bottom: 1.5rem;
    }
    
    .home-hero .hero-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        margin-top: 1.5rem;
    }
    
    .home-hero .hero-actions .btn {
        padding: 0.875rem 2rem;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.2s ease;
        font-size: 1rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 2px solid transparent;
        cursor: pointer;
        text-transform: none;
        letter-spacing: 0.3px;
    }
    
    .home-hero .btn-pln { 
        background: var(--pln-blue); 
        border-color: var(--pln-blue); 
        color: #ffffff;
    }
    .home-hero .btn-pln:hover { 
        background: var(--pln-blue-dark); 
        border-color: var(--pln-blue-dark); 
    }
    .home-hero .btn-outline-pln { 
        border: 2px solid var(--pln-blue); 
        color: var(--pln-blue); 
        background: #ffffff;
    }
    .home-hero .btn-outline-pln:hover { 
        background: var(--pln-blue); 
        color: #ffffff;
    }
    
    /* Stats Section */
    .stats-section {
        background: white;
        padding: 3rem 0;
        border-top: 1px solid rgba(33, 97, 140, 0.1);
        border-bottom: 1px solid rgba(33, 97, 140, 0.1);
    }

    .stat-card {
        background: white;
        border-radius: 8px;
        padding: 2rem 1.5rem;
        text-align: center;
        border: 1px solid rgba(33, 97, 140, 0.15);
        transition: all 0.2s ease;
        height: 100%;
        box-shadow: 0 2px 6px rgba(33, 97, 140, 0.08);
    }

    .stat-card:hover {
        border-color: var(--pln-blue);
        box-shadow: 0 4px 16px rgba(33, 97, 140, 0.12);
    }

    .stat-icon {
        width: 64px;
        height: 64px;
        background: var(--pln-blue-lighter);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
        color: var(--pln-blue);
        font-size: 1.75rem;
    }

    .stat-card:hover .stat-icon {
        background: var(--pln-blue);
        color: white;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--pln-blue);
        margin-bottom: 0.5rem;
        line-height: 1;
    }

    .stat-label {
        color: var(--text-gray);
        font-size: 1rem;
        font-weight: 600;
        margin: 0;
    }

    /* System Info Section */
    .system-info-section {
        padding: 4rem 0;
        background: var(--pln-blue-bg);
        position: relative;
    }

    .system-card {
        background: #ffffff;
        border-radius: 8px;
        padding: 2.5rem;
        box-shadow: 0 2px 8px rgba(33, 97, 140, 0.08);
        border: 1px solid rgba(33, 97, 140, 0.15);
        transition: all 0.2s ease;
        margin-bottom: 2rem;
    }

    .system-card:hover {
        box-shadow: 0 6px 20px rgba(33, 97, 140, 0.12);
    }

    .section-icon {
        width: 56px;
        height: 56px;
        background: var(--pln-blue-lighter);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--pln-blue);
        font-size: 1.5rem;
    }

    .section-title {
        color: var(--pln-blue);
        font-weight: 700;
        font-size: 1.75rem;
        position: relative;
        margin: 0;
    }

    .system-desc {
        color: var(--text-gray);
        line-height: 1.8;
        font-size: 1.0625rem;
        margin: 1.5rem 0 0 0;
    }

    .feature-item {
        display: flex;
        margin-bottom: 1.5rem;
        padding: 1.25rem;
        background: var(--pln-blue-bg);
        border-radius: 8px;
        border: 1px solid rgba(33, 97, 140, 0.1);
        transition: all 0.2s ease;
    }

    .feature-item:hover {
        background: var(--pln-blue-lighter);
        border-color: var(--pln-blue);
    }

    .feature-icon {
        width: 48px;
        height: 48px;
        background: var(--pln-blue);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin-right: 1.25rem;
        flex-shrink: 0;
        font-size: 1.25rem;
    }

    .feature-content h4 {
        color: var(--pln-blue);
        font-weight: 700;
        margin-bottom: 0.5rem;
        font-size: 1.125rem;
    }

    .feature-content p {
        color: var(--text-gray);
        margin: 0;
        font-size: 0.9375rem;
        line-height: 1.6;
    }

    /* Dashboard Section */
    .dashboard-section {
        background: white;
        position: relative;
        overflow: hidden;
        padding: 4rem 0;
        border-top: 1px solid rgba(33, 97, 140, 0.1);
    }

    .dashboard-section .section-title {
        color: var(--pln-blue);
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        position: relative;
        display: inline-block;
    }

    .dashboard-section .section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 3px;
        background: var(--pln-blue);
        border-radius: 3px;
    }

    .section-subtitle {
        color: var(--text-gray);
        font-size: 1.0625rem;
        max-width: 700px;
        margin: 1.5rem auto 0;
        line-height: 1.6;
    }

    .dashboard-card {
        background: #ffffff;
        border-radius: 8px;
        padding: 2rem 1.5rem;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        transition: all 0.2s ease;
        border: 1px solid rgba(33, 97, 140, 0.15);
        text-decoration: none;
        color: var(--pln-blue);
        box-shadow: 0 2px 8px rgba(33, 97, 140, 0.08);
    }

    .dashboard-card:hover {
        box-shadow: 0 8px 24px rgba(33, 97, 140, 0.12);
        border-color: var(--pln-blue);
        background: var(--pln-blue-bg);
    }

    .dashboard-icon {
        width: 80px;
        height: 80px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        font-size: 2rem;
        background: var(--pln-blue);
        color: white;
        box-shadow: 0 2px 8px rgba(33, 97, 140, 0.2);
        transition: all 0.2s ease;
    }
    
    .dashboard-card:hover .dashboard-icon {
        box-shadow: 0 6px 16px rgba(33, 97, 140, 0.25);
    }

    .dashboard-card h3 {
        color: var(--pln-blue);
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        letter-spacing: 0.3px;
    }

    .dashboard-card p {
        color: var(--text-gray);
        font-size: 0.9375rem;
        margin-bottom: 1.5rem;
        flex-grow: 1;
        line-height: 1.6;
    }

    .dashboard-link {
        display: inline-flex;
        align-items: center;
        color: var(--pln-blue);
        font-weight: 600;
        font-size: 0.9375rem;
        transition: all 0.3s ease;
        padding: 0.625rem 1.25rem;
        border-radius: 50px;
        background: var(--pln-blue-lighter);
    }
    
    .dashboard-card:hover .dashboard-link {
        background: var(--pln-blue);
        color: white;
    }

    .dashboard-link i {
        margin-left: 0.5rem;
        transition: all 0.3s ease;
        font-size: 0.875rem;
    }

    .dashboard-card:hover .dashboard-link i {
        margin-left: 0.75rem;
    }

    /* Scroll Animation */
    .fade-in-up {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.6s ease, transform 0.6s ease;
    }

    .fade-in-up.visible {
        opacity: 1;
        transform: translateY(0);
    }

    .fade-in-left {
        opacity: 0;
        transform: translateX(-30px);
        transition: opacity 0.6s ease, transform 0.6s ease;
    }

    .fade-in-left.visible {
        opacity: 1;
        transform: translateX(0);
    }

    .fade-in-right {
        opacity: 0;
        transform: translateX(30px);
        transition: opacity 0.6s ease, transform 0.6s ease;
    }

    .fade-in-right.visible {
        opacity: 1;
        transform: translateX(0);
    }

    /* Responsive */
    @media (max-width: 992px) {
        .home-hero {
            min-height: 450px;
            padding: 3rem 0;
        }
        
        .home-hero::before {
            width: 100%;
            clip-path: none;
            opacity: 0.1;
        }
        
        .hero-content {
            max-width: 100%;
            margin: 0 auto;
            padding: 2rem 1.5rem;
        }
        
        .hero-content h1 {
            font-size: 2.5rem;
        }
        
        .home-hero .hero-subtitle {
            font-size: 1.25rem;
        }
    }

    @media (max-width: 768px) {
        .home-hero {
            min-height: 400px;
            padding: 2rem 0;
        }
        
        .hero-content {
            padding: 1.75rem 1.25rem;
        }
        
        .hero-content h1 {
            font-size: 2rem;
        }
        
        .home-hero .hero-subtitle {
            font-size: 1.125rem;
            margin-bottom: 1rem;
        }
        
        .home-hero .hero-actions {
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .home-hero .hero-actions .btn {
            width: 100%;
            text-align: center;
            justify-content: center;
        }

        .stat-card {
            padding: 1.5rem 1rem;
            margin-bottom: 1rem;
        }

        .stat-number {
            font-size: 2rem;
        }

        .system-card {
            padding: 1.75rem 1.5rem;
        }
        
        .section-title {
            font-size: 1.5rem;
        }
        
        .system-desc {
            font-size: 1rem;
        }
        
        .feature-item {
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .dashboard-section {
            padding: 3rem 0;
        }
        
        .dashboard-card {
            padding: 1.5rem 1rem;
            margin-bottom: 1rem;
        }
        
        .dashboard-icon {
            width: 64px;
            height: 64px;
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<div class="home-hero">
    <div class="hero-container">
        <div class="hero-content">
            <h1>SIAP</h1>
            <div class="hero-subtitle">Sistem Informasi Asset & Property</div>
            <p>Platform terintegrasi untuk pengelolaan aset dan properti yang efisien, transparan, dan mudah diakses oleh seluruh stakeholder PLN Icon Plus.</p>
            <div class="hero-actions">
                <a href="{{ route('public.peta') }}" class="btn btn-pln">
                    <i class="fas fa-map"></i>
                    <span>Lihat Peta Lokasi</span>
                </a>
                <a href="{{ route('public.help') }}" class="btn btn-outline-pln">
                    <i class="fas fa-phone"></i>
                    <span>Kontak Kami</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Stats Section -->
<section class="stats-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="stat-card fade-in-up">
                    <div class="stat-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="stat-number">{{ number_format($stats['total_kantor'] ?? 0) }}</div>
                    <div class="stat-label">Total Kantor</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card fade-in-up">
                    <div class="stat-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <div class="stat-number">{{ number_format($stats['total_gedung'] ?? 0) }}</div>
                    <div class="stat-label">Total Gedung</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card fade-in-up">
                    <div class="stat-icon">
                        <i class="fas fa-door-open"></i>
                    </div>
                    <div class="stat-number">{{ number_format($stats['total_ruang'] ?? 0) }}</div>
                    <div class="stat-label">Total Ruang</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- System Info Section -->
<section class="system-info-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Kenapa Sistem Ini Dibuat -->
                <div class="system-card fade-in-left">
                    <div class="d-flex align-items-center mb-4">
                        <div class="section-icon me-3">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <h2 class="section-title">Kenapa Sistem Ini Dibuat</h2>
                    </div>
                    <p class="system-desc">
                        Sistem Informasi Aset & Properti (SIAP) PLN Icon Plus hadir sebagai solusi terpadu untuk mengoptimalkan pengelolaan aset dan properti perusahaan. Dibangun dengan teknologi terkini, sistem ini memungkinkan pengelolaan aset yang lebih efisien, akurat, dan transparan, sekaligus mendukung pengambilan keputusan berbasis data yang lebih baik bagi seluruh jajaran manajemen PLN.
                    </p>
                </div>

                <!-- Keunggulan Sistem -->
                <div class="system-card fade-in-right">
                    <div class="d-flex align-items-center mb-4">
                        <div class="section-icon me-3">
                            <i class="fas fa-star"></i>
                        </div>
                        <h2 class="section-title">Keunggulan Sistem</h2>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="feature-item fade-in-up">
                                <div class="feature-icon">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="feature-content">
                                    <h4>Terintegrasi</h4>
                                    <p>Seluruh data aset dan properti terkelola dalam satu platform terpadu</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="feature-item fade-in-up">
                                <div class="feature-icon">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="feature-content">
                                    <h4>Real-time Monitoring</h4>
                                    <p>Pemantauan aset secara real-time dengan akurasi tinggi</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="feature-item fade-in-up">
                                <div class="feature-icon">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="feature-content">
                                    <h4>Pelaporan Otomatis</h4>
                                    <p>Generasi laporan otomatis untuk analisis yang lebih cepat</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="feature-item fade-in-up">
                                <div class="feature-icon">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="feature-content">
                                    <h4>User Friendly</h4>
                                    <p>Antarmuka intuitif yang mudah digunakan oleh semua pengguna</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Dashboard Internal Section -->
<section class="dashboard-section">
    <div class="container">
        <div class="text-center mb-5 fade-in-up">
            <h2 class="section-title">Dashboard Internal</h2>
            <p class="section-subtitle">Akses lengkap sistem manajemen kantor dan aset PLN Icon Plus</p>
        </div>
        
        <div class="row g-4">
            <!-- Dashboard Admin -->
            <div class="col-md-6 col-lg-3">
                <a href="{{ route('login') }}" class="dashboard-card fade-in-up">
                    <div class="dashboard-icon">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <h3>Dashboard Admin</h3>
                    <p>Kelola seluruh sistem manajemen kantor dan aset</p>
                    <span class="dashboard-link">
                        <span>Akses Sekarang</span>
                        <i class="fas fa-arrow-right"></i>
                    </span>
                </a>
            </div>
            
            <!-- Peta Kantor -->
            <div class="col-md-6 col-lg-3">
                <a href="{{ route('public.peta') }}" class="dashboard-card fade-in-up">
                    <div class="dashboard-icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h3>Peta Kantor</h3>
                    <p>Lihat lokasi kantor PLN di seluruh Indonesia</p>
                    <span class="dashboard-link">
                        <span>Lihat Peta</span>
                        <i class="fas fa-arrow-right"></i>
                    </span>
                </a>
            </div>
            
            <!-- Kontak -->
            <div class="col-md-6 col-lg-3">
                <a href="{{ route('public.help') }}" class="dashboard-card fade-in-up">
                    <div class="dashboard-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h3>Kontak Kami</h3>
                    <p>Lokasi kantor dan media sosial PLN Icon Plus</p>
                    <span class="dashboard-link">
                        <span>Lihat Kontak</span>
                        <i class="fas fa-arrow-right"></i>
                    </span>
                </a>
            </div>
            
            <!-- Analytics & Laporan -->
            <div class="col-md-6 col-lg-3">
                <a href="{{ route('login') }}" class="dashboard-card fade-in-up">
                    <div class="dashboard-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Analytics & Laporan</h3>
                    <p>Analisis data dan laporan kinerja aset</p>
                    <span class="dashboard-link">
                        <span>Lihat Laporan</span>
                        <i class="fas fa-arrow-right"></i>
                    </span>
                </a>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
// Scroll Animation
document.addEventListener('DOMContentLoaded', function() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, observerOptions);

    // Observe all elements with animation classes
    document.querySelectorAll('.fade-in-up, .fade-in-left, .fade-in-right').forEach(el => {
        observer.observe(el);
    });
});
</script>
@endpush
@endsection
