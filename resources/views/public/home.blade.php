@extends('layouts.public')

@section('title', 'PLN Icon Plus - Asset & Property Management')
@section('description', 'SIAP (Sistem Informasi Asset & Property) PLN Icon Plus - Platform terintegrasi untuk pengelolaan aset dan properti yang efisien, transparan, dan mudah diakses oleh seluruh stakeholder.')
@section('keywords', 'PLN Icon Plus, SIAP, Asset Management, Property Management, Sistem Informasi, Dashboard Admin, Peta Kantor, Directory Internal, Analytics Laporan')
@section('og_title', 'PLN Icon Plus - SIAP Asset & Property Management')
@section('og_description', 'Platform terintegrasi untuk pengelolaan aset dan properti PLN yang efisien, transparan, dan mudah diakses.')

@push('styles')
<style>
    /* Modern Hero Section with Image */
    .home-hero {
        position: relative;
        overflow: hidden;
        color: #1D5C7F;
        padding: 5rem 0;
        min-height: 500px;
        display: flex;
        align-items: center;
        background: #ffffff;
    }
    
    .home-hero::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 60%;
        height: 100%;
        background-image: url("{{ asset('images/logo/property.png') }}");
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        z-index: 1;
        opacity: 0.9;
        clip-path: polygon(20% 0, 100% 0, 100% 100%, 0% 100%);
    }
    
    .home-hero::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 60%;
        height: 100%;
        background: linear-gradient(90deg, #1D5C7F 0%, rgba(29, 92, 127, 0.8) 100%);
        opacity: 0.9;
        z-index: 2;
        clip-path: polygon(20% 0, 100% 0, 100% 100%, 0% 100%);
    }
    
    .hero-container {
        position: relative;
        z-index: 3;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 2rem;
        width: 100%;
    }
    
    @media (max-width: 992px) {
        .home-hero {
            min-height: 450px;
            padding: 3rem 0;
        }
        
        .home-hero::before,
        .home-hero::after {
            width: 100%;
            clip-path: none;
            opacity: 0.7;
        }
        
        .hero-content {
            max-width: 100%;
            margin: 0 auto;
            padding: 2.5rem 2rem;
            background: rgba(255, 255, 255, 0.95);
        }
        
        .hero-content h1 {
            font-size: 2.75rem;
        }
        
        .home-hero .hero-subtitle {
            font-size: 1.5rem;
        }
        
        .home-hero .hero-actions {
            margin-top: 1.5rem;
        }
    }
    
    .hero-content {
        max-width: 700px;
        text-align: left;
        padding: 3rem 2rem;
        background: rgba(255, 255, 255, 0.95);
        border-radius: 10px;
        border: 1px solid rgba(29, 92, 127, 0.1);
        box-shadow: 0 10px 40px rgba(29, 92, 127, 0.15);
        position: relative;
        z-index: 4;
    }
    
    .hero-content h1 {
        position: relative;
        display: inline-block;
        margin: 0 0 1rem 0;
        font-size: 3.5rem;
        font-weight: 800;
        line-height: 1.2;
        color: #1D5C7F;
    }
    
    .hero-content h1::after {
        content: '';
        position: absolute;
        bottom: -12px;
        left: 0;
        width: 80px;
        height: 5px;
        background-color: #1D5C7F;
        border-radius: 3px;
    }
    
    .home-hero .hero-subtitle {
        font-size: 1.75rem;
        font-weight: 600;
        margin: 0 0 1.5rem 0;
        color: #1D5C7F;
        line-height: 1.4;
    }
    
    .home-hero .hero-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        margin-top: 1.5rem;
    }
    
    .home-hero .hero-actions .btn {
        padding: 0.8rem 2rem;
        font-weight: 600;
        border-radius: 6px;
        transition: all 0.3s ease;
        font-size: 1.1rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 2px solid transparent;
        cursor: pointer;
        text-transform: none;
        letter-spacing: 0.5px;
    }
    
    .home-hero .btn-pln { 
        background: #1D5C7F; 
        border-color: #1D5C7F; 
        color: #ffffff;
        box-shadow: 0 4px 15px rgba(29, 92, 127, 0.3);
    }
    .home-hero .btn-pln:hover { 
        background: #174b6b; 
        border-color: #174b6b; 
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(29, 92, 127, 0.4);
    }
    .home-hero .btn-outline-pln { 
        border: 2px solid #1D5C7F; 
        color: #1D5C7F; 
        background: #ffffff;
        transition: all 0.3s ease;
    }
    .home-hero .btn-outline-pln:hover { 
        background: #1D5C7F; 
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(29, 92, 127, 0.3);
    }
    
    @media (max-width: 768px) {
        .home-hero {
            min-height: 400px;
            padding: 2rem 0;
        }
        
        .hero-content {
            padding: 2rem 1.5rem;
        }
        
        .hero-content h1 {
            font-size: 2.25rem;
        }
        
        .home-hero .hero-subtitle {
            font-size: 1.25rem;
            margin-bottom: 1.25rem;
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
    }
</style>
@endpush

@section('content')
<!-- Hero Section Clean -->
<div class="home-hero">
    <div class="hero-container">
        <div class="hero-content">
            <h1>SIAP</h1>
            <div class="hero-subtitle">Sistem Informasi Asset & Property (SIAP)</div>
            <p>Platform terintegrasi untuk pengelolaan aset dan properti yang efisien, transparan, dan mudah diakses oleh seluruh stakeholder.</p>
            <div class="hero-actions">
                <a href="{{ route('public.peta') }}" class="btn btn-pln" data-loading>
                    <i class="fas fa-map me-2"></i>Lihat Peta Lokasi
                </a>
                <a href="{{ route('public.directory') }}" class="btn btn-outline-pln" data-loading>
                    <i class="fas fa-building me-2"></i>Directory Kantor
                </a>
            </div>
        </div>
    </div>
</div>

<!-- System Info Section -->
<section class="system-info-section py-5 bg-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Kenapa Sistem Ini Dibuat -->
                <div class="system-card mb-5">
                    <div class="d-flex align-items-center mb-4">
                        <div class="section-icon me-3">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <h2 class="section-title mb-0">Kenapa Sistem Ini Dibuat</h2>
                    </div>
                    <p class="system-desc mb-0">
                        Sistem Informasi Aset & Properti (SIAP) PLN hadir sebagai solusi terpadu untuk mengoptimalkan pengelolaan aset dan properti perusahaan. Dibangun dengan teknologi terkini, sistem ini memungkinkan pengelolaan aset yang lebih efisien, akurat, dan transparan, sekaligus mendukung pengambilan keputusan berbasis data yang lebih baik bagi seluruh jajaran manajemen PLN.
                    </p>
                </div>

                <!-- Keunggulan Sistem -->
                <div class="system-card">
                    <div class="d-flex align-items-center mb-4">
                        <div class="section-icon me-3">
                            <i class="fas fa-star"></i>
                        </div>
                        <h2 class="section-title mb-0">Keunggulan Sistem</h2>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="feature-item">
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
                            <div class="feature-item">
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
                            <div class="feature-item">
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
                            <div class="feature-item">
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

<style>
    /* System Info Section */
    .system-info-section {
        padding: 4rem 0;
        background-color: #ffffff;
        position: relative;
    }

    .system-card {
        background: #ffffff;
        border-radius: 8px;
        padding: 2.5rem;
        box-shadow: 0 5px 30px rgba(29, 92, 127, 0.08);
        border: 1px solid rgba(29, 92, 127, 0.1);
        transition: all 0.3s ease;
    }

    .system-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 40px rgba(29, 92, 127, 0.15);
    }

    .section-icon {
        width: 50px;
        height: 50px;
        background-color: rgba(29, 92, 127, 0.1);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #1D5C7F;
        font-size: 1.25rem;
    }

    .section-title {
        color: #1D5C7F;
        font-weight: 700;
        font-size: 1.75rem;
        position: relative;
    }

    .system-desc {
        color: #4a5568;
        line-height: 1.8;
        font-size: 1.1rem;
        margin-bottom: 0;
        padding-left: 63px;
    }

    .feature-item {
        display: flex;
        margin-bottom: 1.5rem;
    }

    .feature-icon {
        width: 36px;
        height: 36px;
        background-color: rgba(29, 92, 127, 0.1);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #1D5C7F;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .feature-content h4 {
        color: #1D5C7F;
        font-weight: 600;
        margin-bottom: 0.25rem;
        font-size: 1.1rem;
    }

    .feature-content p {
        color: #64748b;
        margin-bottom: 0;
        font-size: 0.95rem;
        line-height: 1.6;
    }

    @media (max-width: 767.98px) {
        .system-card {
            padding: 1.5rem;
        }
        
        .section-title {
            font-size: 1.5rem;
        }
        
        .system-desc {
            padding-left: 0;
            font-size: 1rem;
        }
        
        .feature-item {
            margin-bottom: 1.25rem;
        }
    }
</style>

<!-- Dashboard Internal Section -->
<section class="dashboard-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Dashboard Internal</h2>
            <p class="section-subtitle">Akses lengkap sistem manajemen kantor dan aset PLN</p>
        </div>
        
        <div class="row g-4">
            <!-- Dashboard Admin -->
            <div class="col-md-6 col-lg-3">
                <a href="{{ route('login') }}" class="dashboard-card">
                    <div class="dashboard-icon">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <h3>Dashboard Admin</h3>
                    <p>Kelola seluruh sistem manajemen kantor dan aset</p>
                    <span class="dashboard-link">Akses Sekarang <i class="fas fa-arrow-right"></i></span>
                </a>
            </div>
            
            <!-- Peta Kantor -->
            <div class="col-md-6 col-lg-3">
                <a href="{{ route('public.peta') }}" class="dashboard-card">
                    <div class="dashboard-icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h3>Peta Kantor</h3>
                    <p>Lihat lokasi kantor PLN di seluruh Indonesia</p>
                    <span class="dashboard-link">Lihat Peta <i class="fas fa-arrow-right"></i></span>
                </a>
            </div>
            
            <!-- Directory Internal -->
            <div class="col-md-6 col-lg-3">
                <a href="{{ route('public.directory') }}" class="dashboard-card">
                    <div class="dashboard-icon">
                        <i class="fas fa-address-book"></i>
                    </div>
                    <h3>Directory Internal</h3>
                    <p>Temukan informasi kontak dan struktur organisasi</p>
                    <span class="dashboard-link">Lihat Directory <i class="fas fa-arrow-right"></i></span>
                </a>
            </div>
            
            <!-- Analytics & Laporan -->
            <div class="col-md-6 col-lg-3">
                <a href="{{ route('login') }}" class="dashboard-card">
                    <div class="dashboard-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Analytics & Laporan</h3>
                    <p>Analisis data dan laporan kinerja aset</p>
                    <span class="dashboard-link">Lihat Laporan <i class="fas fa-arrow-right"></i></span>
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    /* Dashboard Internal Section */
    .dashboard-section {
        background: #ffffff;
        position: relative;
        overflow: hidden;
        padding: 4rem 0;
        border-top: 1px solid rgba(29, 92, 127, 0.1);
        border-bottom: 1px solid rgba(29, 92, 127, 0.1);
    }

    .section-title {
        color: #1D5C7F;
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        position: relative;
        display: inline-block;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 3px;
        background: #1D5C7F;
        border-radius: 3px;
    }

    .section-subtitle {
        color: #6c757d;
        font-size: 1.1rem;
        max-width: 700px;
        margin: 0 auto 2rem;
        line-height: 1.6;
    }

    .dashboard-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 2rem 1.5rem;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        transition: all 0.3s ease;
        border: 2px solid #e6f0f7;
        text-decoration: none;
        color: #1D5C7F;
        box-shadow: 0 4px 12px rgba(29, 92, 127, 0.05);
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(29, 92, 127, 0.1);
        border-color: #1D5C7F;
        background: #f8fbfd;
    }

    .dashboard-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        font-size: 2rem;
        background: linear-gradient(135deg, #1D5C7F 0%, #2A7BA5 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(29, 92, 127, 0.2);
        transition: all 0.3s ease;
    }
    
    .dashboard-card:hover .dashboard-icon {
        transform: scale(1.1);
        box-shadow: 0 6px 16px rgba(29, 92, 127, 0.3);
    }

    .bg-primary-light { background: linear-gradient(135deg, #1D5C7F 0%, #2A7BA5 100%); }
    .bg-success-light { background: linear-gradient(135deg, #0d6e1f 0%, #1e9e40 100%); }
    .bg-warning-light { background: linear-gradient(135deg, #d68a00 0%, #ffb700 100%); }
    .bg-info-light { background: linear-gradient(135deg, #0c6b7a 0%, #0d9dbd 100%); }

    .dashboard-card h3 {
        color: #1D5C7F;
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        letter-spacing: 0.5px;
    }

    .dashboard-card p {
        color: #5a6a7a;
        font-size: 0.95rem;
        margin-bottom: 1.5rem;
        flex-grow: 1;
        line-height: 1.6;
    }

    .dashboard-link {
        display: inline-flex;
        align-items: center;
        color: #1D5C7F;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        background: rgba(29, 92, 127, 0.08);
    }
    
    .dashboard-card:hover .dashboard-link {
        background: rgba(29, 92, 127, 0.15);
    }

    .dashboard-link i {
        margin-left: 0.5rem;
        transition: all 0.3s ease;
        font-size: 0.8em;
    }

    .dashboard-card:hover .dashboard-link i {
        transform: translateX(4px);
    }

    /* Responsive */
    @media (max-width: 992px) {
        .section-title {
            font-size: 1.75rem;
        }
        
        .section-subtitle {
            font-size: 1rem;
            padding: 0 1rem;
        }
    }

    @media (max-width: 768px) {
        .dashboard-section {
            padding: 3rem 0;
        }
        
        .dashboard-card {
            padding: 1.5rem 1rem;
        }
        
        .dashboard-icon {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }
    }
</style>
@endpush
