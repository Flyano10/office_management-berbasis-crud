# SIAP - Sistem Informasi Asset & Property PLN Icon Plus

Aplikasi web untuk mengelola aset dan properti perusahaan PLN Icon Plus. Sistem ini dibuat untuk memudahkan tracking inventaris, kontrak, lokasi kantor, dan data terkait lainnya dalam satu platform terpusat.

##  Tentang Project

Project ini dikembangkan untuk kebutuhan internal PLN Icon Plus dalam mengelola semua data aset dan properti perusahaan. Mulai dari inventaris barang, kontrak perjanjian, hingga informasi detail setiap kantor bisa diakses dan dikelola melalui sistem ini.

Sistem ini punya dua bagian utama:
- **Halaman Public**: Untuk melihat informasi umum seperti peta lokasi kantor, profil perusahaan, dan scan barcode inventaris
- **Halaman Admin**: Untuk mengelola semua data dengan akses berdasarkan role pengguna

##  Fitur Utama

### 1. Manajemen Inventaris
- CRUD lengkap untuk data inventaris
- Kategori inventaris yang bisa dikustomisasi
- Generate QR Code untuk setiap inventaris yang bisa di-print dan di-scan
- Filter berdasarkan kategori, kondisi, bidang, dan lokasi
- Bulk actions untuk operasi massal (hapus, update status)
- Export data ke Excel

### 2. Manajemen Kontrak
- Kelola semua kontrak perjanjian perusahaan
- Tracking tanggal mulai dan selesai kontrak
- Notifikasi kontrak yang mendekati jatuh tempo (6 bulan, 3 bulan, 1 bulan)
- Filter dan pencarian kontrak
- Export data kontrak

### 3. Peta Interaktif
- Peta Indonesia dengan semua lokasi kantor PLN Icon Plus
- Marker berbeda warna berdasarkan jenis kantor (Pusat, SBU, Perwakilan, Gudang)
- Popup detail untuk setiap kantor dengan tab:
  - **Umum**: Info dasar kantor, pegawai, fasilitas
  - **Inventaris**: Daftar inventaris di kantor tersebut
  - **Kontrak**: Daftar kontrak terkait
  - **Laporan**: Laporan inventaris dengan filter
- Fitur pencarian kantor
- Filter berdasarkan kota dan jenis kantor

### 4. Analytics Dashboard
- Statistik overview (total kantor, gedung, ruang, dll)
- Grafik visualisasi data
- Chart untuk distribusi inventaris, kontrak, dan okupansi
- Activity log untuk tracking perubahan data

### 5. Role-Based Access Control (RBAC)
Sistem ini punya beberapa level akses:
- **Super Admin**: Akses penuh ke semua data
- **Admin Regional**: Hanya bisa akses data di kantornya
- **Manager Bidang**: Akses data di kantor dan bidangnya
- **Staf**: Akses terbatas sesuai kantor dan bidang

### 6. Keamanan
- Multi-Factor Authentication (MFA) menggunakan Google Authenticator
- Backup codes untuk recovery
- Session management
- Audit log untuk tracking semua aktivitas pengguna

### 7. Data Management Center
Fitur lengkap untuk kelola data:
- **Import Data**: Import data dari file Excel/CSV
- **Export Data**: Export berbagai data ke Excel/CSV
- **Backup & Restore**: Backup database dan restore jika diperlukan
- **Report Generation**: Generate laporan dalam berbagai format

### 8. Scan Barcode Inventaris
- Generate QR Code untuk setiap inventaris
- Scan QR Code langsung redirect ke halaman peta dengan auto-open modal kantor
- Highlight inventaris yang di-scan di daftar inventaris

##  Teknologi yang Digunakan

- **Backend**: Laravel 10
- **Frontend**: Blade Templates, Bootstrap 5, JavaScript (Vanilla)
- **Database**: MySQL
- **Maps**: Leaflet.js untuk peta interaktif
- **Charts**: Chart.js untuk visualisasi data
- **QR Code**: qrcode.js untuk generate QR Code
- **Excel**: Maatwebsite Excel untuk import/export
- **MFA**: Google2FA untuk two-factor authentication

##  Requirements

- PHP >= 8.1
- Composer
- Node.js & NPM (untuk Vite)
- MySQL 5.7+ atau MariaDB 10.3+
- Web server (Apache/Nginx) atau Laragon untuk development

##  Instalasi

### 1. Clone Repository
```bash
git clone https://github.com/username/office_management-berbasis-crud.git
cd office_management-berbasis-crud
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### 3. Setup Environment
```bash
# Copy file .env
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Konfigurasi Database
Edit file `.env` dan sesuaikan konfigurasi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=username
DB_PASSWORD=password
```

### 5. Migrasi Database
```bash
# Run migrations
php artisan migrate

# (Optional) Run seeders jika ada
php artisan db:seed
```

### 6. Build Assets
```bash
# Development
npm run dev

# Production
npm run build
```

### 7. Setup Storage Link
```bash
php artisan storage:link
```

### 8. Jalankan Server
```bash
php artisan serve
```

Akses aplikasi di `http://localhost:8000`

## 👤 Default User

Setelah migrasi, buat user admin pertama melalui tinker:
```bash
php artisan tinker
```

Lalu jalankan:
```php
$admin = App\Models\Admin::create([
    'nama' => 'Admin',
    'email' => 'admin@pln.co.id',
    'password' => bcrypt('password'),
    'role' => 'super_admin'
]);
```

**Note**: Ganti email dan password sesuai kebutuhan, dan jangan lupa setup MFA setelah login pertama kali.

##  Struktur Project

```
office_management-berbasis-crud/
├── app/
│   ├── Http/
│   │   └── Controllers/     # Semua controller
│   └── Models/               # Eloquent models
├── database/
│   ├── migrations/          # Database migrations
│   └── seeders/            # Database seeders
├── resources/
│   ├── views/
│   │   ├── admin/          # Views untuk halaman admin
│   │   ├── public/         # Views untuk halaman public
│   │   └── layouts/        # Layout templates
│   └── css/               # CSS files
├── routes/
│   ├── web.php             # Routes untuk admin
│   └── public.php          # Routes untuk public
├── public/                 # Public assets
└── storage/                # Storage untuk uploads
```

##  Fitur Keamanan

### Multi-Factor Authentication (MFA)
Setiap admin wajib setup MFA setelah login pertama. Sistem menggunakan Google Authenticator untuk 2FA. Backup codes disediakan untuk recovery jika kehilangan akses ke authenticator.

### Role-Based Access
Setiap user punya role yang menentukan akses mereka:
- Data hanya bisa diakses sesuai scope role
- Super admin bisa akses semua
- Admin regional hanya bisa akses data kantornya
- Manager bidang hanya bisa akses data di bidangnya
- Staf akses terbatas sesuai kantor dan bidang

### Audit Log
Semua aktivitas penting dicatat di audit log:
- Create, update, delete data
- Login dan logout
- Perubahan role atau akses
- Aktivitas penting lainnya

##  Fitur Analytics

Dashboard analytics menampilkan:
- Total kantor, gedung, ruang
- Statistik inventaris per kategori
- Grafik distribusi kontrak
- Chart okupansi ruang
- Activity log terbaru

##  Fitur Peta

Peta interaktif menggunakan Leaflet.js dengan fitur:
- Marker berbeda warna per jenis kantor
- Popup detail dengan tab informasi
- Pencarian kantor
- Filter berdasarkan kota dan jenis
- Auto-zoom ke lokasi yang dipilih

##  Scan Barcode

Setiap inventaris punya QR Code yang bisa:
- Di-print dan ditempel di barang fisik
- Di-scan menggunakan aplikasi QR scanner
- Auto-redirect ke halaman peta dengan modal kantor terbuka
- Highlight item yang di-scan di daftar inventaris

##  Import & Export

### Import Data
- Support format Excel (.xlsx) dan CSV
- Import data inventaris, kontrak, dan data master lainnya
- Validasi data sebelum import
- Error handling untuk data yang tidak valid

### Export Data
- Export ke Excel dengan format rapi
- Export ke CSV untuk kebutuhan lain
- Filter data sebelum export
- Support export berbagai jenis data

##  Backup & Restore

Fitur backup database:
- Backup otomatis atau manual
- Download backup file
- Restore dari backup file
- Log backup history

##  Troubleshooting

### Error "Class not found"
```bash
composer dump-autoload
php artisan optimize:clear
```

### Error "Route not found"
```bash
php artisan route:clear
php artisan cache:clear
```

### QR Code tidak muncul
Pastikan library qrcode.js sudah ter-load. Cek console browser untuk error JavaScript.

### Modal tidak muncul
Pastikan Bootstrap 5 sudah ter-load dengan benar. Cek apakah ada error JavaScript di console.

##  Catatan Development

- Project ini menggunakan Laravel 10 dengan PHP 8.1+
- Untuk development, disarankan pakai Laragon atau Laravel Sail
- Pastikan file `.env` sudah dikonfigurasi dengan benar
- Jangan commit file `.env` ke repository
- Gunakan `php artisan optimize:clear` jika ada masalah cache

##  Kontribusi

Untuk kontribusi ke project ini:
1. Fork repository
2. Buat branch baru untuk fitur (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## License

Project ini untuk penggunaan interna.

##  Developer

Dikembangkan untuk kebutuhan internal.

---

**Note**: Untuk pertanyaan atau issue, silakan buat issue di repository atau hubungi development.

