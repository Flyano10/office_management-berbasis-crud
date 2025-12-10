<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- SEO Meta Tags -->
    <title>@yield('title', 'PLN Icon Plus - Directory Kantor')</title>
    <meta name="description" content="@yield('description', 'Sistem Informasi Direktori Kantor PLN Icon Plus - Platform terintegrasi untuk pengelolaan direktori kantor yang efisien, transparan, dan mudah diakses oleh seluruh stakeholder.')">
    <meta name="keywords" content="@yield('keywords', 'PLN, Icon Plus, Direktori Kantor, Sistem Informasi, SIAP, Kantor PLN, Directory, Peta Lokasi')">
    <meta name="author" content="PLN Icon Plus">
    <meta name="robots" content="index, follow">
    <meta name="language" content="Indonesian">
    <meta name="revisit-after" content="7 days">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="@yield('og_title', 'PLN Icon Plus - Direktori Kantor')">
    <meta property="og:description" content="@yield('og_description', 'Platform terintegrasi untuk pengelolaan direktori kantor PLN yang efisien dan transparan.')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', asset('images/logo/pln-logo.png'))">
    <meta property="og:site_name" content="PLN Icon Plus">
    <meta property="og:locale" content="id_ID">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', 'PLN Icon Plus - Direktori Kantor')">
    <meta name="twitter:description" content="@yield('twitter_description', 'Platform terintegrasi untuk pengelolaan direktori kantor PLN yang efisien dan transparan.')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('images/logo/pln-logo.png'))">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/logo/pln-logo.png') }}">
    
    <!-- Preconnect for Performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    
    <!-- Font Google - Inter & Poppins untuk tampilan yang lebih modern -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Framework CSS Bootstrap 5 untuk layout responsive -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Icon library Font Awesome untuk icon-icon keren -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- CSS Leaflet untuk map yang interaktif -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        :root {
            --pln-primary: #1D5C7F;
            --pln-secondary: #1D5C7F;
            --pln-accent: #FFD700;
            --pln-dark: #1D5C7F;
            --pln-light: #E8F4F8;
            --pln-gray: #6B7280;
            --pln-light-gray: #F8FAFC;
        }
        
        body {
            font-family: 'Inter', 'Poppins', sans-serif;
            background-color: white;
        }
        
        .navbar {
            background: #ffffff !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid #e2e8f0 !important;
            padding: 0 !important;
            position: sticky;
            top: 0;
            z-index: 1030;
            transition: all 0.3s ease;
            height: 60px;
            display: flex;
            align-items: center;
        }

        .navbar.scrolled {
            padding: 0.25rem 0;
            box-shadow: 0 4px 18px rgba(29, 92, 127, 0.12);
            border-bottom: 1px solid rgba(29, 92, 127, 0.2);
            min-height: 56px;
        }
        
        .navbar .container {
            max-width: 1200px;
        }
        
        .navbar-toggler {
            border: none;
            color: #334155;
            padding: 0.25rem 0.5rem;
        }
        
        .navbar-toggler:focus {
            box-shadow: 0 0 0 0.15rem rgba(44, 106, 143, 0.25);
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%2851, 65, 85, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        
        .navbar-brand {
            font-weight: 700;
            color: #4A90A4 !important;
            font-size: 1.1rem;
            text-shadow: none;
            white-space: nowrap;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0;
            line-height: 1;
        }
        
        .navbar-brand:hover {
            color: #3A7A8E !important;
            transform: translateY(-1px);
        }
        
        .navbar-brand i {
            margin-right: 4px;
            font-size: 1.1rem;
        }
        
        .navbar-logo {
            height: 72px;
            width: auto;
            filter: none;
            transition: all 0.3s ease;
            flex-shrink: 0;
            position: relative;
            z-index: 10;
            margin: -12px 0;
        }

        .navbar-logo:hover {
            transform: scale(1.05);
            filter: drop-shadow(0 2px 4px rgba(44, 106, 143, 0.3));
        }
        
        .navbar-nav .nav-link {
            color: #5b6b7f !important;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 0.45rem 1.2rem;
            margin: 0 0.25rem;
            border-radius: 999px;
            transition: all 0.25s ease;
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .navbar-nav .nav-link:hover {
            background-color: rgba(29, 92, 127, 0.08);
            color: #1D5C7F !important;
        }

        .navbar-nav .nav-link.active {
            background-color: #0f4c81;
            color: #ffffff !important;
        }
        
        .navbar-nav .nav-link i {
            display: none;
        }
        
        /* Navbar Responsive untuk Mobile */
        @media (max-width: 768px) {
            .navbar {
                padding: 0.4rem 0;
                min-height: 55px;
            }
            
            .navbar.scrolled {
                padding: 0.25rem 0;
                min-height: 45px;
            }
            
            .navbar-brand {
                font-size: 0.95rem;
                color: #4A90A4 !important;
                gap: 0.4rem;
            }
            
            .navbar-logo {
                height: 70px;
                margin: -10px 0;
            }
            
            .navbar-nav .nav-link {
                padding: 0.6rem 1rem;
                margin: 0.25rem 0;
                text-align: center;
                font-size: 0.9rem;
                color: #64748b !important;
            }
            
            .navbar-nav .nav-link:hover {
                background-color: rgba(74, 144, 164, 0.1);
                border-radius: 8px;
            }
            
            .navbar-nav .nav-link.active {
                background-color: #4A90A4;
                color: white !important;
            }
            
            .navbar-nav .nav-link i {
                display: none;
            }
        }
        
        .btn-pln {
            background: var(--pln-primary);
            border-color: var(--pln-primary);
            color: white;
        }
        
        .btn-pln:hover {
            background: var(--pln-secondary);
            border-color: var(--pln-secondary);
            color: white;
        }
        
        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border-radius: 12px;
        }
        
        .table {
            border-radius: 8px;
            overflow: hidden;
        }
        
        .table thead th {
            background: var(--pln-light-gray);
            border: none;
            font-weight: 600;
            color: var(--pln-primary);
        }
        
        .table tbody tr:hover {
            background-color: #f8fafc;
        }
        
        .map-container {
            height: 600px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .map-container.enhanced {
            height: 700px;
        }
        
        .view-toggle {
            background: white;
            border-radius: 8px;
            padding: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .view-toggle .btn {
            border-radius: 6px;
        }
        
        .view-toggle .btn.active {
            background: var(--pln-primary);
            color: white;
        }
        
        .footer {
            background: var(--pln-primary);
            color: white;
            margin-top: 30px;
        }
        
        .footer-main {
            padding: 1rem 0;
        }
        
        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            padding: 0.75rem 0;
        }
        
        .footer-brand-section {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 0.75rem;
        }
        
        .footer-logo {
            width: 120px;
            height: 120px;
            object-fit: contain;
        }
        
        .footer-brand {
            flex: 1;
        }
        
        .footer-address-section,
        .footer-nav-section,
        .footer-contact-section {
            margin-bottom: 0;
        }
        
        .footer-copyright-section {
            text-align: center;
        }
        
        .footer-title {
            font-size: 0.85rem;
            font-weight: 600;
            color: white;
            margin-bottom: 0.25rem;
        }
        
        .footer-subtitle {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.8);
            margin: 0;
        }
        
        .footer-section-title {
            font-size: 0.8rem;
            font-weight: 600;
            color: white;
            margin-bottom: 0.4rem;
        }
        
        .footer-address {
            font-size: 0.65rem;
            line-height: 1.3;
        }
        
        .footer-address-line {
            margin: 0 0 0.1rem 0;
            color: rgba(255, 255, 255, 0.9);
        }
        
        .footer-address-line:last-child {
            margin-bottom: 0;
        }
        
        .footer-copyright {
            font-size: 0.65rem;
            color: rgba(255, 255, 255, 0.8);
            margin: 0;
        }
        
        .footer-nav {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
        }
        
        .footer-nav-link {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            font-size: 0.75rem;
            transition: all 0.3s ease;
            padding: 0.1rem 0;
        }
        
        .footer-nav-link:hover {
            color: white;
            text-decoration: underline;
            transform: translateX(5px);
        }
        
        .footer-contact {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
        }
        
        .footer-contact-line {
            display: flex;
            align-items: center;
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.9);
            margin: 0;
        }
        
        .footer-contact-line i {
            width: 16px;
            text-align: center;
        }
        
        /* Footer yang responsive untuk semua ukuran layar */
        @media (max-width: 768px) {
            .footer-main {
                padding: 0.75rem 0;
            }
            
            .footer-brand-section {
                flex-direction: row;
                align-items: center;
                margin-bottom: 1.5rem;
            }
            
            .footer-logo {
                width: 100px;
                height: 100px;
            }
            
            .footer-title {
                font-size: 0.85rem;
            }
            
            .footer-subtitle {
                font-size: 0.7rem;
            }
            
            .footer-section-title {
                font-size: 0.8rem;
                margin-bottom: 0.4rem;
            }
            
            .footer-address {
                font-size: 0.65rem;
                margin-bottom: 0.75rem;
            }
            
            .footer-nav {
                gap: 0.4rem;
                margin-bottom: 1rem;
            }
            
            .footer-nav-link {
                font-size: 0.75rem;
            }
            
            .footer-contact-line {
                font-size: 0.7rem;
            }
            
            .footer-copyright {
                font-size: 0.65rem;
            }
        }
        
        /* Reset margin dan padding untuk tampilan full screen tanpa gap putih */
        html, body {
            margin: 0 !important;
            padding: 0 !important;
            overflow-x: hidden;
            width: 100%;
            height: 100%;
        }
        
        .container-fluid {
            padding: 0 !important;
            margin: 0 !important;
        }
        
        /* Hilangkan gap putih di sekitar hero section */
        main {
            margin: 0 !important;
            padding: 0 !important;
        }
        
        /* Pastikan gambar hero section memenuhi lebar layar penuh */
        .hero-section-full,
        .hero-image-container,
        .hero-image-full {
            width: 100vw !important;
            height: 100vh !important;
        }
        
        /* Pastikan semua elemen menggunakan box-sizing border-box */
        * {
            box-sizing: border-box;
            font-family: 'Inter', 'Poppins', sans-serif;
        }
        
        /* Warna Teks Hero Section - Biru PLN untuk Kontras Tinggi */
        .hero-content-overlay h1 {
            color: #1D5C7F !important;
            font-family: 'Inter', 'Poppins', sans-serif !important;
            font-weight: 800 !important;
            text-shadow: 0 2px 10px rgba(255, 255, 255, 0.8), 0 0 30px rgba(255, 255, 255, 0.6) !important;
        }
        
        .hero-content-overlay h2 {
            color: #1D5C7F !important;
            font-family: 'Inter', 'Poppins', sans-serif !important;
            font-weight: 600 !important;
            opacity: 0.9;
            text-shadow: 0 2px 8px rgba(255, 255, 255, 0.8), 0 0 20px rgba(255, 255, 255, 0.5) !important;
        }
        
        .hero-content-overlay p {
            color: #1e293b !important;
            font-family: 'Inter', 'Poppins', sans-serif !important;
            font-weight: 500 !important;
            text-shadow: 0 1px 6px rgba(255, 255, 255, 0.7), 0 0 15px rgba(255, 255, 255, 0.4) !important;
        }
        
        /* Paksa style button untuk hero section */
        .hero-actions .btn-pln {
            background: #1D5C7F !important;
            border: none !important;
            color: white !important;
            box-shadow: 0 4px 15px rgba(29, 92, 127, 0.3) !important;
        }
        
        .hero-actions .btn-pln:hover {
            background: #15435F !important;
            border: none !important;
            color: white !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 20px rgba(29, 92, 127, 0.4) !important;
        }
        
        .hero-actions .btn-outline-pln {
            background: white !important;
            border: 2px solid #1D5C7F !important;
            color: #1D5C7F !important;
        }
        
        .hero-actions .btn-outline-pln:hover {
            background: #1D5C7F !important;
            border: 2px solid #1D5C7F !important;
            color: white !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 20px rgba(29, 92, 127, 0.4) !important;
        }
        
        .hero-section-full * {
            background-color: transparent;
        }
        
        /* Professional Hero Section */
        .hero-section-professional {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
            position: relative;
        }
        
        .hero-section-professional::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 40%;
            height: 100%;
            background: linear-gradient(135deg, rgba(44, 106, 143, 0.05) 0%, rgba(74, 155, 155, 0.05) 100%);
            z-index: 1;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
        }
        
        .hero-badge .badge {
            font-size: 0.9rem;
            font-weight: 500;
            border: 1px solid rgba(44, 106, 143, 0.2);
        }
        
        .stat-item {
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.06);
        }
        
        .stat-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        
        .hero-image img {
            transition: all 0.3s ease;
        }
        
        .hero-image:hover img {
            transform: scale(1.02);
        }
        
        /* Card Hover Effects */
        .hover-lift {
            transition: all 0.3s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15) !important;
        }
        
        .hover-lift-white {
            transition: all 0.3s ease;
        }
        
        .hover-lift-white:hover {
            transform: translateY(-5px);
            background: rgba(255,255,255,0.2) !important;
            box-shadow: 0 15px 35px rgba(0,0,0,0.25);
        }
        
        /* Custom Text Colors */
        .text-white-75 {
            color: rgba(255,255,255,0.75) !important;
        }
        
        .text-white-50 {
            color: rgba(255,255,255,0.5) !important;
        }
        
        /* Button Enhancements */
        .btn {
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        /* Responsive Improvements */
        @media (max-width: 768px) {
            .hero-section-professional::before {
                display: none;
            }
            
            .hero-content {
                text-align: center;
                padding: 2rem 0;
            }
        }
        
        /* Hero Section - Background dengan Kontras Seimbang */
        .hero-section-abstract {
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 !important;
            padding: 6rem 2rem 4.5rem;
            background:
                linear-gradient(120deg, rgba(17, 94, 133, 0.16) 0%, rgba(226, 240, 247, 0.78) 40%, rgba(243, 249, 253, 0.9) 100%),
                url('/images/logo/property.png') center/cover no-repeat fixed !important;
        }

        .hero-section-abstract::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(29, 92, 127, 0.18) 0%, rgba(255, 255, 255, 0.4) 55%, rgba(255, 255, 255, 0.25) 100%);
            z-index: 1;
        }
        
        /* Elemen-elemen background abstrak untuk dekorasi */
        .abstract-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }
        
        /* Banner biru di pojok kiri atas - DIHILANGKAN untuk full width */
        .blue-banner {
            display: none;
        }
        
        .banner-shape {
            display: none;
        }
        
        /* Logo PLN di pojok kanan atas */
        .pln-logo {
            position: absolute;
            top: 2.5rem;
            right: 3rem;
            z-index: 40;
        }
        
        .pln-logo-img {
            width: 140px;
            height: auto;
            object-fit: contain;
            filter: drop-shadow(0 12px 24px rgba(29, 92, 127, 0.15));
            transition: transform 0.3s ease, filter 0.3s ease;
        }
        
        .pln-logo:hover .pln-logo-img {
            transform: translateY(-4px);
            filter: drop-shadow(0 16px 32px rgba(29, 92, 127, 0.22));
        }
        
        
        /* Elemen gelombang di pojok kanan bawah */
        .wave-elements {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 200px;
            height: 150px;
            z-index: 2;
        }
        
        .wave-layer {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 100%;
            height: 50px;
            border-radius: 50% 0 0 0;
        }
        
        .wave-1 {
            display: none;
        }
        
        .wave-2 {
            display: none;
        }
        
        .wave-3 {
            display: none;
        }
        
        
        
        
        /* Animasi-animasi keren untuk efek visual */
        @keyframes pulse {
            0%, 100% {
                opacity: 0.4;
                transform: scale(1);
            }
            50% {
                opacity: 1;
                transform: scale(1.2);
            }
        }
        
        
        /* Desain responsive untuk background abstrak */
        @media (max-width: 768px) {
            .blue-banner {
                width: 200px;
                height: 60px;
            }
            
            .pln-logo {
                top: 20px;
                right: 15px;
            }
            
            .pln-logo-img {
                width: 100px;
            }
            
            .abstract-shapes {
                top: 60px;
                right: 15px;
            }
            
            
            .wave-elements {
                width: 150px;
                height: 120px;
            }
            
            
        }
        
        @media (max-width: 480px) {
            .blue-banner {
                width: 150px;
                height: 50px;
            }
            
            .pln-logo {
                top: 15px;
                right: 10px;
            }
            
            .pln-logo-img {
                width: 80px;
            }
            
            .abstract-shapes {
                top: 50px;
                right: 10px;
            }
            
            
            .wave-elements {
                width: 120px;
                height: 100px;
            }
            
            
        }
        
        /* Elemen background aset untuk dekorasi */
        .asset-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            opacity: 0.8;
        }
        
        .asset-item {
            position: absolute;
            width: 120px;
            height: 120px;
            background: white;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border: 2px solid #e5e7eb;
            animation: float 8s ease-in-out infinite;
            transition: all 0.3s ease;
        }
        
        .asset-item:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }
        
        .asset-item .asset-icon {
            font-size: 3rem;
            color: #1E3A8A;
            text-shadow: none;
        }
        
        /* Styling khusus untuk elemen aset */
        .ac-icon {
            color: #4A9B9B;
        }
        
        .kwh-icon {
            color: #F59E0B;
        }
        
        .genset-icon {
            color: #10B981;
        }
        
        .microwave-icon {
            color: #8B5CF6;
        }
        
        .transformer-icon {
            color: #EF4444;
        }
        
        .server-icon {
            color: #6B7280;
        }
        
        .printer-icon {
            color: #F97316;
        }
        
        .camera-icon {
            color: #EC4899;
        }
        
        /* Posisi elemen-elemen aset di halaman */
        .ac-unit-1 {
            top: 15%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .ac-unit-2 {
            top: 25%;
            right: 15%;
            animation-delay: 2s;
        }
        
        .kwh-meter {
            top: 40%;
            left: 5%;
            animation-delay: 1s;
        }
        
        .genset {
            top: 60%;
            right: 10%;
            animation-delay: 3s;
        }
        
        .microwave {
            top: 70%;
            left: 20%;
            animation-delay: 4s;
        }
        
        .transformer {
            top: 20%;
            left: 50%;
            animation-delay: 1.5s;
        }
        
        .server {
            top: 50%;
            left: 80%;
            animation-delay: 2.5s;
        }
        
        .printer {
            top: 80%;
            right: 30%;
            animation-delay: 3.5s;
        }
        
        .camera {
            top: 30%;
            left: 70%;
            animation-delay: 4.5s;
        }
        
        /* Animasi mengambang untuk elemen aset */
        @keyframes float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            25% {
                transform: translateY(-10px) rotate(2deg);
            }
            50% {
                transform: translateY(-5px) rotate(0deg);
            }
            75% {
                transform: translateY(-15px) rotate(-2deg);
            }
        }
        
        /* Badge hero section untuk informasi tambahan */
        .hero-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 4;
        }
        
        .hero-badge .badge-text {
            background: linear-gradient(135deg, #1D5C7F 0%, #2A7C9E 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(29, 92, 127, 0.2);
        }
        
        /* Desain responsive untuk background aset */
        @media (max-width: 768px) {
            .asset-item {
                width: 80px;
                height: 80px;
            }
            
            .asset-item .asset-icon {
                font-size: 2rem;
            }
            
            .ac-unit-1 {
                top: 10%;
                left: 5%;
            }
            
            .ac-unit-2 {
                top: 20%;
                right: 10%;
            }
            
            .kwh-meter {
                top: 35%;
                left: 3%;
            }
            
            .genset {
                top: 55%;
                right: 5%;
            }
            
            .microwave {
                top: 75%;
                left: 15%;
            }
            
            .transformer {
                top: 15%;
                left: 45%;
            }
            
            .server {
                top: 45%;
                left: 75%;
            }
            
            .printer {
                top: 85%;
                right: 25%;
            }
            
            .camera {
                top: 25%;
                left: 65%;
            }
        }
        
        @media (max-width: 480px) {
            .asset-item {
                width: 60px;
                height: 60px;
            }
            
            .asset-item .asset-icon {
                font-size: 1.5rem;
            }
            
            .hero-badge {
                top: 10px;
                right: 10px;
            }
            
            .hero-badge .badge-text {
                padding: 6px 12px;
                font-size: 0.8rem;
            }
        }
        
        /* Hero Section dengan gambar full screen - tampilan bersih dan profesional */
        .hero-section-full {
            height: 100vh;
            width: 100vw;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 0;
            background: linear-gradient(rgba(255, 255, 255, 0.7), rgba(255, 255, 255, 0.8)), url('/images/logo/property.png') !important;
            background-size: 120% !important;
            background-position: center top !important;
            background-repeat: no-repeat !important;
            min-height: 80vh;
        }
        
        
        
        .hero-content-overlay {
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns: minmax(0, 480px) minmax(240px, 300px);
            gap: 2.6rem;
            align-items: center;
            color: #1F2A37;
            padding: 3rem 3.2rem;
            margin: 0;
            background: rgba(255, 255, 255, 0.58);
            border-radius: 20px;
            backdrop-filter: blur(22px);
            border: 1px solid rgba(255, 255, 255, 0.45);
            box-shadow: 0 20px 48px rgba(22, 64, 97, 0.13);
            animation: fadeInUp 0.9s ease-out;
        }

        .hero-copy {
            position: relative;
        }

        .hero-copy::before {
            content: 'SIAP';
            position: absolute;
            top: -2rem;
            left: -0.25rem;
            font-size: 6.4rem;
            font-weight: 800;
            color: rgba(29, 92, 127, 0.07);
            letter-spacing: 0.16em;
            pointer-events: none;
            z-index: -1;
        }

        .hero-visual {
            position: relative;
            display: flex;
            align-items: stretch;
            justify-content: center;
            flex-direction: column;
            gap: 1.25rem;
        }

        .hero-visual::after {
            display: none;
        }

        .hero-badge {
            margin-bottom: 1.25rem;
        }

        .badge-siap {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            background: rgba(29, 92, 127, 0.12);
            color: #1D5C7F;
            font-weight: 600;
            font-size: 0.78rem;
            border-radius: 999px;
            padding: 0.32rem 1rem;
            border: 1px solid rgba(29, 92, 127, 0.2);
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }
        
        @keyframes glow {
            0%, 100% {
                box-shadow: 0 10px 30px rgba(14, 165, 233, 0.4);
            }
            50% {
                box-shadow: 0 15px 40px rgba(14, 165, 233, 0.7);
            }
        }
        
        .btn-pln:hover {
            animation: glow 2s ease-in-out infinite;
        }
        
        .btn-pln:hover::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            animation: pulse 0.6s ease-out;
        }
        
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .hero-subtitle {
            animation: fadeInUp 1s ease-out 0.2s both;
        }
        
        .hero-description {
            animation: fadeInUp 1s ease-out 0.4s both;
        }
        
        .hero-actions {
            animation: fadeInUp 1s ease-out 0.6s both;
        }
        
        .hero-badge {
            margin-bottom: 1.5rem;
        }
        
        .hero-badge .badge {
            background: rgba(255, 255, 255, 0.15) !important;
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(20px);
            font-size: 0.7rem;
            padding: 0.4rem 1.5rem;
            border-radius: 50px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .hero-badge .badge:hover {
            background: rgba(255, 255, 255, 0.25) !important;
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }
        
        .hero-title {
            font-size: 3.1rem;
            font-weight: 800;
            margin-bottom: 0.75rem;
            letter-spacing: 0.05em;
            color: #15445D !important;
            line-height: 1.08;
            position: relative;
            padding-bottom: 0.85rem;
        }

        .hero-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 82px;
            height: 4px;
            border-radius: 999px;
            background: linear-gradient(90deg, #1D5C7F 0%, rgba(29, 92, 127, 0.12) 100%);
        }

        .hero-subtitle {
            font-size: 1.35rem;
            margin-bottom: 1rem;
            font-weight: 600;
            color: #2C6A8F !important;
            line-height: 1.3;
        }

        .hero-description {
            font-size: 0.98rem;
            margin-bottom: 1.6rem;
            line-height: 1.7;
            max-width: 500px;
            color: #1F2A37 !important;
        }
        
        .hero-actions {
            display: flex;
            gap: 0.9rem;
            justify-content: flex-start;
            flex-wrap: wrap;
            margin-top: 0.6rem;
            margin-bottom: 0.6rem;
            padding: 0;
        }

        .hero-actions .btn {
            padding: 0.78rem 1.6rem;
            font-weight: 600;
            font-size: 0.94rem;
            border-radius: 999px;
            transition: all 0.3s ease;
            min-width: 152px;
            height: auto;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .hero-actions .btn-pln {
            background: #1D5C7F;
            border: 2px solid #1D5C7F;
            color: #ffffff;
            box-shadow: 0 14px 32px rgba(29, 92, 127, 0.13);
            font-size: 1.05rem;
        }
        
        .btn-pln::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-pln:hover::before {
            left: 100%;
        }
        
        .btn-pln:hover {
            background: #15435F;
            border-color: #174a66;
            transform: translateY(-2px);
            box-shadow: 0 18px 40px rgba(23, 74, 102, 0.24);
        }
        
        .btn-outline-pln {
            background: rgba(255, 255, 255, 0.9) !important;
            border: 2px solid #1D5C7F !important;
            color: #1D5C7F !important;
            box-shadow: 0 10px 24px rgba(29, 92, 127, 0.15);
        }
        
        .btn-outline-pln:hover {
            background: #1D5C7F !important;
            color: #ffffff !important;
        }
        
        .hero-highlight-card {
            position: relative;
            width: 100%;
            padding: 1.6rem 1.4rem;
            background: rgba(255, 255, 255, 0.94);
            border-radius: 20px;
            border: 1px solid rgba(29, 92, 127, 0.15);
            box-shadow: 0 12px 32px rgba(29, 92, 127, 0.12);
            text-align: left;
        }

        .hero-highlight-card.secondary {
            background: rgba(255, 255, 255, 0.82);
        }

        .hero-highlight-card h3 {
            font-size: 1.25rem;
            color: #1D5C7F;
            font-weight: 700;
            margin-bottom: 0.35rem;
        }

        .hero-highlight-icon {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1D5C7F;
            font-size: 1.1rem;
            flex-shrink: 0;
        }
        
        .hero-highlight-stack {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.8rem;
        }

        .hero-more-link {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-weight: 600;
            font-size: 0.95rem;
            color: #1D5C7F;
            text-decoration: none;
            transition: color 0.2s ease, transform 0.2s ease;
        }

        .hero-more-link:hover {
            color: #15445D;
            transform: translateX(2px);
        }
        
        /* Section informasi perusahaan */
        .company-info-section {
            background: transparent;
            padding: 4rem 0 4.5rem;
            position: relative;
        }
        
        .company-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.18em;
            color: #1D5C7F;
            background: transparent;
            border: none;
            padding: 0;
            border-radius: 0;
            margin-bottom: 1.25rem;
        }
        
        .company-info-title {
            font-size: 2.25rem;
            font-weight: 700;
            color: #123C53;
            margin-bottom: 1rem;
        }
        
        .company-subheading {
            font-size: 1.05rem;
            font-weight: 700;
            color: #1D5C7F;
            margin: 1.5rem 0 0.75rem;
            letter-spacing: 0.02em;
        }
        
        .company-subheading:first-of-type {
            margin-top: 0.5rem;
        }
        
        .company-info-description {
            color: #2C3E50;
            font-size: 1rem;
            line-height: 1.75;
            margin-bottom: 0.85rem;
        }
        
        .company-timeline-carousel {
            position: relative;
            background: transparent;
            border: none;
            border-radius: 0;
            padding: 1.5rem 0;
            box-shadow: none;
            overflow: visible;
        }
        
        .company-timeline-carousel::before,
        .company-timeline-carousel::after,
        .company-timeline-carousel.at-start::before,
        .company-timeline-carousel.at-end::after {
            display: none;
        }

        .timeline-track-wrapper {
            overflow: hidden;
            position: relative;
        }

        .timeline-track {
            position: relative;
            display: flex;
            gap: 1.75rem;
            padding: 0.75rem 0;
            scroll-behavior: auto;
            transform: translateX(0);
            transition: transform 0.45s ease;
        }

        .timeline-track::before {
            display: none;
        }

        .timeline-slide {
            min-width: 240px;
            max-width: 260px;
            background: transparent;
            border: none;
            border-radius: 0;
            box-shadow: none;
            padding: 0;
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .timeline-slide::before {
            display: none;
        }

        .slide-year {
            font-weight: 700;
            font-size: 1.15rem;
            color: #1D5C7F;
            letter-spacing: 0.05em;
        }

        .timeline-slide p {
            margin: 0;
            color: #2C3E50;
            line-height: 1.65;
            font-size: 0.95rem;
        }

        .timeline-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: none;
            background: transparent;
            box-shadow: none;
            color: #1D5C7F;
            z-index: 4;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.25s ease;
        }

        .timeline-nav:hover {
            transform: translateY(-50%) translateY(-4px);
            background: rgba(29, 92, 127, 0.12);
            color: #1D5C7F;
            box-shadow: none;
        }

        .timeline-nav.prev {
            left: 1.1rem;
        }

        .timeline-nav.next {
            right: 1.1rem;
        }

        .timeline-nav.disabled,
        .timeline-nav:disabled {
            opacity: 0.35;
            pointer-events: none;
            transform: translateY(-50%);
        }

        .company-feature-card {
            background: transparent;
            border-radius: 0;
            border: none;
            padding: 2rem 1.9rem;
            box-shadow: none;
            transition: all 0.35s ease;
            position: relative;
            overflow: visible;
            height: 100%;
        }

        .company-feature-card.mission {
            background: transparent;
            box-shadow: none;
            padding: 2.2rem 2.1rem;
        }

        .company-feature-card h3 {
            font-size: 1.4rem;
            font-weight: 700;
            color: #123C53;
            margin-bottom: 1rem;
        }

        .company-vision-mission {
            margin-top: 1.8rem;
        }

        .company-feature-card p {
            color: #475569;
            margin-bottom: 1rem;
        }

        .company-feature-card ul {
            padding-left: 1.1rem;
            margin: 0;
            color: #475569;
            display: grid;
            gap: 0.5rem;
        }

        .company-feature-card li {
            line-height: 1.6;
        }

        .feature-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            background: linear-gradient(135deg, rgba(29, 92, 127, 0.2) 0%, rgba(29, 92, 127, 0.05) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1D5C7F;
            font-size: 1.25rem;
            margin-bottom: 1.2rem;
        }

        .company-values {
            margin-top: 3.5rem;
        }

        .values-title {
            font-size: 1.65rem;
            font-weight: 700;
            color: #123C53;
            margin-bottom: 1.6rem;
        }

        .company-values-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
        }

        .company-value-card {
            background: transparent;
            border-radius: 0;
            border: none;
            padding: 1.6rem 1.5rem;
            box-shadow: none;
            height: 100%;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .value-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.75rem;
        }

        .value-label {
            font-size: 0.9rem;
            font-weight: 700;
            color: #123C53;
            letter-spacing: 0.04em;
        }

        .company-value-card p {
            margin: 0;
            color: #475569;
            line-height: 1.6;
        }

        .company-value-card ul {
            margin: 0;
            padding-left: 1.1rem;
            color: #64748B;
            display: grid;
            gap: 0.35rem;
        }

        @media (max-width: 992px) {
            .company-info-section {
                padding: 3rem 0;
            }

            .company-timeline-carousel {
                padding: 2.1rem 3rem;
            }

            .timeline-slide {
                min-width: 220px;
                max-width: 240px;
                padding: 1.5rem 1.3rem;
            }

            .timeline-nav {
                width: 40px;
                height: 40px;
            }

            .company-timeline-carousel {
                margin-top: 1.5rem;
            }

            .company-feature-card {
                padding: 1.7rem 1.6rem;
            }

            .company-feature-card.mission {
                padding: 1.85rem 1.75rem;
            }
        }

        @media (max-width: 768px) {
            .company-values-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .company-timeline-carousel {
                padding: 1.8rem 2.3rem;
            }

            .timeline-track {
                gap: 1.25rem;
            }

            .timeline-slide {
                min-width: 70%;
                max-width: 70%;
            }
        }

        @media (max-width: 576px) {
            .company-pill {
                font-size: 0.7rem;
                letter-spacing: 0.14em;
            }

            .company-info-title {
                font-size: 1.8rem;
            }

            .company-timeline-carousel {
                padding: 1.45rem 1.4rem 2.6rem;
            }

            .company-timeline-carousel::before,
            .company-timeline-carousel::after {
                width: 48px;
            }

            .timeline-track {
                gap: 0.85rem;
            }

            .timeline-slide {
                min-width: 88%;
                max-width: 88%;
            }

            .timeline-nav.disabled,
            .timeline-nav:disabled {
                transform: none;
            }
        }
        
        .stat-icon:hover {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 12px 35px rgba(44, 106, 143, 0.4);
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #1D5C7F;
            line-height: 1;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            font-size: 1rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }
        
        .stat-description {
            font-size: 0.9rem;
            color: #6b7280;
            line-height: 1.4;
        }
        
        /* Section Tentang PLN - Modern & Clean Design */
        .company-info-section {
            background: transparent;
            padding: 5rem 0;
            position: relative;
        }
        
        .company-info-section::before {
            display: none;
        }
        
        .company-info-section .container {
            position: relative;
            z-index: 2;
        }
        
        .company-info-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1D5C7F;
            margin-bottom: 0.75rem;
            letter-spacing: -0.02em;
            text-align: center;
        }
        
        .company-info-subtitle {
            font-size: 1rem;
            color: #64748b;
            text-align: center;
            margin-bottom: 2.5rem;
            font-weight: 400;
        }
        
        .company-info-card {
            background: transparent;
            border-radius: 0;
            padding: 1.8rem 1.5rem;
            border: none;
            box-shadow: none;
            height: 100%;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: visible;
            text-align: center;
        }
        
        .company-info-card::before {
            display: none;
        }
        
        .company-info-card:hover {
            transform: translateY(-10px);
            box-shadow: none;
        }
        
        .company-info-icon {
            width: 55px;
            height: 55px;
            background: linear-gradient(135deg, #4A90A4 0%, #5BA0B4 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.4rem;
            margin: 0 auto 1.2rem;
            box-shadow: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .company-info-icon:hover {
            transform: scale(1.15) rotate(10deg);
            box-shadow: none;
        }
        
        .company-info-label {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1D5C7F;
            margin-bottom: 0.8rem;
            letter-spacing: -0.02em;
        }
        
        .company-info-description {
            font-size: 0.95rem;
            color: #64748b;
            line-height: 1.7;
        }
        
        /* Utility ukuran ikon SVG agar konsisten di seluruh publik */
        .icon-16 { width: 16px; height: 16px; }
        .icon-18 { width: 18px; height: 18px; }
        .icon-20 { width: 20px; height: 20px; }
        .icon     { vertical-align: -2px; display: inline-block; }
        
        /* Section Dashboard Internal */
        .actions-section {
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
            padding: 4rem 0;
            position: relative;
            border-top: 1px solid rgba(44, 106, 143, 0.1);
        }
        
        .actions-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1D5C7F;
            margin-bottom: 0.75rem;
            letter-spacing: -0.02em;
            text-align: center;
        }
        
        .actions-subtitle {
            font-size: 1rem;
            color: #64748b;
            font-weight: 400;
            max-width: 600px;
            margin: 0 auto 2rem;
            text-align: center;
        }
        
        /* Styling untuk tombol Dashboard Internal */
        .actions-section .btn {
            font-weight: 600;
            font-size: 0.9rem;
            padding: 0.8rem 1.5rem;
            border-radius: 10px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            letter-spacing: 0.02em;
            min-width: 160px;
            margin: 0.4rem;
        }
        
        .actions-section .btn-pln {
            background: linear-gradient(135deg, #4A90A4 0%, #3A7A8E 100%) !important;
            border: none !important;
            color: white !important;
            box-shadow: 0 8px 24px rgba(74, 144, 164, 0.3);
        }
        
        .actions-section .btn-pln:hover {
            background: linear-gradient(135deg, #3A7A8E 0%, #4A90A4 100%) !important;
            color: white !important;
            transform: translateY(-4px) scale(1.05);
            box-shadow: 0 12px 32px rgba(74, 144, 164, 0.4);
        }
        
        .actions-section .btn-outline-pln {
            background: white !important;
            border: 2px solid #4A90A4 !important;
            color: #4A90A4 !important;
            box-shadow: 0 4px 16px rgba(74, 144, 164, 0.15);
        }
        
        .actions-section .btn-outline-pln:hover {
            background: #4A90A4 !important;
            color: white !important;
            border-color: #4A90A4 !important;
            transform: translateY(-4px) scale(1.05);
            box-shadow: 0 12px 32px rgba(74, 144, 164, 0.35);
        }
        
        .btn-pln:focus {
            background: linear-gradient(135deg, var(--pln-primary) 0%, var(--pln-secondary) 100%) !important;
            color: white !important;
            box-shadow: 0 0 0 0.2rem rgba(44, 106, 143, 0.25);
        }
        
        .btn-outline-pln {
            border: 2px solid var(--pln-primary);
            color: var(--pln-primary);
            background: white;
            font-weight: 600;
            font-size: 1rem;
            padding: 12px 26px;
            border-radius: 12px;
            transition: all 0.4s ease;
            box-shadow: 0 4px 16px rgba(44, 106, 143, 0.15);
            letter-spacing: 0.025em;
        }
        
        .btn-outline-pln:hover {
            background: var(--pln-primary) !important;
            color: white !important;
            border-color: var(--pln-primary);
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 12px 32px rgba(44, 106, 143, 0.30);
        }
        
        /* Paksa override style button Bootstrap */
        .hero-section .btn-pln {
            background: linear-gradient(135deg, #2C6A8F 0%, #1E3A8A 100%) !important;
            border: none !important;
            color: white !important;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(44, 106, 143, 0.2);
        }
        
        .hero-section .btn-pln:hover {
            background: linear-gradient(135deg, #1E3A8A 0%, #2C6A8F 100%) !important;
            color: white !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(44, 106, 143, 0.3);
        }
        
        /* Desain responsive untuk navbar */
        @media (max-width: 768px) {
            .navbar-logo {
                height: 40px;
            }
            
            .hero-section {
                padding: 3rem 0;
            }
            
            .hero-title {
                font-size: 2rem;
            }
            
            .hero-accent {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.2rem;
            }
            
            .hero-description {
                font-size: 0.9rem;
            }
            
            .hero-actions {
                flex-direction: column;
                align-items: center;
                gap: 1rem;
            }
            
            .hero-actions .btn {
                width: 100%;
                max-width: 160px;
                min-width: auto;
                padding: 0.3rem 0.6rem;
                font-size: 0.7rem;
                height: 30px;
            }
            
            /* Old stats-section - removed */
            
            .stats-title {
                font-size: 1.8rem;
            }
            
            .stat-card {
                padding: 1.5rem;
            }
            
            .stat-icon {
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
            }
            
            .stat-number {
                font-size: 2rem;
            }
            
            
            .actions-section {
                padding: 3rem 0;
            }
            
            .actions-title {
                font-size: 1.5rem;
            }
            
            .actions-subtitle {
                font-size: 1rem;
            }
        }
        
        @media (max-width: 576px) {
            .hero-title {
                font-size: 1.8rem;
            }
            
            .hero-accent {
                font-size: 1.8rem;
            }
            
            .hero-subtitle {
                font-size: 1rem;
            }
            
            .hero-description {
                font-size: 0.85rem;
            }
            
            .stats-title {
                font-size: 1.6rem;
            }
            
            .actions-title {
                font-size: 1.4rem;
            }
        }
        
        /* Style popup map yang ditingkatkan */
        .custom-popup .leaflet-popup-content-wrapper {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            border: none;
        }
        
        .custom-popup .leaflet-popup-content {
            margin: 0;
            padding: 0;
            width: 280px !important;
        }
        
        .popup-container {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 300px;
        }
        
        .popup-header {
            padding: 12px;
            background: linear-gradient(135deg, #2C6A8F 0%, #1E4A5F 100%);
            color: white;
            border-radius: 8px 8px 0 0;
            min-height: auto;
        }
        
        .popup-header h6 {
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        
        .popup-header p {
            font-size: 0.75rem;
            margin-bottom: 0.5rem;
            line-height: 1.3;
        }
        
        .employee-summary {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 6px;
            padding: 8px;
        }
        
        .employee-summary .d-flex {
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        .employee-summary small {
            font-size: 0.7rem;
            white-space: nowrap;
        }
        
        .popup-body {
            padding: 10px;
            max-height: 200px;
            overflow-y: auto;
        }
        
        .employee-stats-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 0.5rem;
        }
        
        .employee-stats-card h6 {
            font-size: 0.85rem;
            margin-bottom: 0.75rem;
        }
        
        .stat-box {
            border: 1px solid #e9ecef;
            transition: all 0.2s ease;
        }
        
        .stat-box:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .stat-number {
            font-size: 1.2rem;
            font-weight: bold;
            margin: 0.25rem 0;
        }
        
        .stat-label {
            font-size: 0.7rem;
            margin: 0;
        }
        
        .total-employees {
            font-size: 0.8rem;
            margin-top: 0.75rem;
        }
        
        .nav-tabs .nav-link {
            border: none;
            border-radius: 0;
            font-size: 8px;
            padding: 4px 5px;
            color: #6c757d;
        }
        
        .nav-tabs .nav-link.active {
            background: #1D5C7F;
            color: white;
        }
        
        .popup-body .btn {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
        }
        
        .popup-body .btn-sm {
            font-size: 0.65rem;
            padding: 0.2rem 0.4rem;
        }
        
        .popup-body small {
            font-size: 0.7rem;
        }
        
        .popup-body .d-flex {
            margin-bottom: 0.25rem;
        }
        
        .popup-body .d-flex:last-child {
            margin-bottom: 0;
        }
        
        .popup-body .badge {
            font-size: 0.6rem;
            padding: 0.2rem 0.4rem;
        }
        
        .popup-body .fas {
            font-size: 0.7rem;
        }
        
        .popup-body .me-1 {
            margin-right: 0.25rem !important;
        }
        
        .popup-body .me-2 {
            margin-right: 0.5rem !important;
        }
        
        .popup-body .row {
            margin: 0;
        }
        
        .popup-body .col-6 {
            padding: 0.125rem;
        }
        
        .popup-body .g-2 {
            --bs-gutter-x: 0.25rem;
            --bs-gutter-y: 0.25rem;
        }
        
        .popup-body .mt-2 {
            margin-top: 0.25rem !important;
        }
        
        .popup-body .mt-3 {
            margin-top: 0.5rem !important;
        }
        
        .popup-body .mb-2 {
            margin-bottom: 0.25rem !important;
        }
        
        .popup-body .mb-3 {
            margin-bottom: 0.5rem !important;
        }
        
        .popup-body .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }
        
        .popup-body .loading-text {
            font-size: 0.7rem;
        }
        
        .popup-body .text-center {
            padding: 0.5rem;
        }
        
        .popup-body .py-3 {
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
        }
        
        .popup-body .w-100 {
            width: 100% !important;
        }
        
        .popup-body .text-muted {
            font-size: 0.7rem;
        }
        
        .popup-body .text-white-50 {
            font-size: 0.7rem;
        }
        
        .popup-body .text-white {
            font-size: 0.7rem;
        }
        
        .popup-body .text-primary {
            font-size: 0.7rem;
        }
        
        .popup-body .text-info {
            font-size: 0.7rem;
        }
        
        .popup-body .text-success {
            font-size: 0.7rem;
        }
        
        .popup-body .text-warning {
            font-size: 0.7rem;
        }
        
        .popup-body .text-danger {
            font-size: 0.7rem;
        }
        
        .popup-body .text-secondary {
            font-size: 0.7rem;
        }
        
        .nav-tabs .nav-link:hover {
            background: #f8f9fa;
            color: #1D5C7F;
        }

        /* Styling button dengan warna PLN */
        .btn-primary {
            background-color: #2C6A8F !important;
            border-color: #2C6A8F !important;
        }

        .btn-primary:hover {
            background-color: #1E4A5F !important;
            border-color: #1E4A5F !important;
        }

        .btn-primary:focus {
            background-color: #2C6A8F !important;
            border-color: #2C6A8F !important;
            box-shadow: 0 0 0 0.2rem rgba(44, 106, 143, 0.25) !important;
        }

        /* Styling badge dengan warna PLN */
        .badge.bg-primary {
            background-color: #2C6A8F !important;
        }

        .badge.bg-info {
            background-color: #1E4A5F !important;
        }
        
        /* Style untuk tab laporan inventaris */
        .laporan-inventaris-container {
            padding: 0;
        }
        
        .laporan-inventaris-container .form-label {
            font-weight: 600;
            color: var(--pln-primary);
            margin-bottom: 8px;
        }
        
        .laporan-inventaris-container .form-select {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .laporan-inventaris-container .form-select:focus {
            border-color: var(--pln-primary);
            box-shadow: 0 0 0 0.2rem rgba(44, 106, 143, 0.25);
        }
        
        .laporan-inventaris-container .table-responsive {
            max-height: 350px;
            overflow-y: auto;
            border: 1px solid #dee2e6;
            border-radius: 8px;
        }
        
        .laporan-inventaris-container .table {
            margin-bottom: 0;
        }
        
        .laporan-inventaris-container .table th {
            background-color: var(--pln-primary);
            color: white;
            font-weight: 600;
            font-size: 12px;
            padding: 10px 8px;
            border: none;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .laporan-inventaris-container .table td {
            padding: 8px;
            font-size: 12px;
            border: 1px solid #dee2e6;
        }
        
        .laporan-inventaris-container .table-secondary td {
            background-color: #f8f9fa;
            font-weight: 600;
            color: var(--pln-primary);
        }
        
        .laporan-inventaris-container .badge {
            font-size: 10px;
            padding: 4px 8px;
        }
        
        /* Perbaikan layout filter */
        .laporan-inventaris-container .mb-3 {
            margin-bottom: 15px !important;
        }
        
        .laporan-inventaris-container .form-select {
            width: 100% !important;
            min-width: 200px;
        }
        
        .laporan-inventaris-container .row {
            margin: 0;
        }
        
        .laporan-inventaris-container .col-md-6 {
            padding: 0 5px;
        }
        
        /* Modal untuk kontrak, inventaris, dan laporan inventaris */
        .laporan-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            display: none;
        }
        
        .laporan-modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .laporan-modal-content {
            background: white;
            border-radius: 12px;
            width: 90%;
            height: 85%;
            max-width: 1200px;
            max-height: 800px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            display: flex;
            flex-direction: column;
        }
        
        .laporan-modal-header {
            background: #1D5C7F;
            color: white;
            padding: 15px 20px;
            border-radius: 12px 12px 0 0;
            position: relative;
        }
        
        .laporan-modal-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .laporan-modal-body {
            padding: 20px;
            flex: 1;
            overflow-y: auto;
            background: #ffffff;
        }
        
        .laporan-modal-close {
            position: absolute;
            top: 15px;
            right: 20px;
            background: none;
            border: none;
            color: white;
            font-size: 28px;
            cursor: pointer;
            line-height: 1;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .laporan-modal-close:hover {
            color: #f8f9fa;
            opacity: 0.8;
        }
        
        /* Table styling untuk modal - dominan putih dengan font abu-abu/biru PLN */
        .laporan-modal-body .table {
            background: white;
            margin-bottom: 0;
        }
        
        .laporan-modal-body .table thead th {
            background: #1D5C7F;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 12px 10px;
            border: 1px solid #1D5C7F;
            text-align: center;
            vertical-align: middle;
        }
        
        .laporan-modal-body .table tbody td {
            background: white;
            color: #6b7280;
            font-size: 0.875rem;
            padding: 10px;
            border: 1px solid #e5e7eb;
            vertical-align: middle;
        }
        
        .laporan-modal-body .table tbody tr:hover {
            background: #f9fafb;
        }
        
        .laporan-modal-body .table tbody tr.table-secondary td {
            background: #f3f4f6;
            color: #1D5C7F;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .laporan-modal-body .table tbody td strong {
            color: #1D5C7F;
            font-weight: 600;
        }
        
        .laporan-modal-body .table tbody td small {
            color: #6b7280;
        }
        
        .laporan-modal-body .badge {
            font-size: 0.75rem;
            padding: 4px 8px;
            font-weight: 500;
        }
        
        .laporan-modal-body .badge.bg-primary {
            background: #1D5C7F !important;
        }
        
        .laporan-modal-body .badge.bg-info {
            background: #1D5C7F !important;
            opacity: 0.8;
        }
        
        .laporan-modal-body .badge.bg-secondary {
            background: #9ca3af !important;
            color: white;
        }
        
        .laporan-modal-body .badge.bg-success {
            background: #10b981 !important;
        }
        
        .laporan-modal-body .text-success {
            color: #10b981 !important;
        }
        
        .laporan-modal-body .btn-success {
            background: #10b981;
            border-color: #10b981;
        }
        
        .laporan-modal-body .btn-success:hover {
            background: #059669;
            border-color: #059669;
        }
        
        /* Style filter yang ditingkatkan */
        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            border: 2px solid #e5e7eb;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #1E40AF;
            box-shadow: 0 0 0 0.2rem rgba(30, 64, 175, 0.25);
        }
        
        .btn-pln {
            background: linear-gradient(135deg, #2D5A87 0%, #4A9B9B 100%);
            border: none;
            color: white;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-pln:hover {
            background: linear-gradient(135deg, #1E3A8A 0%, #2563EB 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3);
        }
        
        .btn-outline-secondary:hover {
            background: #6c757d;
            border-color: #6c757d;
        }
        
        .btn-outline-info:hover {
            background: #0dcaf0;
            border-color: #0dcaf0;
        }
        
        /* Style untuk tombol export */
        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-success:hover {
            background: linear-gradient(135deg, #218838 0%, #1ea085 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        }
        
        .btn-success:active {
            transform: translateY(0);
        }
        
        /* Animasi loading yang smooth */
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }
        
        .loading-text {
            animation: pulse 1.5s ease-in-out infinite;
        }
        
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .fa-spin {
            animation: fa-spin 1s infinite linear;
        }
        
        @keyframes fa-spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        /* Style untuk badge-badge */
        .badge {
            font-size: 0.75em;
            font-weight: 500;
        }
        
        .badge.bg-success {
            background-color: #10b981 !important;
        }
        
        .badge.bg-warning {
            background-color: #f59e0b !important;
        }
        
        .badge.bg-danger {
            background-color: #ef4444 !important;
        }
        
        .badge.bg-info {
            background-color: #06b6d4 !important;
        }
        
        .badge.bg-primary {
            background-color: #1E40AF !important;
        }
        
        /* Style untuk tabel kontrak */
        .kontrak-table-container {
            max-height: 400px;
            overflow-y: auto;
            overflow-x: auto;
        }
        
        .kontrak-table-container .table {
            margin-bottom: 0;
            font-size: 11px;
            min-width: 1200px;
        }
        
        .kontrak-table-container .table th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: #495057;
            padding: 6px 4px;
            font-size: 10px;
            white-space: nowrap;
        }
        
        .kontrak-table-container .table td {
            padding: 4px 3px;
            vertical-align: middle;
            border-bottom: 1px solid #dee2e6;
            font-size: 10px;
            white-space: nowrap;
        }
        
        .kontrak-table-container .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .kontrak-table-container .btn {
            font-size: 11px;
            padding: 4px 8px;
        }
        
        .kontrak-table-container .badge {
            font-size: 9px;
            padding: 2px 4px;
        }
        
        /* Scrollbar custom untuk tabel kontrak */
        .kontrak-table-container::-webkit-scrollbar {
            width: 6px;
        }
        
        .kontrak-table-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }
        
        .kontrak-table-container::-webkit-scrollbar-thumb {
            background: #1E40AF;
            border-radius: 3px;
        }
        
        .kontrak-table-container::-webkit-scrollbar-thumb:hover {
            background: #4A9B9B;
        }
        
        .inventaris-item, .kontrak-item {
            transition: background-color 0.2s;
        }
        
        .inventaris-item:hover, .kontrak-item:hover {
            background-color: #f8f9fa;
        }
        
        .inventaris-list, .kontrak-list {
            max-height: 250px;
            overflow-y: auto;
        }
        
        /* Scrollbar custom untuk list inventaris dan kontrak */
        .inventaris-list::-webkit-scrollbar,
        .kontrak-list::-webkit-scrollbar {
            width: 6px;
        }
        
        .inventaris-list::-webkit-scrollbar-track,
        .kontrak-list::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }
        
        .inventaris-list::-webkit-scrollbar-thumb,
        .kontrak-list::-webkit-scrollbar-thumb {
            background: #1E40AF;
            border-radius: 3px;
        }
        
        .inventaris-list::-webkit-scrollbar-thumb:hover,
        .kontrak-list::-webkit-scrollbar-thumb:hover {
            background: #4A9B9B;
        }
        
        @media (max-width: 768px) {
            .map-container {
                height: 400px;
            }
            
            .map-container.enhanced {
                height: 500px;
            }
            
            .custom-popup .leaflet-popup-content {
                width: 250px !important;
            }
            
            .popup-header {
                padding: 8px;
            }
            
            .popup-body {
                padding: 8px;
                max-height: 200px;
            }
            
            .custom-popup .leaflet-popup-content {
                width: 250px !important;
            }
            
            .nav-tabs .nav-link {
                font-size: 8px;
                padding: 4px 6px;
            }
            
            .form-label {
                font-size: 14px;
            }
            
            .btn {
                font-size: 14px;
                padding: 8px 16px;
            }
            
            .badge {
                font-size: 0.7em;
            }
            
            .kontrak-table-container {
                max-height: 250px;
            }
            
            .kontrak-table-container .table {
                font-size: 9px;
                min-width: 1000px;
            }
            
            .kontrak-table-container .table th,
            .kontrak-table-container .table td {
                padding: 3px 2px;
            }
            
            .kontrak-table-container .btn {
                font-size: 9px;
                padding: 2px 4px;
            }
            
            .btn-success {
                font-size: 10px;
                padding: 4px 8px;
            }
            
            .kontrak-table-container .table th {
                font-size: 8px;
                padding: 2px 1px;
            }
            
            .kontrak-table-container .table td {
                padding: 3px 2px;
            }
        }
        
        /* Styling untuk halaman peta */
        .peta-header-card {
            background: linear-gradient(135deg, #2C6A8F 0%, #1E4A5F 100%);
            border-radius: 10px;
            padding: 1.25rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 3px 12px rgba(44, 106, 143, 0.2);
        }
        
        .peta-header-content {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }
        
        .peta-header-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: white;
        }
        
        .peta-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: white;
            margin-bottom: 0.375rem;
        }
        
        .peta-subtitle {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
            margin: 0;
        }
        
        .peta-search-card {
            background: white;
            border-radius: 10px;
            padding: 1.25rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(44, 106, 143, 0.1);
        }
        
        .peta-search-header {
            margin-bottom: 1.25rem;
        }
        
        .peta-search-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1D5C7F;
            margin-bottom: 0.375rem;
        }
        
        .peta-search-subtitle {
            color: #6b7280;
            margin: 0;
            font-size: 0.9rem;
        }
        
        .peta-form-group {
            margin-bottom: 1rem;
        }
        
        .peta-form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.375rem;
            display: block;
            font-size: 0.9rem;
        }
        
        .peta-form-control,
        .peta-form-select {
            width: 100%;
            padding: 0.625rem 0.875rem;
            border: 1px solid #e5e7eb;
            border-radius: 7px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .peta-form-control:focus,
        .peta-form-select:focus {
            outline: none;
            border-color: #1D5C7F;
            box-shadow: 0 0 0 3px rgba(44, 106, 143, 0.1);
        }
        
        .peta-search-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            margin-top: 1.25rem;
        }
        
        .peta-btn {
            padding: 0.625rem 1.25rem;
            border-radius: 7px;
            font-weight: 600;
            font-size: 0.85rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
        }
        
        .peta-btn-primary {
            background: #1D5C7F;
            color: white;
        }
        
        .peta-btn-primary:hover {
            background: #1E4A5F;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(44, 106, 143, 0.3);
        }
        
        .peta-btn-outline {
            background: white;
            color: #1D5C7F;
            border: 2px solid #2C6A8F;
        }
        
        .peta-btn-outline:hover {
            background: #1D5C7F;
            color: white;
            transform: translateY(-2px);
        }
        
        .peta-btn-secondary {
            background: #6b7280;
            color: white;
        }
        
        .peta-btn-secondary:hover {
            background: #4b5563;
            transform: translateY(-2px);
        }
        
        .peta-map-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(44, 106, 143, 0.1);
            margin-bottom: 1.25rem;
        }
        
        .peta-map-header {
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .peta-map-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1D5C7F;
            margin: 0;
        }
        
        .peta-map-info {
            color: #6b7280;
            font-size: 0.9rem;
        }
        
        .peta-map-container {
            position: relative;
        }
        
        .peta-map {
            height: 600px;
            width: 100%;
        }
        
        /* Pindahkan zoom controls ke kanan */
        .leaflet-control-zoom {
            right: 15px !important;
            left: auto !important;
            top: 15px !important;
        }
        
        .leaflet-control-zoom a {
            background: rgba(255, 255, 255, 0.95) !important;
            border: 1px solid rgba(44, 106, 143, 0.3) !important;
            color: #2C6A8F !important;
            font-weight: 600 !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15) !important;
            backdrop-filter: blur(10px) !important;
            -webkit-backdrop-filter: blur(10px) !important;
        }
        
        .leaflet-control-zoom a:hover {
            background: #2C6A8F !important;
            color: white !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 12px rgba(44, 106, 143, 0.3) !important;
        }
        
        /* Overlay search yang compact dan tidak nutupin marker */
        .peta-compact-search {
            position: absolute;
            top: 15px;
            left: 15px;
            z-index: 1000;
        }
        
        .peta-search-toggle {
            background: white;
            border: 1px solid #2C6A8F;
            border-radius: 6px;
            padding: 0.5rem 0.75rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.375rem;
            font-weight: 600;
            color: #1D5C7F;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            font-size: 0.8rem;
            white-space: nowrap;
        }
        
        .peta-search-toggle:hover {
            background: #1D5C7F;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(44, 106, 143, 0.3);
        }
        
        .peta-search-toggle.active {
            background: #1D5C7F;
            color: white;
        }
        
        .peta-search-toggle i {
            font-size: 0.8rem;
        }
        
        .peta-search-panel {
            position: absolute;
            top: 40px;
            left: 0;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 8px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(44, 106, 143, 0.3);
            min-width: 280px;
            max-width: 320px;
            display: none;
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            animation: slideDown 0.3s ease-out;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .peta-search-header {
            background: linear-gradient(135deg, #2C6A8F 0%, #1E4A5F 100%);
            color: white;
            padding: 0.75rem 1rem;
            border-radius: 6px 6px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .peta-search-header h4 {
            margin: 0;
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        .peta-close-btn {
            background: none;
            border: none;
            color: white;
            font-size: 0.9rem;
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 3px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .peta-close-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.1);
        }
        
        .peta-search-content {
            padding: 0.75rem;
        }
        
        .peta-search-content .peta-form-group {
            margin-bottom: 0.5rem;
        }
        
        .peta-form-row {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }
        
        .peta-form-row .peta-form-select {
            flex: 1;
            font-size: 0.8rem;
            padding: 0.5rem 0.75rem;
        }
        
        .peta-search-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-top: 0.75rem;
        }
        
        .peta-search-actions .peta-btn {
            flex: 1;
            font-size: 0.8rem;
            padding: 0.5rem 0.75rem;
        }
        
        .peta-search-content .peta-form-group:last-of-type {
            margin-bottom: 1rem;
        }
        
        .peta-search-content .peta-form-label {
            font-size: 0.8rem;
        }
        
        /* Responsive untuk mobile */
        @media (max-width: 768px) {
            .peta-compact-search {
                top: 10px;
                left: 10px;
                right: 10px;
            }
            
            /* Zoom controls di mobile */
            .leaflet-control-zoom {
                right: 10px !important;
                top: 10px !important;
            }
            
            .peta-search-toggle {
                width: 100%;
                justify-content: center;
                font-size: 0.75rem;
                padding: 0.4rem 0.6rem;
            }
            
            .peta-search-panel {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 90%;
                max-width: 350px;
                max-height: 80vh;
                overflow-y: auto;
                z-index: 1001;
            }
            
            .peta-form-row {
                flex-direction: column;
                gap: 0.375rem;
            }
            
            .peta-search-actions {
                flex-direction: column;
            }
            
            .peta-search-actions .peta-btn {
                width: 100%;
            }
        }
        
        .peta-search-content .peta-form-control,
        .peta-search-content .peta-form-select {
            font-size: 0.8rem;
            padding: 0.375rem 0.625rem;
            width: 100%;
        }
        
        .peta-search-content .peta-search-actions {
            margin-top: 0.75rem;
            display: flex;
            gap: 0.375rem;
            flex-wrap: wrap;
        }
        
        .peta-search-content .peta-btn {
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
            flex: 1;
            min-width: 70px;
        }
        
        /* Legenda untuk map */
        .peta-legend {
            position: absolute;
            bottom: 15px;
            right: 15px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(44, 106, 143, 0.3);
            z-index: 1000;
            min-width: 150px;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        
        .peta-legend-header {
            background: linear-gradient(135deg, #2C6A8F 0%, #1E4A5F 100%);
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: 8px 8px 0 0;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .peta-legend-content {
            padding: 0.75rem;
        }
        
        .peta-legend-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.375rem;
        }
        
        .peta-legend-item:last-child {
            margin-bottom: 0;
        }
        
        .peta-legend-marker {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        
        .kantor-pusat-marker {
            background: #DC2626; /* Red - matches marker-icon-red.png */
        }
        
        .kantor-sbu-marker {
            background: #3B82F6; /* Blue - matches marker-icon-blue.png */
        }
        
        .kantor-perwakilan-marker {
            background: #FF8C00; /* Orange - matches marker-icon-orange.png */
        }
        
        .kantor-gudang-marker {
            background: #8B5CF6; /* Violet - matches marker-icon-violet.png */
        }
        
        /* Styling untuk icon marker custom */
        .custom-marker-icon {
            background: transparent !important;
            border: none !important;
        }
        
        .custom-marker-icon .peta-legend-marker {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        /* Icon marker custom sekarang dihandle via URL icon yang berbeda di JavaScript */
        
        /* Styling untuk halaman directory */
        .directory-header-card {
            background: linear-gradient(135deg, #2C6A8F 0%, #1E4A5F 100%);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(44, 106, 143, 0.2);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .directory-header-icon {
            width: 35px;
            height: 35px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.9rem;
        }
        
        .directory-header-content {
            flex: 1;
        }
        
        .directory-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: white;
            margin: 0 0 0.25rem 0;
        }
        
        .directory-subtitle {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.8);
            margin: 0;
        }
        
        .directory-view-toggle {
            display: flex;
            justify-content: center;
            margin-bottom: 1rem;
        }
        
        .directory-toggle-btn {
            padding: 0.5rem 1rem;
            border: 1px solid #2C6A8F;
            background: white;
            color: #1D5C7F;
            font-weight: 600;
            font-size: 0.8rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        
        .directory-toggle-btn.active,
        .directory-toggle-btn:hover {
            background: #1D5C7F;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(44, 106, 143, 0.3);
        }
        
        .directory-search-card {
            background: white;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(44, 106, 143, 0.1);
        }
        
        .directory-search-header {
            margin-bottom: 1rem;
        }
        
        .directory-search-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1D5C7F;
            margin: 0 0 0.25rem 0;
        }
        
        .directory-search-subtitle {
            font-size: 0.8rem;
            color: #6b7280;
            margin: 0;
        }
        
        .directory-form-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.25rem;
        }
        
        .directory-form-control,
        .directory-form-select {
            font-size: 0.8rem;
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        
        .directory-form-control:focus,
        .directory-form-select:focus {
            border-color: #1D5C7F;
            box-shadow: 0 0 0 3px rgba(44, 106, 143, 0.1);
        }
        
        .directory-btn-primary {
            background: #1D5C7F;
            border: none;
            color: white;
            font-weight: 600;
            font-size: 0.8rem;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        
        .directory-btn-primary:hover {
            background: #1E4A5F;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(44, 106, 143, 0.3);
        }
        
        .directory-list-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(44, 106, 143, 0.1);
            overflow: hidden;
        }
        
        .directory-list-header {
            background: #f8fafc;
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .directory-list-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1D5C7F;
            margin: 0 0 0.25rem 0;
        }
        
        .directory-list-subtitle {
            font-size: 0.8rem;
            color: #6b7280;
            margin: 0;
        }
        
        .directory-list-content {
            padding: 0;
        }
        
        .directory-table-responsive {
            overflow-x: auto;
            scrollbar-width: thin;
            scrollbar-color: #2C6A8F #f1f5f9;
        }
        
        .directory-table-responsive::-webkit-scrollbar {
            height: 6px;
        }
        
        .directory-table-responsive::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }
        
        .directory-table-responsive::-webkit-scrollbar-thumb {
            background: #1D5C7F;
            border-radius: 3px;
        }
        
        .directory-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }
        
        .directory-table th {
            background: #f8fafc;
            color: #374151;
            font-weight: 600;
            font-size: 0.8rem;
            padding: 0.75rem;
            text-align: left;
            border-bottom: 2px solid #e5e7eb;
            white-space: nowrap;
        }
        
        .directory-th-sticky {
            position: sticky;
            left: 0;
            background: #f8fafc;
            z-index: 10;
            min-width: 200px;
        }
        
        .directory-table td {
            padding: 0.75rem;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }
        
        .directory-td-sticky {
            position: sticky;
            left: 0;
            background: white;
            z-index: 5;
            min-width: 200px;
        }
        
        .directory-table-row:hover {
            background: #f8fafc;
        }
        
        .directory-kantor-info {
            max-width: 200px;
        }
        
        .directory-kantor-name {
            font-size: 0.9rem;
            font-weight: 600;
            color: #1f2937;
            margin: 0 0 0.25rem 0;
            line-height: 1.4;
        }
        
        .directory-kantor-address {
            font-size: 0.75rem;
            color: #6b7280;
            margin: 0;
            line-height: 1.4;
        }
        
        .directory-badge {
            display: inline-block;
            padding: 0.2rem 0.5rem;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .directory-badge-pusat {
            background: #fee2e2;
            color: #dc2626;
        }
        
        .directory-badge-sbu {
            background: #dbeafe;
            color: #2563eb;
        }
        
        .directory-badge-perwakilan {
            background: #fef3c7;
            color: #d97706;
        }
        
        .directory-badge-gudang {
            background: #e9d5ff;
            color: #7c3aed;
        }
        
        .directory-badge-default {
            background: #f3f4f6;
            color: #6b7280;
        }
        
        .directory-kantor-city,
        .directory-kantor-phone {
            font-size: 0.8rem;
            color: #374151;
            white-space: nowrap;
        }
        
        .directory-btn-outline {
            background: transparent;
            border: 1px solid #2C6A8F;
            color: #1D5C7F;
            font-size: 0.7rem;
            padding: 0.4rem 0.8rem;
            border-radius: 4px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .directory-btn-outline:hover {
            background: #1D5C7F;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(44, 106, 143, 0.2);
        }
        
        .directory-no-location {
            color: #9ca3af;
            font-size: 0.8rem;
        }
        
        .directory-pagination {
            padding: 1rem;
            display: flex;
            justify-content: center;
            background: #f8fafc;
            border-top: 1px solid #e5e7eb;
        }
        
        .directory-empty-state {
            text-align: center;
            padding: 2rem 1rem;
        }
        
        .directory-empty-icon {
            font-size: 2rem;
            color: #d1d5db;
            margin-bottom: 0.75rem;
        }
        
        .directory-empty-title {
            font-size: 1rem;
            font-weight: 600;
            color: #6b7280;
            margin: 0 0 0.25rem 0;
        }
        
        .directory-empty-subtitle {
            font-size: 0.8rem;
            color: #9ca3af;
            margin: 0;
        }
        
        .directory-map-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(44, 106, 143, 0.1);
            overflow: hidden;
        }
        
        .directory-map-header {
            background: #f8fafc;
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .directory-map-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1D5C7F;
            margin: 0 0 0.25rem 0;
        }
        
        .directory-map-subtitle {
            font-size: 0.8rem;
            color: #6b7280;
            margin: 0;
        }
        
        .directory-map-content {
            padding: 0;
        }
        
        .directory-map-container {
            height: 400px;
            width: 100%;
        }
        
        /* Desain responsive untuk directory page */
        @media (max-width: 768px) {
            .directory-header-card {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }
            
            .directory-header-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }
            
            .directory-title {
                font-size: 1.5rem;
            }
            
            .directory-table-responsive {
                font-size: 0.85rem;
            }
            
            .directory-table th,
            .directory-table td {
                padding: 0.75rem 0.5rem;
            }
            
            .directory-kantor-name {
                font-size: 0.9rem;
            }
            
            .directory-kantor-address {
                font-size: 0.8rem;
            }
        }
        
        .peta-legend-item span {
            font-size: 0.75rem;
            color: #374151;
            font-weight: 500;
        }
        
        .peta-page {
            background: linear-gradient(180deg, #f5f9ff 0%, #ffffff 60%);
            padding: 1.5rem 0 2rem;
        }

        .peta-fullscreen {
            min-height: calc(100vh - 80px);
            height: calc(100vh - 80px);
            display: flex;
        }

        .peta-dashboard {
            display: flex;
            align-items: stretch;
            gap: 1.75rem;
            flex: 1;
            min-height: 0;
        }

        .peta-dashboard-panel {
            width: 360px;
            max-width: 380px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.98) 0%, rgba(230, 241, 255, 0.9) 100%);
            border-radius: 30px;
            border: 1px solid rgba(16, 82, 129, 0.12);
            box-shadow: 0 24px 58px rgba(15, 76, 117, 0.16);
            padding: 1.75rem 1.9rem;
            display: flex;
            flex-direction: column;
            position: relative;
            z-index: 1010;
            transition: transform 0.3s ease;
            max-height: 100%;
        }

        .peta-dashboard-panel.collapsed {
            transform: translateX(calc(-100% + 68px));
        }

        .panel-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
        }

        .panel-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.45rem 1rem;
            border-radius: 999px;
            background: linear-gradient(135deg, rgba(199, 222, 250, 0.85) 0%, rgba(221, 235, 255, 0.95) 100%);
            color: #0f4c81;
            font-size: 0.72rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            font-weight: 600;
        }

        .panel-title {
            margin: 0.6rem 0 0;
            color: #09375a;
            font-size: 1.6rem;
            font-weight: 700;
        }

        .panel-collapse {
            width: 44px;
            height: 44px;
            border-radius: 16px;
            border: 1px solid rgba(16, 82, 129, 0.14);
            background: rgba(255, 255, 255, 0.95);
            color: #0f4c81;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 18px 36px rgba(15, 61, 87, 0.16);
            transition: all 0.2s ease;
        }

        .panel-collapse:hover {
            background: #0f4c81;
            color: #ffffff;
        }

        .panel-divider {
            height: 1px;
            background: linear-gradient(90deg, rgba(15, 76, 117, 0.08) 0%, rgba(15, 76, 117, 0.2) 50%, rgba(15, 76, 117, 0.08) 100%);
            margin: 1.35rem 0 1.25rem;
        }

        .panel-body {
            display: flex;
            flex-direction: column;
            gap: 1.35rem;
            overflow-y: auto;
            padding-right: 0.35rem;
            scrollbar-width: thin;
            flex: 1;
            min-height: 0;
        }

        .panel-body::-webkit-scrollbar {
            width: 6px;
        }

        .panel-body::-webkit-scrollbar-thumb {
            background: rgba(17, 94, 133, 0.25);
            border-radius: 999px;
        }

        .panel-section {
            display: grid;
            gap: 0.85rem;
        }

        .stat-card {
            display: flex;
            align-items: center;
            gap: 0.9rem;
            padding: 0.95rem 1.05rem;
            border-radius: 20px;
            border: 1px solid rgba(16, 82, 129, 0.12);
            background: linear-gradient(135deg, rgba(248, 250, 255, 0.95) 0%, rgba(235, 243, 255, 0.9) 100%);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.85);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            background: linear-gradient(140deg, rgba(214, 229, 250, 0.9) 0%, rgba(239, 246, 255, 0.95) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0f4c81;
            font-size: 1.05rem;
            box-shadow: 0 10px 22px rgba(15, 76, 117, 0.16);
        }

        .stat-label {
            display: block;
            font-size: 0.85rem;
            color: #5b6b7b;
            margin-bottom: 0.2rem;
        }

        .stat-value {
            font-weight: 700;
            font-size: 1.4rem;
            color: #0f3d57;
        }

        .panel-summary {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.2rem;
            border-radius: 22px;
            border: 1px solid rgba(16, 82, 129, 0.14);
            background: linear-gradient(140deg, rgba(230, 241, 255, 0.92) 0%, rgba(255, 255, 255, 0.95) 70%);
        }

        .summary-label {
            font-size: 0.85rem;
            color: #0f4c81;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            font-weight: 600;
        }

        .summary-value {
            font-size: 2rem;
            font-weight: 700;
            color: #09375a;
        }

        .panel-actions {
            display: grid;
            gap: 0.75rem;
        }

        .panel-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.55rem;
            padding: 0.75rem 1rem;
            border-radius: 18px;
            border: 1px solid rgba(16, 82, 129, 0.15);
            background: rgba(255, 255, 255, 0.95);
            font-weight: 600;
            color: #0f4c81;
            transition: all 0.2s ease;
        }

        .panel-btn.primary {
            background: linear-gradient(135deg, #0f4c81 0%, #1d6ba5 100%);
            color: #ffffff;
            border: none;
            box-shadow: 0 18px 36px rgba(16, 82, 129, 0.22);
        }

        .panel-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 28px rgba(15, 76, 117, 0.18);
        }

        .panel-legend {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .legend-title {
            font-weight: 700;
            color: #0f4c81;
            font-size: 0.9rem;
        }

        .legend-list {
            list-style: none;
            margin: 0;
            padding: 0;
            display: grid;
            gap: 0.65rem;
        }

        .legend-list li {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-size: 0.85rem;
            color: #4f647a;
        }

        .legend-dot {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            box-shadow: 0 0 0 4px rgba(16, 82, 129, 0.1);
        }

        .panel-tips {
            display: grid;
            gap: 0.85rem;
        }

        .peta-side-tip {
            display: flex;
            gap: 0.75rem;
            align-items: flex-start;
            padding: 0.85rem 1rem;
            border-radius: 18px;
            border: 1px dashed rgba(16, 82, 129, 0.24);
            background: rgba(255, 255, 255, 0.82);
            color: #4f647a;
            font-size: 0.85rem;
        }

        .peta-side-tip i {
            color: #0f4c81;
            font-size: 1.1rem;
        }

        .peta-quick-section h4 {
            margin: 0 0 0.6rem;
            font-size: 1.05rem;
            font-weight: 600;
            color: #0f4c81;
        }

        .peta-quick-list {
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }

        .peta-quick-item {
            display: flex;
            gap: 0.75rem;
            align-items: flex-start;
            border-radius: 18px;
            border: 1px solid rgba(16, 82, 129, 0.12);
            background: linear-gradient(135deg, rgba(248, 250, 255, 0.95) 0%, rgba(235, 243, 255, 0.9) 100%);
            padding: 0.85rem 1rem;
        }

        .peta-quick-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            background: linear-gradient(140deg, rgba(214, 229, 250, 0.9) 0%, rgba(239, 246, 255, 0.96) 100%);
            color: #0f4c81;
            box-shadow: 0 8px 18px rgba(15, 76, 117, 0.14);
        }

        .peta-quick-item strong {
            display: block;
            color: #0f4c81;
            margin-bottom: 0.2rem;
        }

        .peta-quick-item p {
            margin: 0;
            color: #5a6f85;
            font-size: 0.83rem;
        }

        .peta-dashboard-map {
            flex: 1;
            position: relative;
            border-radius: 32px;
            overflow: hidden;
            background: #ffffff;
            border: 1px solid rgba(29, 92, 127, 0.12);
            box-shadow: 0 28px 64px rgba(15, 61, 87, 0.16);
            height: 100%;
            min-height: calc(100vh - 160px);
        }

        .peta-map {
            width: 100%;
            height: 100%;
            min-height: 560px;
        }

        .map-controls {
            position: absolute;
            top: 24px;
            right: 24px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            z-index: 1100;
        }

        .map-control {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            border: 1px solid rgba(29, 92, 127, 0.16);
            background: rgba(255, 255, 255, 0.92);
            color: #1D5C7F;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 16px 34px rgba(15, 61, 87, 0.18);
            transition: all 0.2s ease;
        }

        .map-control:hover {
            background: #1D5C7F;
            color: #ffffff;
            transform: translateY(-1px);
        }

        @media (max-width: 1200px) {
            .peta-dashboard {
                flex-direction: column;
                gap: 1.4rem;
                height: auto;
            }

            .peta-dashboard-panel {
                width: 100%;
                max-width: none;
                transform: none !important;
                max-height: none;
            }

            .map-controls {
                right: 18px;
                top: 18px;
            }
        }

        @media (max-width: 992px) {
            .panel-title {
                font-size: 1.45rem;
            }

            .peta-map {
                min-height: 520px;
            }
        }

        @media (max-width: 768px) {
            .peta-page {
                padding: 1rem 0 1.5rem;
            }

            .panel-header {
                gap: 0.75rem;
            }

            .map-controls {
                flex-direction: row;
                bottom: 20px;
                top: auto;
                right: 50%;
                transform: translateX(50%);
                background: rgba(255, 255, 255, 0.9);
                padding: 0.45rem 0.65rem;
                border-radius: 999px;
                box-shadow: 0 16px 32px rgba(15, 61, 87, 0.18);
            }

            .map-control {
                width: 44px;
                height: 44px;
                border-radius: 14px;
            }
        }

        .peta-modal {
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid rgba(29, 92, 127, 0.12);
        }

        .peta-modal-header {
            background: linear-gradient(135deg, #1D5C7F 0%, #0f3d57 100%);
            color: #ffffff;
            border-bottom: none;
        }

        .peta-modal .form-label {
            font-weight: 600;
            color: #123C53;
        }

        .peta-modal .form-control,
        .peta-modal .form-select {
            border-radius: 12px;
            border: 1px solid rgba(29, 92, 127, 0.18);
            padding: 0.6rem 0.75rem;
        }

        .peta-modal .btn-primary {
            background: #1D5C7F;
            border-color: #1D5C7F;
            border-radius: 12px;
            padding: 0.6rem 1.2rem;
        }

        .peta-modal .btn-outline-secondary {
            border-radius: 12px;
        }

        @media (max-width: 1200px) {
            .peta-intro-card {
                grid-template-columns: 1fr;
            }

            .peta-intro-meta {
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            }
        }

        @media (max-width: 992px) {
            .peta-intro-card {
                padding: 2.1rem 2.2rem;
            }

            .peta-map-container {
                height: 540px;
            }

            .peta-legend {
                top: 18px;
                right: 18px;
                min-width: 190px;
            }

            .peta-stats-card {
                padding: 1.8rem;
            }
        }

        @media (max-width: 768px) {
            .peta-heading {
                font-size: 2rem;
            }

            .peta-map {
                height: calc(100vh - 140px);
            }

            .peta-hero-overlay {
                max-width: 360px;
                padding: 1.4rem 1.6rem;
            }

            .peta-stats-overlay {
                padding: 1.3rem 1.4rem;
            }
        }

        @media (max-width: 576px) {
            .peta-map {
                height: calc(100vh - 170px);
                min-height: 420px;
            }

            .peta-hero-overlay {
                position: static;
                max-width: none;
                margin: 16px;
            }

            .peta-stats-overlay {
                position: static;
                margin: 0 16px 16px;
            }

            .peta-legend {
                position: static;
                margin: 0 16px 16px;
            }

            .peta-stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        
        /* Custom Popup Styling */
        .custom-popup .leaflet-popup-content-wrapper {
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            padding: 0;
        }
        
        .custom-popup .leaflet-popup-content {
            margin: 0;
            padding: 0;
            max-height: 400px;
            overflow-y: auto;
        }
        
        .custom-popup .leaflet-popup-tip {
            background: white;
            border: 1px solid #e5e7eb;
        }
        
        .peta-title-section {
            text-align: center;
        }
        
        .peta-title {
            color: white;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .peta-subtitle {
            color: rgba(255,255,255,0.9);
            font-size: 1.1rem;
            margin: 0;
            font-weight: 400;
        }
        
        /* Map Container */
        .peta-map-container {
            position: relative;
            height: 600px;
            background: white;
            border-right: 1px solid #e5e7eb;
        }
        
        .peta-map {
            width: 100%;
            height: 100%;
            border: none;
        }
        
        /* Controls */
        .peta-controls {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 1000;
        }
        
        .search-btn-icon {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 8px;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            color: #374151;
        }
        
        .search-btn-icon:hover {
            background: #f9fafb;
            border-color: #1D5C7F;
            color: #1D5C7F;
        }
        
        /* Legend */
        .peta-legend {
            position: absolute;
            top: 20px;
            right: 20px;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            z-index: 1000;
            min-width: 160px;
        }
        
        .legend-title {
            font-weight: 600;
            color: #1D5C7F;
            margin-bottom: 12px;
            font-size: 0.9rem;
        }
        
        .legend-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.75rem;
            color: #374151;
        }
        
        .legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        
        /* Sidebar */
        /* Statistics Section - Below Map */
        .stats-section {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border: 1px solid #e5e7eb;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        
        .stats-header {
            padding: 16px 20px;
            border-bottom: 1px solid #e5e7eb;
            background: #1D5C7F;
            color: white;
            border-radius: 8px 8px 0 0;
        }
        
        .stats-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            margin: 0;
        }
        
        /* Stats Horizontal */
        .stats-horizontal {
            padding: 20px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }
        
        .stat-card-horizontal {
            background: white;
            border-radius: 8px;
            padding: 16px;
            border: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s ease;
        }
        
        .stat-card-horizontal:hover {
            border-color: #1D5C7F;
            box-shadow: 0 2px 8px rgba(44, 106, 143, 0.1);
        }
        
        .stat-icon-horizontal {
            width: 40px;
            height: 40px;
            background: #1D5C7F;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.1rem;
            flex-shrink: 0;
        }
        
        .stat-content {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
            background: #1D5C7F;
        }
        
        .stat-info {
            flex: 1;
        }
        
        .stat-number {
            font-size: 1.875rem;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 4px;
            color: #1D5C7F;
        }
        
        .stat-label {
            color: #6b7280;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        /* Stats Info */
        .stats-info {
            padding: 16px 20px;
            border-top: 1px solid #e5e7eb;
            background: #f8fafc;
            display: flex;
            gap: 24px;
            justify-content: center;
        }
        
        .info-item-horizontal {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            color: #6b7280;
            line-height: 1.4;
        }
        
        .info-item-horizontal i {
            flex-shrink: 0;
            color: #1D5C7F;
        }
        
        /* Modal Styling */
        .peta-modal .modal-header {
            background: #1D5C7F;
            color: white;
            border-bottom: none;
        }
        
        .peta-modal .modal-header .btn-close {
            filter: invert(1);
        }
        
        .peta-modal .modal-title {
            font-weight: 600;
        }
        
        .peta-label {
            font-weight: 500;
            color: #1D5C7F;
            margin-bottom: 8px;
        }
        
        .peta-input,
        .peta-select {
            border-color: #d1d5db;
            border-radius: 6px;
            padding: 10px 12px;
        }
        
        .peta-input:focus,
        .peta-select:focus {
            border-color: #1D5C7F;
            box-shadow: 0 0 0 3px rgba(44, 106, 143, 0.1);
        }
        
        /* Responsive Design */
        @media (max-width: 991px) {
            .peta-title {
                font-size: 2rem;
            }
            
            .peta-map-container {
                height: 400px;
                border-right: none;
                border-bottom: 1px solid #e5e7eb;
            }
            
            .stats-horizontal {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }
            
            .stats-info {
                flex-direction: column;
                gap: 12px;
                text-align: center;
            }
            
            .peta-controls {
                top: 15px;
                left: 15px;
            }
            
            .peta-legend {
                top: 15px;
                right: 15px;
                min-width: 160px;
                padding: 12px;
            }
        }
        
        @media (max-width: 767px) {
            .peta-header {
                padding: 1.5rem 0;
            }
            
            .peta-title {
                font-size: 1.75rem;
            }
            
            .peta-subtitle {
                font-size: 1rem;
            }
            
            .stats-grid {
                padding: 16px;
                gap: 12px;
            }
            
            .stat-card {
                padding: 16px;
                gap: 12px;
            }
            
            .stat-icon {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }
            
            .stat-number {
                font-size: 1.5rem;
            }
        }
        
        /* Responsive untuk halaman peta */
        @media (max-width: 768px) {
            /* Hero Section Mobile */
            .peta-hero-title {
                font-size: 2.5rem;
                line-height: 1.2;
            }
            
            .peta-hero-subtitle {
                font-size: 1.1rem;
                margin-bottom: 2rem;
            }
            
            .peta-hero-stats {
                flex-direction: column;
                gap: 1rem;
            }
            
            .peta-stat-divider {
                width: 60px;
                height: 2px;
            }
            
            .peta-hero-actions {
                flex-direction: column;
                gap: 1rem;
            }
            
            .peta-hero-btn {
                width: 100%;
                min-width: auto;
            }
            
            /* Map Section Mobile */
            .peta-floating-controls {
                top: 10px;
                left: 10px;
            }
            
            .peta-floating-btn {
                width: 45px;
                height: 45px;
                font-size: 1rem;
            }
            
            .peta-floating-legend {
                top: 10px;
                right: 10px;
                min-width: 200px;
            }
            
            .peta-legend-content {
                padding: 1rem;
            }
            
            /* Search Modal Mobile */
            .peta-search-content {
                margin: 1rem;
                max-height: 95vh;
            }
            
            .peta-search-header-new {
                padding: 1.5rem;
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }
            
            .peta-search-icon {
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
            }
            
            .peta-form-row {
                grid-template-columns: 1fr;
            }
            
            .peta-form-actions {
                flex-direction: column;
            }
            
            /* Info Section Mobile */
            .peta-info-title {
                font-size: 2rem;
            }
            
            .peta-feature-card {
                padding: 2rem 1.5rem;
            }
            
            .peta-feature-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }
            
            /* Toast Mobile */
            .peta-toast {
                top: 10px;
                right: 10px;
                left: 10px;
                transform: translateY(-100%);
            }
            
            .peta-toast.show {
                transform: translateY(0);
            }
        }
        
        @media (max-width: 480px) {
            .peta-hero-content {
                padding: 2rem 1rem;
            }
            
            .peta-hero-title {
                font-size: 2rem;
            }
            
            .peta-stat-number {
                font-size: 2rem;
            }
            
            .peta-info-title {
                font-size: 1.75rem;
            }
        }

        /* ===== HIGH PRIORITY IMPROVEMENTS ===== */
        
        /* Loading States System */
        .btn-loading {
            position: relative;
            pointer-events: none;
            opacity: 0.8;
        }

        .btn-loading .btn-text {
            opacity: 0;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 16px;
            height: 16px;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }

        /* Button Enhancements dengan Shimmer Effect */
        .btn-pln, .btn-outline-pln {
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-pln::before, .btn-outline-pln::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-pln:hover::before, .btn-outline-pln:hover::before {
            left: 100%;
        }

        /* Image Lazy Loading System */
        .lazy-image {
            opacity: 0;
            transition: opacity 0.3s ease;
            background: #f8f9fa;
        }

        .lazy-image.loaded {
            opacity: 1;
        }

        .lazy-placeholder {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading-shimmer 1.5s infinite;
            border-radius: 8px;
        }

        @keyframes loading-shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* Smooth Scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Enhanced Focus States untuk Accessibility */
        .btn-pln:focus, .btn-outline-pln:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(74, 144, 164, 0.3);
        }

        .form-control:focus {
            border-color: #4A90A4;
            box-shadow: 0 0 0 0.2rem rgba(74, 144, 164, 0.25);
        }

            margin-bottom: 1rem;
            color: #dc3545;
        }

        /* Success States */
        .success-state {
            text-align: center;
            padding: 2rem;
            color: #28a745;
        }

        .success-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        /* Accessibility - Reduced Motion */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
            
            html {
                scroll-behavior: auto;
            }
        }

        /* Print Styles */
        @media print {
            .navbar, .footer, .btn {
                display: none !important;
            }
            
            body {
                background: white !important;
                color: black !important;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top border-bottom" style="background-color: #ffffff !important;">
        <div class="container">
            <a class="navbar-brand p-0" href="{{ route('public.home') }}">
                <img src="{{ asset('images/logo/pln-logo.png') }}" alt="PLN Icon Plus" class="d-inline-block align-middle" style="height: 70px; margin-top: -5px; margin-bottom: -5px;">
            </a>
            <button class="navbar-toggler border-0 p-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.home') ? 'active' : '' }} px-3 py-2 mx-1" href="{{ route('public.home') }}">
                            <i class="fas fa-home d-lg-none me-2"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.peta') ? 'active' : '' }} px-3 py-2 mx-1" href="{{ route('public.peta') }}">
                            <i class="fas fa-map-marked-alt d-lg-none me-2"></i> Peta Kantor
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.help') ? 'active' : '' }} px-3 py-2 mx-1" href="{{ route('public.help') }}">
                            <i class="fas fa-phone d-lg-none me-2"></i> Kontak
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.about') ? 'active' : '' }} px-3 py-2 mx-1" href="{{ route('public.about') }}">
                            <i class="fas fa-info-circle d-lg-none me-2"></i> Tentang
                        </a>
                    </li>
                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                        @guest
                            <a href="{{ route('login') }}" class="btn btn-pln btn-sm px-3">
                                <i class="fas fa-sign-in-alt me-1"></i> <span class="d-none d-sm-inline">Login</span>
                            </a>
                        @else
                            <div class="dropdown">
                                <a class="nav-link dropdown-toggle p-0 d-flex align-items-center" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-pln text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <span class="ms-2 d-none d-lg-inline text-dark">{{ Auth::user()->name }}</span>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow" aria-labelledby="userDropdown">
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('admin.dashboard') }}">
                                            <i class="fas fa-tachometer-alt me-2 text-muted" style="width: 20px;"></i> Dashboard
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center py-2 text-danger" 
                                           href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt me-2" style="width: 20px;"></i> Logout
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endguest
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <style>
        /* Navbar Styles */
        .navbar {
            padding: 0.5rem 0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        
        .navbar-brand {
            font-weight: 600;
        }
        
        .nav-link {
            color: #4a5568 !important;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.2s;
            border-radius: 4px;
        }
        
        .nav-link:hover, 
        .nav-link:focus {
            color: #1D5C7F !important;
            background-color: rgba(29, 92, 127, 0.05);
        }
        
        .nav-link.active {
            color: #1D5C7F !important;
            font-weight: 600;
        }
        
        /* Buttons */
        .btn-pln {
            background-color: #1D5C7F;
            border-color: #1D5C7F;
            color: white !important;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 0.4rem 1rem;
        }
        
        .btn-pln:hover {
            background-color: #174b6b;
            border-color: #174b6b;
        }
        
        /* Responsive adjustments */
        @media (max-width: 991.98px) {
            .navbar-collapse {
                background: white;
                padding: 1rem;
                margin-top: 0.5rem;
                border-radius: 0.5rem;
                box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
            }
            
            .nav-item {
                margin: 0.15rem 0;
            }
            
            .nav-link {
                padding: 0.6rem 1rem !important;
            }
            
            .btn-pln {
                width: 100%;
                margin: 0.5rem 0 0;
            }
        }
        
        /* Body padding for fixed navbar */
        body {
            padding-top: 65px;
        }
    </style>
    
    <script>
        // Close mobile menu when clicking on a nav link
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                const navbarCollapse = document.querySelector('.navbar-collapse');
                if (navbarCollapse.classList.contains('show')) {
                    const bsCollapse = new bootstrap.Collapse(navbarCollapse, {toggle: false});
                    bsCollapse.hide();
                }
            });
        });
    </script>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer bg-pln py-5">
        <div class="container">
            <div class="row">
                <!-- Logo & Brand -->
                <div class="col-md-4 mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ asset('images/logo/pln-logo.png') }}" alt="PLN" class="me-3" style="height: 50px;">
                        <div>
                            <h5 class="text-white mb-0 fw-bold">PLN ICON PLUS</h5>
                            <p class="text-white-50 mb-0">Sistem Informasi Aset & Properti</p>
                        </div>
                    </div>
                    <p class="text-white-50 small">Platform terintegrasi untuk pengelolaan aset dan properti.</p>
                    
                    <div class="mt-3">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-md-2 mb-4">
                    <h6 class="text-uppercase fw-bold text-white mb-3">Tautan Cepat</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('public.home') }}" class="text-decoration-none text-white-50">Beranda</a></li>
                        <li class="mb-2"><a href="{{ route('public.peta') }}" class="text-decoration-none text-white-50">Peta Kantor</a></li>
                        <li class="mb-2"><a href="{{ route('public.help') }}" class="text-decoration-none text-white-50">Kontak</a></li>
                        <li><a href="{{ route('public.about') }}" class="text-decoration-none text-white-50">Tentang Kami</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div class="col-md-3 mb-4">
                    <h6 class="text-uppercase fw-bold text-white mb-3">Kontak</h6>
                    <ul class="list-unstyled text-white-50">
                        <li class="mb-2">
                            <i class="fas fa-phone text-white me-2"></i> +62 21 1234 5678
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-envelope text-white me-2"></i> info@pln.co.id
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-clock text-white me-2"></i> Senin - Jumat, 08:00 - 17:00 WIB
                        </li>
                    </ul>
                </div>

                <!-- Address -->
                <div class="col-md-3">
                    <h6 class="text-uppercase fw-bold text-white mb-3">Alamat</h6>
                    <address class="small text-white-50">
                        Sub Bidang UMUM<br>
                        Bidang Pengadaan dan UMUM<br>
                        Direktorat HCMA<br>
                        Lantai 10, Wing Selatan - Menara Jamsostek<br>
                        Jl. Gatot Subroto No.Kav. 38<br>
                        Jakarta 12710
                    </address>
                </div>
            </div>

            <!-- Copyright -->
            <div class="row mt-4 pt-3 border-top border-white-10">
                <div class="col-12 text-center">
                    <p class="mb-0 text-white-50 small">
                        &copy; {{ date('Y') }} PT PLN (Persero) - ICON PLUS. Seluruh Hak Cipta Dilindungi.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript Bootstrap untuk interaksi UI -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JavaScript Leaflet untuk map interaktif -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <!-- Library ExcelJS untuk export Excel dengan styling -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>
    

    <!-- Custom JavaScript for Enhanced UX -->
    <script>
        // ===== HIGH PRIORITY IMPROVEMENTS JAVASCRIPT =====

        document.addEventListener('DOMContentLoaded', function() {
            const carousels = document.querySelectorAll('.company-timeline-carousel');

            carousels.forEach(carousel => {
                const track = carousel.querySelector('.timeline-track');
                const slides = Array.from(carousel.querySelectorAll('.timeline-slide'));
                const prevBtn = carousel.querySelector('.timeline-nav.prev');
                const nextBtn = carousel.querySelector('.timeline-nav.next');
                const wrapper = carousel.querySelector('.timeline-track-wrapper');

                if (!track || slides.length === 0 || !prevBtn || !nextBtn || !wrapper) {
                    return;
                }

                let gap = 16;
                let slideWidth = slides[0].getBoundingClientRect().width;
                let visibleCount = 1;
                let currentIndex = 0;
                let maxIndex = 0;

                const updateMetrics = () => {
                    const style = window.getComputedStyle(track);
                    gap = parseFloat(style.columnGap || style.gap || '0');
                    slideWidth = slides[0].getBoundingClientRect().width;

                    const wrapperWidth = wrapper.getBoundingClientRect().width;
                    const totalSlideWidth = slideWidth + gap;
                    visibleCount = Math.max(1, Math.round(wrapperWidth / totalSlideWidth));
                    maxIndex = Math.max(0, slides.length - visibleCount);
                    currentIndex = Math.min(currentIndex, maxIndex);

                    applyTransform();
                };

                const applyTransform = () => {
                    const offset = -currentIndex * (slideWidth + gap);
                    track.style.transform = `translateX(${offset}px)`;

                    const atStart = currentIndex === 0;
                    const atEnd = currentIndex >= maxIndex;

                    carousel.classList.toggle('at-start', atStart);
                    carousel.classList.toggle('at-end', atEnd);

                    prevBtn.disabled = atStart;
                    nextBtn.disabled = atEnd;
                };

                const move = direction => {
                    currentIndex = Math.min(Math.max(0, currentIndex + direction), maxIndex);
                    applyTransform();
                };

                prevBtn.addEventListener('click', () => move(-1));
                nextBtn.addEventListener('click', () => move(1));

                let resizeTimeout;
                window.addEventListener('resize', () => {
                    clearTimeout(resizeTimeout);
                    resizeTimeout = setTimeout(updateMetrics, 150);
                });

                updateMetrics();
            });
        });

        // Button Loading States
        function setButtonLoading(button, loading = true) {
            if (loading) {
                button.classList.add('btn-loading');
                button.disabled = true;
                // Wrap text content
                if (!button.querySelector('.btn-text')) {
                    button.innerHTML = `<span class="btn-text">${button.innerHTML}</span>`;
                }
            } else {
                button.classList.remove('btn-loading');
                button.disabled = false;
            }
        }
        
        // Auto-apply loading states to all buttons with data-loading attribute
        document.addEventListener('click', function(e) {
            const button = e.target.closest('[data-loading]');
            if (button) {
                setButtonLoading(button, true);
                
                // Auto-remove loading after timeout (fallback)
                setTimeout(() => {
                    setButtonLoading(button, false);
                }, 5000);
            }
        });
        
        // Form submission loading
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
                if (submitBtn) {
                    setButtonLoading(submitBtn, true);
                }
            });
        });
        
        // Lazy Loading Images
        const lazyImages = document.querySelectorAll('.lazy-image');
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy-placeholder');
                    img.onload = () => {
                        img.classList.add('loaded');
                    };
                    observer.unobserve(img);
                }
            });
        });
        
        lazyImages.forEach(img => imageObserver.observe(img));
        
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Page transition loading
        // Error handling for images
        document.querySelectorAll('img').forEach(img => {
            img.addEventListener('error', function() {
                this.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGRkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkltYWdlIG5vdCBmb3VuZDwvdGV4dD48L3N2Zz4=';
                this.classList.add('error-state');
            });
        });
        
        // Keyboard navigation enhancement
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Tab') {
                document.body.classList.add('keyboard-navigation');
            }
        });
        
        document.addEventListener('mousedown', function() {
            document.body.classList.remove('keyboard-navigation');
        });
        
        // Performance optimization - debounce scroll events
        let scrollTimeout;
        window.addEventListener('scroll', function() {
            if (scrollTimeout) {
                clearTimeout(scrollTimeout);
            }
            scrollTimeout = setTimeout(function() {
                // Scroll-based optimizations here
            }, 100);
        });
        
        // CSRF token refresh for long sessions
        setInterval(function() {
            fetch('/csrf-token')
                .then(response => response.json())
                .then(data => {
                    document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.token);
                })
                .catch(error => console.log('CSRF token refresh failed'));
        }, 300000); // Refresh every 5 minutes
        
    </script>
        
        @stack('scripts')
    </body>
    </html>
