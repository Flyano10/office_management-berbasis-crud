# SIAP - Sistem Informasi Asset & Property PLN Icon Plus

Sistem internal buat nge-track inventaris, kontrak, sama lokasi kantor PLN Icon Plus. Jadi semua data aset perusahaan bisa dikelola dari satu tempat.

## Yang Bisa Dilakuin

**Inventaris**
- Kelola data barang, ada QR code buat setiap item yang bisa di-scan
- Filter berdasarkan kategori/kondisi/lokasi
- Bulk action buat operasi massal
- Export ke Excel

**Kontrak**
- Tracking kontrak perjanjian
- Notifikasi otomatis kalau kontrak mau habis (6/3/1 bulan sebelumnya)
- Export data kontrak

**Peta Kantor**
- Peta interaktif semua lokasi kantor se-Indonesia
- Marker berwarna beda per jenis kantor
- Popup detail per kantor (info umum, inventaris, kontrak, laporan)
- Search dan filter berdasarkan kota/jenis

**Analytics**
- Dashboard statistik
- Chart distribusi inventaris dan kontrak
- Activity log

**Keamanan**
- Multi-Factor Authentication (Google Authenticator)
- Role-based access (Super Admin, Admin Regional, Manager, Staf)
- Audit log buat tracking aktivitas

**Data Management**
- Import/Export Excel & CSV
- Backup & Restore database
- Generate laporan

## Tech Stack

- Laravel 10, PHP 8.1+
- MySQL 5.7+
- Bootstrap 5 + Vanilla JS
- Leaflet.js (peta), Chart.js (grafik)
- Maatwebsite Excel, Google2FA

## Setup

```bash
# Clone dan install
git clone https://github.com/username/office_management-berbasis-crud.git
cd office_management-berbasis-crud
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Edit .env, sesuaikan DB config
# DB_DATABASE=nama_database
# DB_USERNAME=username
# DB_PASSWORD=password

# Migrate database
php artisan migrate

# Build assets & link storage
npm run build
php artisan storage:link

# Jalankan
php artisan serve
```

## Bikin User Admin Pertama

```bash
php artisan tinker
```

```php
App\Models\Admin::create([
    'nama' => 'Admin',
    'email' => 'admin@pln.co.id',
    'password' => bcrypt('password'),
    'role' => 'super_admin'
]);
```

Ganti email & password sesuai kebutuhan. Jangan lupa setup MFA setelah login pertama.

## Struktur Folder

```
├── app/Http/Controllers/    # Controllers
├── app/Models/              # Models
├── database/migrations/     # Migrations
├── resources/views/
│   ├── admin/              # Halaman admin
│   └── public/             # Halaman public
├── routes/
│   ├── web.php            # Admin routes
│   └── public.php         # Public routes
└── public/                # Assets
```

## Role & Akses

- **Super Admin**: Akses semua data
- **Admin Regional**: Data di kantornya doang
- **Manager Bidang**: Data kantor + bidangnya
- **Staf**: Akses terbatas sesuai scope

## Troubleshoot

**Error "Class not found"**
```bash
composer dump-autoload
php artisan optimize:clear
```

**Route error**
```bash
php artisan route:clear
php artisan cache:clear
```

**QR Code ga muncul**: Cek console browser, pastikan qrcode.js ke-load

**Modal ga kebuka**: Pastikan Bootstrap 5 udah ter-load dengan benar

## Notes

- Jangan commit file `.env`
- Pakai Laragon atau Laravel Sail buat development
- Kalau ada masalah cache: `php artisan optimize:clear`
- Project ini untuk internal use only

---

Butuh bantuan? Bikin issue atau kontak Saya.