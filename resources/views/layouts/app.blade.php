<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PLN Icon Plus Kantor Management')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo/pln-logo.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/logo/pln-logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo/pln-logo.png') }}">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#1e40af">
    <meta name="background-color" content="#1e3a8a">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="PLN Kantor">
    <meta name="msapplication-TileColor" content="#1e40af">
    <meta name="msapplication-navbutton-color" content="#1e40af">
    
    <!-- PWA App Icons -->
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('images/pwa-icon-72.png') }}">
    <link rel="apple-touch-icon" sizes="96x96" href="{{ asset('images/pwa-icon-96.png') }}">
    <link rel="apple-touch-icon" sizes="128x128" href="{{ asset('images/pwa-icon-128.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('images/pwa-icon-144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('images/pwa-icon-152.png') }}">
    <link rel="apple-touch-icon" sizes="192x192" href="{{ asset('images/pwa-icon-192.png') }}">
    <link rel="apple-touch-icon" sizes="384x384" href="{{ asset('images/pwa-icon-384.png') }}">
    <link rel="apple-touch-icon" sizes="512x512" href="{{ asset('images/pwa-icon-512.png') }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Public Sans font -->
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            /* Palette Warna - Dominan Putih dengan Aksen Biru Muda */
            --primary-color: #3b82f6;
            --primary-light: #60a5fa;
            --primary-lighter: #93c5fd;
            --primary-lightest: #dbeafe;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #06b6d4;
            
            /* Warna Background */
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --bg-tertiary: #f1f5f9;
            --bg-accent: #eff6ff;
            
            /* Warna Text */
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --text-light: #cbd5e1;
            
            /* Border & Shadow */
            --border-color: #e2e8f0;
            --border-light: #f1f5f9;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
            
            /* Spacing */
            --spacing-xs: 0.25rem;
            --spacing-sm: 0.5rem;
            --spacing-md: 1rem;
            --spacing-lg: 1.5rem;
            --spacing-xl: 2rem;
            --spacing-2xl: 3rem;
            
            /* Border Radius */
            --radius-sm: 0.375rem;
            --radius-md: 0.5rem;
            --radius-lg: 0.75rem;
            --radius-xl: 1rem;
        }

        body {
            font-family: 'Public Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-tertiary) 100%);
            color: var(--text-primary);
            line-height: 1.6;
            font-size: 14px;
        }

        /* Style Header Modern */
        .modern-header {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 1.5rem;
            padding: 1.5rem 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(59, 130, 246, 0.1);
            position: relative;
            overflow: hidden;
        }

        .modern-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-light));
        }

        .search-container {
            position: relative;
            max-width: 400px;
        }

        .search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 3rem;
            border: 2px solid var(--border-light);
            border-radius: 2rem;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            font-size: 0.875rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-light);
            box-shadow: 0 8px 32px rgba(59, 130, 246, 0.15);
            transform: translateY(-1px);
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1rem;
        }

        .notification-btn {
            position: relative;
            background: linear-gradient(135deg, var(--primary-lightest) 0%, var(--bg-accent) 100%);
            border: 2px solid var(--primary-lightest);
            border-radius: 1rem;
            padding: 0.75rem;
            color: var(--primary-color);
            font-size: 1.25rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .notification-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(59, 130, 246, 0.2);
            border-color: var(--primary-light);
        }

        .notification-badge {
            position: absolute;
            top: -0.5rem;
            right: -0.5rem;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            border-radius: 50%;
            width: 1.5rem;
            height: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: linear-gradient(135deg, var(--primary-lightest) 0%, var(--bg-accent) 100%);
            padding: 0.75rem 1.5rem;
            border-radius: 2rem;
            border: 2px solid var(--primary-lightest);
            transition: all 0.3s ease;
        }

        .user-profile:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 32px rgba(59, 130, 246, 0.15);
            border-color: var(--primary-light);
        }

        .user-avatar {
            position: relative;
        }

        .avatar-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 3px solid var(--primary-light);
            transition: all 0.3s ease;
        }

        .user-profile:hover .avatar-img {
            transform: scale(1.1);
            border-color: var(--primary-color);
        }

        .user-greeting {
            font-weight: 700;
            color: var(--text-primary);
            font-size: 1rem;
        }

        .user-welcome {
            font-size: 0.875rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .map-container {
            height: 400px;
            width: 100%;
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: var(--shadow-md);
        }
        
        .sidebar {
            background: var(--bg-primary);
            min-height: 100vh;
            box-shadow: var(--shadow-lg);
            border-right: 1px solid var(--border-light);
        }

        .sidebar .nav-link {
            color: var(--text-secondary);
            padding: 0.75rem 1rem;
            margin: 0.25rem 0.5rem;
            border-radius: var(--radius-md);
            transition: all 0.2s ease;
            border: 1px solid transparent;
            font-weight: 500;
        }

        .sidebar .nav-link:hover {
            background-color: var(--bg-accent);
            color: var(--primary-color);
            border-color: var(--primary-lightest);
            transform: translateX(2px);
        }

        .sidebar .nav-link.active {
            background: linear-gradient(135deg, var(--primary-lightest) 0%, var(--bg-accent) 100%);
            color: var(--primary-color);
            font-weight: 600;
            border-color: var(--primary-light);
        }

        .sidebar .nav-link i {
            width: 20px;
            margin-right: 0.75rem;
            color: var(--text-muted);
        }

        .sidebar .nav-link:hover i,
        .sidebar .nav-link.active i {
            color: var(--primary-color);
        }

        /* Organisasi Sidebar */
        .sidebar .nav-item hr {
            border-color: rgba(0, 0, 0, 0.1);
            margin: 0.5rem 1rem;
        }

        .sidebar .nav-link {
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover {
            transform: translateX(4px);
        }

        /* Styling Page Header */
        .page-header {
            margin-bottom: 0;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .page-subtitle {
            color: var(--text-secondary);
            font-size: 0.95rem;
            margin-bottom: 0;
        }

        /* Aksi Header */
        .btn-toolbar {
            gap: 1rem;
        }

        .btn-toolbar .input-group {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-toolbar .btn {
            border-radius: 0.5rem;
        }

        /* Styling Sidebar Brand */
        .sidebar-brand {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .sidebar-logo-img {
            height: 32px;
            width: auto;
            margin-bottom: 0.5rem;
        }

        .sidebar-logo-icon {
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .sidebar-brand-text {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
            line-height: 1.2;
        }

        .sidebar-welcome {
            color: var(--text-secondary);
            font-size: 0.8rem;
            margin: 0;
        }

        /* Styling Menu Collapse */
        .sidebar .collapse {
            background: var(--bg-secondary);
            border-radius: var(--radius-md);
            margin: 0.25rem 0.5rem;
            padding: 0.5rem 0;
        }

        .sidebar .collapse .nav-link {
            padding: 0.5rem 1rem;
            margin: 0.125rem 0.5rem;
            font-size: 0.9rem;
            border-radius: var(--radius-sm);
        }

        .sidebar .nav-link[data-bs-toggle="collapse"] {
            font-weight: 600;
            color: var(--text-primary);
            background: var(--bg-accent);
            border: 1px solid var(--border-light);
        }

        .sidebar .nav-link[data-bs-toggle="collapse"]:hover {
            background: var(--primary-lightest);
            color: var(--primary-color);
        }

        .sidebar .nav-link[data-bs-toggle="collapse"] i:last-child {
            transition: transform 0.3s ease;
        }

        .sidebar .nav-link[data-bs-toggle="collapse"][aria-expanded="true"] i:last-child {
            transform: rotate(180deg);
        }

        /* Spacing compact */
        .sidebar .nav-item {
            margin-bottom: 0.125rem;
        }

        .sidebar .nav-link {
            padding: 0.625rem 1rem;
            margin: 0.125rem 0.5rem;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--text-primary);
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-light);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .navbar-brand img {
            height: 40px;
            width: auto;
            margin-right: 10px;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
        }

        /* Styling Logo PLN */
        .pln-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            border-radius: 1rem;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            margin-right: 1.5rem;
        }

        .pln-logo-img {
            height: 75px;
            width: auto;
            max-width: 85px;
            object-fit: contain;
            transition: transform 0.3s ease;
            /* Styling bersih untuk logo transparan */
            filter: drop-shadow(0 2px 6px rgba(0,0,0,0.15));
            background: transparent;
            /* Hapus filter kompleks karena logo sekarang transparan */
        }

        .pln-logo-img:hover {
            transform: scale(1.05);
        }

        /* Responsive logo */
        @media (max-width: 768px) {
            .pln-logo-img {
                height: 55px;
                max-width: 60px;
            }
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-light));
            border-radius: 2px;
            animation: progressLoad 3s ease-in-out infinite;
        }

        /* Animations */
        @keyframes logoFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        @keyframes textFade {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes progressLoad {
            0% { width: 0%; }
            50% { width: 70%; }
            100% { width: 100%; }
        }

        /* Responsive loading screen */
        @media (max-width: 768px) {
            .loading-container {
                padding: 1.5rem;
            }
            
            .loading-logo-img {
                height: 85px;
                max-width: 95px;
            }
            
            .loading-text h3 {
                font-size: 1.5rem;
            }
        }

        .navbar-brand i {
            margin-right: 0.5rem;
            color: var(--primary-color);
        }
        
        .card {
            border: 1px solid var(--border-light);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            background-color: var(--bg-primary);
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
            border-color: var(--primary-lightest);
        }

        .card-header {
            background: linear-gradient(135deg, var(--bg-accent) 0%, var(--primary-lightest) 100%);
            color: var(--text-primary);
            border-radius: var(--radius-xl) var(--radius-xl) 0 0 !important;
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            border-bottom: 1px solid var(--border-light);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            border: 1px solid var(--primary-color);
            border-radius: var(--radius-md);
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.2s ease;
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary-color) 100%);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
            border-color: var(--primary-light);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
        }

        .btn-warning {
            background: linear-gradient(135deg, var(--warning-color) 0%, #d97706 100%);
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%);
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
        }
        
        .table-responsive {
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: linear-gradient(135deg, var(--bg-accent) 0%, var(--primary-lightest) 100%);
            border: none;
            padding: 1rem;
            font-weight: 600;
            color: var(--text-primary);
            font-size: 14px;
        }

        .table tbody td {
            padding: 1rem;
            border: none;
            border-bottom: 1px solid var(--border-light);
            font-size: 14px;
        }

        .table tbody tr:hover {
            background-color: var(--bg-accent);
        }

        .form-control {
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 0.75rem 1rem;
            transition: all 0.2s ease;
            background-color: var(--bg-primary);
            font-size: 14px;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px var(--primary-lightest);
            background-color: var(--bg-primary);
        }

        .form-label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            font-size: 14px;
        }

        .alert {
            border: none;
            border-radius: 0.75rem;
            padding: 1rem 1.25rem;
            font-weight: 500;
        }

        .alert-success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
        }

        .alert-danger {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
        }

        .alert-warning {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
        }

        .alert-info {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            color: #1e40af;
        }

        .badge {
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            font-weight: 600;
        }

        .badge-success {
            background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
        }

        .badge-warning {
            background: linear-gradient(135deg, var(--warning-color) 0%, #d97706 100%);
        }

        .badge-danger {
            background: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%);
        }

        .badge-info {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        }

        .main-content {
            background-color: var(--card-bg);
            border-radius: 1rem;
            box-shadow: var(--shadow-sm);
            margin: 1rem;
            padding: 2rem;
        }

        .page-header {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--border-color);
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        .page-subtitle {
            color: var(--text-secondary);
            margin: 0.5rem 0 0 0;
        }

        .stats-card {
            background: linear-gradient(135deg, var(--card-bg) 0%, #f8fafc 100%);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.2s ease;
        }

        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
        }

        /* Badge Styling */
        .badge {
            border-radius: var(--radius-sm);
            font-weight: 500;
            font-size: 0.75rem;
            padding: 0.375rem 0.75rem;
        }

        .badge.bg-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%) !important;
        }

        .badge.bg-success {
            background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%) !important;
        }

        .badge.bg-warning {
            background: linear-gradient(135deg, var(--warning-color) 0%, #d97706 100%) !important;
        }

        .badge.bg-danger {
            background: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%) !important;
        }

        /* Button Variants */
        .btn-outline-primary {
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
            background-color: transparent;
            border-radius: var(--radius-md);
            font-weight: 500;
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-1px);
        }

        .btn-outline-secondary {
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
            background-color: transparent;
            border-radius: var(--radius-md);
            font-weight: 500;
        }

        .btn-outline-secondary:hover {
            background-color: var(--bg-tertiary);
            color: var(--text-primary);
            border-color: var(--text-muted);
        }

        /* Neutral icon buttons with subtle hover */
        .btn-icon {
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
            background-color: #fff;
        }

        .btn-icon:hover {
            background-color: var(--primary-lightest);
            color: var(--primary-color);
            border-color: var(--primary-lightest);
        }

        .btn-light.btn-icon {
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
            background-color: var(--bg-primary);
        }

        .btn-light.btn-icon:hover {
            background-color: var(--primary-lightest);
            color: var(--primary-color);
            border-color: var(--primary-lightest);
        }

        .stats-label {
            color: var(--text-secondary);
            font-weight: 600;
            margin: 0.5rem 0 0 0;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: -100%;
                width: 280px;
                z-index: 1000;
                transition: left 0.3s ease;
            }

            .sidebar.show {
                left: 0;
            }

            .main-content {
                margin: 0;
                border-radius: 0;
            }
        }
    </style>
    
    @stack('styles')
</head>
    <body>

        <div class="container-fluid">
            <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <div class="sidebar-brand">
                            <img src="{{ asset('images/logo/pln-logo.png') }}" 
                                 alt="PLN Logo" 
                                 class="sidebar-logo-img"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='inline';">
                            <i class="fas fa-building sidebar-logo-icon" style="display: none;"></i>
                            <h4 class="sidebar-brand-text">PLN Icon Plus</h4>
                        </div>
                        <small class="sidebar-welcome">Selamat datang, {{ Auth::guard('admin')->user()->nama_admin ?? 'Admin' }}</small>
                    </div>
                    
                    <ul class="nav flex-column">
                        <!-- Dashboard -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i>
                                Dashboard
                            </a>
                        </li>
                        
                        <!-- Data Management Collapse -->
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="collapse" href="#dataManagement" role="button" aria-expanded="false" aria-controls="dataManagement">
                                <i class="fas fa-database"></i>
                                Data Management
                                <i class="fas fa-chevron-down ms-auto" style="font-size: 0.8rem;"></i>
                            </a>
                            <div class="collapse" id="dataManagement">
                                <ul class="nav flex-column ms-3">
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('peta.*') ? 'active' : '' }}" href="{{ route('peta.index') }}">
                                            <i class="fas fa-map"></i>
                                            Peta
                                        </a>
                                    </li>
                                    @if((Auth::guard('admin')->user()->role ?? '') !== 'staf')
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('kantor.*') ? 'active' : '' }}" href="{{ route('kantor.index') }}">
                                            <i class="fas fa-building"></i>
                                            Kantor
                                        </a>
                                    </li>
                                    @endif
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('gedung.*') ? 'active' : '' }}" href="{{ route('gedung.index') }}">
                                            <i class="fas fa-home"></i>
                                            Gedung
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('lantai.*') ? 'active' : '' }}" href="{{ route('lantai.index') }}">
                                            <i class="fas fa-layer-group"></i>
                                            Lantai
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('ruang.*') ? 'active' : '' }}" href="{{ route('ruang.index') }}">
                                            <i class="fas fa-door-open"></i>
                                            Ruang
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('okupansi.*') ? 'active' : '' }}" href="{{ route('okupansi.index') }}">
                                            <i class="fas fa-chart-pie"></i>
                                            Okupansi
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        
                        <!-- Business Management Collapse -->
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="collapse" href="#businessManagement" role="button" aria-expanded="false" aria-controls="businessManagement">
                                <i class="fas fa-briefcase"></i>
                                Business
                                <i class="fas fa-chevron-down ms-auto" style="font-size: 0.8rem;"></i>
                            </a>
                            <div class="collapse" id="businessManagement">
                                <ul class="nav flex-column ms-3">
                                    @if((Auth::guard('admin')->user()->role ?? '') !== 'staf')
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('bidang.*') ? 'active' : '' }}" href="{{ route('bidang.index') }}">
                                            <i class="fas fa-sitemap"></i>
                                            Bidang
                                        </a>
                                    </li>
                                    @endif
                                    @if((Auth::guard('admin')->user()->role ?? '') !== 'staf')
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('sub-bidang.*') ? 'active' : '' }}" href="{{ route('sub-bidang.index') }}">
                                            <i class="fas fa-sitemap"></i>
                                            Sub Bidang
                                        </a>
                                    </li>
                                    @endif
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('kontrak.*') ? 'active' : '' }}" href="{{ route('kontrak.index') }}">
                                            <i class="fas fa-file-contract"></i>
                                            Kontrak
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('realisasi.*') ? 'active' : '' }}" href="{{ route('realisasi.index') }}">
                                            <i class="fas fa-chart-line"></i>
                                            Realisasi
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        
                        <!-- Inventaris Management -->
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="collapse" href="#inventarisManagement" role="button" aria-expanded="false" aria-controls="inventarisManagement">
                                <i class="fas fa-box"></i>
                                Inventaris
                                <i class="fas fa-chevron-down ms-auto" style="font-size: 0.8rem;"></i>
                            </a>
                            <div class="collapse" id="inventarisManagement">
                                <ul class="nav flex-column ms-3">
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('inventaris.*') ? 'active' : '' }}" href="{{ route('inventaris.index') }}">
                                            <i class="fas fa-box"></i>
                                            Data Inventaris
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('kategori-inventaris.*') ? 'active' : '' }}" href="{{ route('kategori-inventaris.index') }}">
                                            <i class="fas fa-tags"></i>
                                            Kategori Inventaris
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        
                        <!-- Analytics & Tools -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('analytics.*') ? 'active' : '' }}" href="{{ route('analytics.index') }}">
                                <i class="fas fa-chart-bar"></i>
                                Analytics
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('import.*') ? 'active' : '' }}" href="{{ route('import.index') }}">
                                <i class="fas fa-upload"></i>
                                Import Data
                            </a>
                        </li>
                        
                        <!-- Admin Section -->
                        @if(in_array(Auth::guard('admin')->user()->role ?? '', ['super_admin','admin_regional','manager_bidang','admin','staf']))
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="collapse" href="#adminSection" role="button" aria-expanded="false" aria-controls="adminSection">
                                <i class="fas fa-cog"></i>
                                Admin
                                <i class="fas fa-chevron-down ms-auto" style="font-size: 0.8rem;"></i>
                            </a>
                            <div class="collapse" id="adminSection">
                                <ul class="nav flex-column ms-3">
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}" href="{{ route('admin.index') }}">
                                            <i class="fas fa-users-cog"></i>
                                            Admin Management
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('audit-log.*') ? 'active' : '' }}" href="{{ route('audit-log.index') }}">
                                            <i class="fas fa-history"></i>
                                            Audit Log
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        @endif
                        
                        <!-- Settings Section -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('mfa.*') || request()->routeIs('profile.*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#settingsSection" role="button" aria-expanded="false" aria-controls="settingsSection">
                                <i class="fas fa-cog"></i>
                                Pengaturan
                                <i class="fas fa-chevron-down ms-auto" style="font-size: 0.8rem;"></i>
                            </a>
                            <div class="collapse" id="settingsSection">
                                <ul class="nav flex-column ms-3">
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('mfa.*') ? 'active' : '' }}" href="{{ route('mfa.setup') }}">
                                            <i class="fas fa-shield-alt"></i>
                                            MFA / 2FA
                                            @if(Auth::guard('admin')->check() && Auth::guard('admin')->user()->mfa_enabled)
                                                <span class="badge bg-success ms-2" style="font-size: 0.65rem;">Aktif</span>
                                            @else
                                                <span class="badge bg-warning ms-2" style="font-size: 0.65rem;">Nonaktif</span>
                                            @endif
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                                            <i class="fas fa-user"></i>
                                            Profil Saya
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        
                        <!-- Logout -->
                        <li class="nav-item mt-3">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link text-danger w-100 text-start">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="main-content">
                    <!-- Top Navigation -->
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <!-- Page Header -->
                        <div class="page-header">
                            <div class="d-flex align-items-center">
                                <!-- PLN Logo -->
                                <div class="pln-logo me-3">
                                    <img src="{{ asset('images/logo/pln-logo.png') }}" 
                                         alt="PLN Logo" 
                                         class="pln-logo-img">
                                </div>
                                <div>
                                    <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
                                    <p class="page-subtitle">@yield('page-subtitle', 'Kelola data kantor PLN Icon Plus')</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Header Actions -->
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <!-- Global Search -->
                            <div class="me-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="globalSearch" placeholder="Cari data..." style="width: 300px;">
                                    <button class="btn btn-outline-secondary" type="button" onclick="performGlobalSearch()">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                                <!-- Search Results Dropdown -->
                                <div id="searchResults" class="dropdown-menu" style="width: 400px; max-height: 400px; overflow-y: auto; display: none;">
                                    <div id="searchResultsContent"></div>
                                </div>
                            </div>
                            
                            <!-- Notification Center -->
                            <div class="dropdown me-3">
                                <button class="btn btn-outline-secondary position-relative" type="button" data-bs-toggle="dropdown" id="notification-bell">
                                    <i class="fas fa-bell"></i>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notification-badge" style="display: none;">
                                        0
                                    </span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end notification-dropdown" style="width: 350px; max-height: 400px; overflow-y: auto;">
                                    <li class="dropdown-header">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>Notifikasi</span>
                                            <button class="btn btn-sm btn-outline-secondary" onclick="markAllAsRead()">
                                                <i class="fas fa-check-double"></i> Tandai Semua Dibaca
                                            </button>
                                        </div>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <div id="notification-list">
                                        <li class="dropdown-item text-center text-muted py-3">
                                            <i class="fas fa-bell-slash fa-2x mb-2"></i><br>
                                            Belum ada notifikasi
                                        </li>
                                    </div>
                                </ul>
                            </div>
                            
                            <!-- Page Actions -->
                            @yield('page-actions')
                        </div>
                    </div>

                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Toast Notifications from Session -->
                @if(session('toast'))
                    <div id="toast-data" style="display: none;">{{ json_encode(session('toast')) }}</div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const toastElement = document.getElementById('toast-data');
                            if (toastElement) {
                                try {
                                    const toastData = JSON.parse(toastElement.textContent);
                                    if (toastData && toastData.type && toastData.message) {
                                        if (window.Toast && window.Toast[toastData.type]) {
                                            window.Toast[toastData.type](toastData.message, {
                                                title: toastData.title || 'Notifikasi',
                                                timestamp: true
                                            });
                                        }
                                    }
                                } catch (e) {
                                    console.error('Error parsing toast data:', e);
                                }
                            }
                        });
                    </script>
                @endif

                <!-- Page Content -->
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Toast Notifications -->
    <script src="{{ asset('js/toast-notifications.js') }}"></script>
    
    <!-- PWA JavaScript -->
    <script src="{{ asset('js/pwa.js') }}"></script>
    
    @stack('scripts')
    
    <!-- Loading Screen Script -->
    <script>
    </script>

    <!-- Global Search Script -->
    <script>
    let searchTimeout;
    
    // Global search functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('globalSearch');
        const searchResults = document.getElementById('searchResults');
        const searchResultsContent = document.getElementById('searchResultsContent');
        
        // Search on input
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            
            if (query.length < 2) {
                searchResults.style.display = 'none';
                return;
            }
            
            // Debounce search
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                performSearch(query);
            }, 300);
        });
        
        // Show search results on focus
        searchInput.addEventListener('focus', function() {
            if (this.value.trim().length >= 2) {
                searchResults.style.display = 'block';
            }
        });
        
        // Hide search results when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#globalSearch') && !e.target.closest('#searchResults')) {
                searchResults.style.display = 'none';
            }
        });
    });
    
    function performSearch(query) {
        fetch(`{{ route('search.global') }}?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displaySearchResults(data.results);
                } else {
                    console.error('Search error:', data.message);
                }
            })
            .catch(error => {
                console.error('Search error:', error);
            });
    }
    
    function displaySearchResults(results) {
        const searchResults = document.getElementById('searchResults');
        const searchResultsContent = document.getElementById('searchResultsContent');
        
        if (results.length === 0) {
            searchResultsContent.innerHTML = '<div class="dropdown-item-text text-muted">Tidak ada hasil ditemukan</div>';
        } else {
            let html = '';
            results.forEach(result => {
                const typeIcon = getTypeIcon(result.type);
                html += `
                    <a href="${result.url}" class="dropdown-item">
                        <div class="d-flex align-items-center">
                            <i class="${typeIcon} me-2"></i>
                            <div>
                                <div class="fw-bold">${result.title}</div>
                                <small class="text-muted">${result.subtitle}</small>
                            </div>
                        </div>
                    </a>
                `;
            });
            searchResultsContent.innerHTML = html;
        }
        
        searchResults.style.display = 'block';
    }
    
    function getTypeIcon(type) {
        const icons = {
            'kantor': 'fas fa-building text-primary',
            'gedung': 'fas fa-home text-success',
            'ruang': 'fas fa-door-open text-info',
            'kontrak': 'fas fa-file-contract text-warning',
            'realisasi': 'fas fa-chart-line text-secondary',
            'bidang': 'fas fa-sitemap text-dark'
        };
        return icons[type] || 'fas fa-file text-muted';
    }
    
    function performGlobalSearch() {
        const query = document.getElementById('globalSearch').value.trim();
        if (query.length >= 2) {
            performSearch(query);
        }
    }
    </script>
</body>
</html>
