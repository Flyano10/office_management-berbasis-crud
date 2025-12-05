# Konfigurasi CORS & Security Headers

## Cara Mengubah Domain untuk CORS

### 1. Melalui File `.env`

Tambahkan atau edit variabel berikut di file `.env`:

```env
# Domain utama aplikasi
APP_URL=https://kantor.plniconplus.com

# Domain yang diizinkan untuk CORS (pisahkan dengan koma jika lebih dari satu)
CORS_ALLOWED_ORIGINS=https://kantor.plniconplus.com,https://admin.plniconplus.com,https://internal.pln.co.id

# Pattern regex untuk subdomain (opsional, pisahkan dengan koma jika lebih dari satu)
CORS_ALLOWED_ORIGINS_PATTERNS=^https://.*\.plniconplus\.com$,^https://.*\.pln\.co\.id$
```

### 2. Contoh Konfigurasi

#### Contoh 1: Single Domain
```env
APP_URL=https://kantor.plniconplus.com
CORS_ALLOWED_ORIGINS=https://kantor.plniconplus.com
```

#### Contoh 2: Multiple Domains
```env
APP_URL=https://kantor.plniconplus.com
CORS_ALLOWED_ORIGINS=https://kantor.plniconplus.com,https://admin.plniconplus.com,https://dashboard.plniconplus.com
```

#### Contoh 3: Dengan Subdomain Pattern (Wildcard)
```env
APP_URL=https://kantor.plniconplus.com
CORS_ALLOWED_ORIGINS=https://kantor.plniconplus.com
CORS_ALLOWED_ORIGINS_PATTERNS=^https://.*\.plniconplus\.com$
```
Ini akan mengizinkan semua subdomain seperti:
- `https://kantor.plniconplus.com`
- `https://admin.plniconplus.com`
- `https://dashboard.plniconplus.com`
- `https://api.plniconplus.com`
- dll

#### Contoh 4: Development & Production
```env
# Development
APP_URL=http://localhost:8000
CORS_ALLOWED_ORIGINS=http://localhost:8000,http://127.0.0.1:8000

# Production
APP_URL=https://kantor.plniconplus.com
CORS_ALLOWED_ORIGINS=https://kantor.plniconplus.com,https://admin.plniconplus.com
CORS_ALLOWED_ORIGINS_PATTERNS=^https://.*\.plniconplus\.com$
```

### 3. Setelah Mengubah `.env`

Jalankan perintah berikut untuk memastikan konfigurasi ter-update:

```bash
php artisan config:clear
php artisan config:cache
```

### 4. Verifikasi Konfigurasi

Untuk melihat konfigurasi CORS yang aktif, jalankan:

```bash
php artisan tinker
```

Kemudian ketik:
```php
config('cors.allowed_origins');
config('cors.allowed_origins_patterns');
```

## Security Headers

Security headers sudah dikonfigurasi otomatis dan tidak perlu diubah. Headers yang diterapkan:

- **X-Content-Type-Options**: `nosniff`
- **X-Frame-Options**: `DENY`
- **X-XSS-Protection**: `1; mode=block`
- **Referrer-Policy**: `strict-origin-when-cross-origin`
- **Permissions-Policy**: Menonaktifkan fitur browser yang tidak diperlukan
- **Content-Security-Policy**: Membatasi sumber daya yang bisa dimuat
- **Strict-Transport-Security**: Memaksa HTTPS (jika menggunakan HTTPS)

## Catatan Penting

1. **Jangan gunakan wildcard `*`** untuk `CORS_ALLOWED_ORIGINS` di production karena tidak aman
2. **Gunakan HTTPS** di production untuk keamanan maksimal
3. **Test konfigurasi** setelah deploy untuk memastikan CORS bekerja dengan benar
4. **Clear cache** setelah mengubah `.env` agar perubahan ter-apply

## Troubleshooting

### CORS Error di Browser
1. Pastikan domain di `CORS_ALLOWED_ORIGINS` sesuai dengan domain yang digunakan
2. Pastikan sudah menjalankan `php artisan config:clear` dan `php artisan config:cache`
3. Cek browser console untuk melihat error detail

### Domain Tidak Terdeteksi
1. Pastikan format URL benar (dengan `https://` atau `http://`)
2. Pastikan tidak ada spasi di antara domain (jika multiple domains)
3. Cek apakah pattern regex benar (jika menggunakan `CORS_ALLOWED_ORIGINS_PATTERNS`)

