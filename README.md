PLN ICON PLUS - KANTOR MANAGEMENT SYSTEM

Sistem manajemen kantor untuk PLN Icon Plus yang udah gw buat dari nol.

[![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1+-blue.svg)](https://php.net)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple.svg)](https://getbootstrap.com)

Tentang Sistem Ini

Ini sistem yang gw buat buat PLN Icon Plus buat ngelola semua aset kantor mereka. Dari gedung, lantai, ruang, sampe kontrak sewa - semua bisa diatur dari sini.

Client: PLN Icon Plus  
Status: Production Ready  
Tech Stack: Laravel 10 + Bootstrap 5 + MySQL  



Fitur Utama

Manajemen Data Kantor
1. Kantor - Ngelola semua kantor PLN Icon Plus
2. Gedung - Manajemen gedung dan bangunan
3. Lantai - Kelola lantai di setiap gedung
4. Ruang - Manajemen ruang kerja
5. Bidang & Sub Bidang - Organisasi departemen

Manajemen Kontrak & Realisasi
1. Kontrak - Kelola kontrak sewa dan perjanjian
2. Realisasi - Tracking realisasi kontrak
3. Okupansi - Monitoring penggunaan ruang
4. Inventaris - Manajemen aset dan inventaris

Sistem Admin
1. Login Admin - Autentikasi aman
2. Role Management - Super Admin & Admin
3. Audit Log - Tracking semua aktivitas
4. Session Management - Keamanan session

Dashboard & Analytics
1. Dashboard Utama - Statistik real-time
2. Analytics - Grafik dan laporan
3. Peta Interaktif - Visualisasi lokasi kantor
4. Export Excel - Download data ke Excel

Fitur Publik
1. Halaman Publik - Info untuk stakeholder
2. Peta Publik - Lihat lokasi kantor
3. Directory - Daftar kantor PLN Icon Plus

---

Tech Stack

Backend
1. Laravel 10.x - Framework PHP
2. MySQL - Database
3. Laravel Sanctum - API Authentication
4. Maatwebsite Excel - Import/Export Excel

Frontend
1. Bootstrap 5 - CSS Framework
2. Chart.js - Grafik dan chart
3. Leaflet.js - Maps interaktif
4. Font Awesome - Icons
5. Vanilla JavaScript - Interaksi frontend

---

Database

Tabel Utama
1. provinsi - Data provinsi
2. kota - Data kota
3. jenis_kantor - Jenis-jenis kantor
4. kantor - Data kantor PLN Icon Plus
5. gedung - Data gedung
6. lantai - Data lantai
7. ruang - Data ruang
8. bidang - Data bidang/departemen
9. sub_bidang - Data sub bidang
10. kontrak - Data kontrak sewa
11. realisasi - Data realisasi kontrak
12. okupansi - Data okupansi ruang
13. inventaris - Data inventaris
14. kategori_inventaris - Kategori inventaris
15. admin - Data admin sistem
16. audit_logs - Log aktivitas sistem


Installation

Yang Dibutuhkan
1. PHP 8.1 atau lebih tinggi
2. Composer
3. MySQL
4. Node.js & NPM

Langkah Setup

1. Clone Repository
```bash
git clone https://github.com/yourusername/pln-kantor-management.git
cd pln-kantor-management
```

2. Install Dependencies
```bash
composer install
npm install
```

3. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

4. Setup Database
```bash
php artisan migrate
php artisan db:seed
```

5. Build Assets
```bash
npm run build
```

6. Jalankan Server
```bash
php artisan serve
```


Fitur Unggulan

Global Search
1. Cari data di semua modul sekaligus
2. Filter berdasarkan kategori
3. Hasil real-time

Import/Export
1. Import data dari Excel
2. Export data ke Excel dengan format rapi
3. Template import yang mudah dipakai

Peta Interaktif
1. Tampilkan semua kantor di peta
2. Filter berdasarkan kota/jenis
3. Info detail setiap kantor

Responsive Design
1. Bisa dipakai di desktop, tablet, mobile
2. UI yang clean dan mudah dipakai
3. Loading yang cepat

Keamanan
1. Password yang aman
2. Session timeout
3. Audit trail lengkap
4. Role-based access



API Documentation

API lengkap ada di API_DOCUMENTATION.md

Endpoint Utama
1. GET /api/v1/dashboard - Data dashboard
2. GET /api/v1/kantor - List kantor
3. POST /api/v1/kontrak - Buat kontrak baru
4. GET /api/v1/analytics - Data analytics



Statistik Project

1. Total Files: 200+ files
2. Controllers: 25+ controllers
3. Models: 18 models
4. Views: 60+ view files
5. Routes: 100+ routes
6. Database Tables: 16 tables
7. Lines of Code: 15,000+ lines



Status Project

1. Database - 16 tabel dengan relasi lengkap
2. CRUD Operations - Semua modul lengkap
3. Authentication - Login admin aman
4. Dashboard - Analytics & statistik
5. Advanced Features - Search, filter, bulk ops
6. File Management - Upload/download
7. Map Integration - Peta interaktif
8. Responsive Design - Mobile-friendly
9. Security - Enterprise-level security
10. Performance - Optimized untuk production

Status: 100% Komplite Siap Produksi


Support

Developer: Rafly Juliano  
Project: PLN ICON Plus Kantor Management system 


License

Project ini menggunakan MIT License - lihat file LICENSE untuk detail.

