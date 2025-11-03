# 📋 PLN ICON PLUS - ADMIN PANEL DOCUMENTATION

## 🎯 **OVERVIEW**
Dokumentasi lengkap untuk Admin Panel sistem manajemen kantor PLN Icon Plus. Panduan ini mencakup semua fitur, cara penggunaan, dan troubleshooting.

**URL Admin Panel:** `http://your-domain.com/admin`  
**Login URL:** `http://your-domain.com/login`  
**Default Admin:** `admin@pln.co.id` / `password123`

---

## 🔐 **AUTHENTICATION & ACCESS**

### **Login Admin**
1. Buka URL: `http://your-domain.com/login`
2. Masukkan email dan password admin
3. Klik tombol "Login"
4. Sistem akan redirect ke dashboard admin

### **Role & Permission**
- **Super Admin:** Akses penuh ke semua fitur termasuk manajemen admin
- **Admin:** Akses ke semua modul data management, tidak bisa akses admin management

### **Session Management**
- Session timeout: 2 jam
- Auto logout jika tidak aktif
- Single session per user (login baru akan logout session lama)

---

## 🏠 **DASHBOARD**

### **Statistik Utama**
Dashboard menampilkan ringkasan data real-time:

1. **Total Kantor** - Jumlah kantor PLN Icon Plus
2. **Total Gedung** - Jumlah gedung yang dikelola
3. **Total Ruang** - Jumlah ruang kerja
4. **Total Kontrak** - Jumlah kontrak aktif
5. **Total Okupansi** - Persentase penggunaan ruang
6. **Total Inventaris** - Jumlah aset inventaris

### **Grafik & Chart**
- **Kantor per Kota** - Pie chart distribusi kantor
- **Realisasi Kontrak** - Line chart per bulan
- **Status Kantor** - Bar chart aktif vs non-aktif
- **Okupansi Ruang** - Progress bar per gedung

### **Recent Activities**
Log aktivitas terbaru dari semua admin:
- User yang melakukan aksi
- Jenis aksi (Create, Update, Delete)
- Waktu aksi
- Detail perubahan

---

## 🏢 **DATA MANAGEMENT**

### **1. KANTOR MANAGEMENT**

#### **Fitur Utama:**
- **CRUD Operations** - Create, Read, Update, Delete kantor
- **Search & Filter** - Cari berdasarkan nama, kode, alamat, kota
- **Bulk Operations** - Hapus/export multiple kantor sekaligus
- **Export Excel** - Download data kantor ke Excel
- **Map Integration** - Tampilkan lokasi di peta

#### **Field Data Kantor:**
- **Nama Kantor** - Nama lengkap kantor
- **Kode Kantor** - Kode unik kantor (auto-generated)
- **Alamat** - Alamat lengkap kantor
- **Jenis Kantor** - Pusat, SBU, KP, dll
- **Kota** - Kota lokasi kantor
- **Status** - Aktif/Non-aktif
- **Status Kepemilikan** - Milik/Sewa
- **Luas Tanah** - Luas tanah dalam m²
- **Luas Bangunan** - Luas bangunan dalam m²
- **Koordinat** - Latitude & Longitude untuk peta

#### **Cara Menggunakan:**
1. **Tambah Kantor Baru:**
   - Klik tombol "Tambah Kantor"
   - Isi form dengan data lengkap
   - Upload foto kantor (optional)
   - Klik "Simpan"

2. **Edit Kantor:**
   - Klik icon edit di kolom aksi
   - Ubah data yang diperlukan
   - Klik "Update"

3. **Hapus Kantor:**
   - Klik icon delete di kolom aksi
   - Konfirmasi penghapusan
   - Data akan dihapus permanen

4. **Export Data:**
   - Klik tombol "Export Excel"
   - File akan otomatis download

### **2. GEDUNG MANAGEMENT**

#### **Fitur Utama:**
- **CRUD Operations** - Kelola data gedung
- **Relasi dengan Kantor** - Setiap gedung terhubung ke kantor
- **Status Tracking** - Monitor status gedung
- **Lantai Management** - Kelola lantai di setiap gedung

#### **Field Data Gedung:**
- **Nama Gedung** - Nama gedung
- **Kantor** - Kantor pemilik gedung
- **Alamat** - Alamat gedung
- **Kota** - Kota lokasi
- **Status** - Aktif/Non-aktif
- **Jumlah Lantai** - Total lantai gedung
- **Luas Total** - Luas total gedung
- **Koordinat** - GPS coordinates

### **3. LANTAI MANAGEMENT**

#### **Fitur Utama:**
- **CRUD Operations** - Kelola lantai gedung
- **Relasi dengan Gedung** - Setiap lantai terhubung ke gedung
- **Ruang Management** - Kelola ruang di setiap lantai

#### **Field Data Lantai:**
- **Nama Lantai** - Nama/nomor lantai
- **Gedung** - Gedung pemilik lantai
- **Nomor Lantai** - Nomor urut lantai
- **Luas Lantai** - Luas lantai dalam m²
- **Status** - Aktif/Non-aktif
- **Deskripsi** - Keterangan tambahan

### **4. RUANG MANAGEMENT**

#### **Fitur Utama:**
- **CRUD Operations** - Kelola ruang kerja
- **Relasi dengan Lantai** - Setiap ruang terhubung ke lantai
- **Okupansi Tracking** - Monitor penggunaan ruang
- **Inventaris Integration** - Kelola inventaris di ruang

#### **Field Data Ruang:**
- **Nama Ruang** - Nama ruang kerja
- **Lantai** - Lantai pemilik ruang
- **Tipe Ruang** - Kantor, Meeting, Storage, dll
- **Luas Ruang** - Luas ruang dalam m²
- **Kapasitas** - Jumlah orang yang bisa ditampung
- **Status** - Aktif/Non-aktif
- **Okupansi** - Persentase penggunaan

### **5. BIDANG & SUB BIDANG**

#### **Fitur Utama:**
- **Organisasi Structure** - Kelola struktur organisasi
- **Hierarchy Management** - Bidang → Sub Bidang
- **Kontrak Integration** - Terhubung dengan kontrak

#### **Field Data Bidang:**
- **Nama Bidang** - Nama departemen/bidang
- **Kode Bidang** - Kode unik bidang
- **Deskripsi** - Keterangan bidang
- **Status** - Aktif/Non-aktif

#### **Field Data Sub Bidang:**
- **Nama Sub Bidang** - Nama sub departemen
- **Bidang** - Bidang induk
- **Kode Sub Bidang** - Kode unik
- **Deskripsi** - Keterangan sub bidang

---

## 📋 **BUSINESS MANAGEMENT**

### **1. KONTRAK MANAGEMENT**

#### **Fitur Utama:**
- **CRUD Operations** - Kelola kontrak sewa
- **Status Tracking** - Monitor status kontrak
- **Realisasi Integration** - Terhubung dengan realisasi
- **Document Upload** - Upload dokumen kontrak
- **Export Excel** - Download data kontrak

#### **Field Data Kontrak:**
- **Nomor Kontrak** - Nomor unik kontrak
- **Nama Penyewa** - Nama perusahaan/instansi
- **Kantor** - Kantor yang disewa
- **Bidang** - Bidang penyewa
- **Sub Bidang** - Sub bidang penyewa
- **Tanggal Mulai** - Tanggal mulai kontrak
- **Tanggal Selesai** - Tanggal berakhir kontrak
- **Nilai Kontrak** - Nilai kontrak dalam Rupiah
- **Status** - Aktif/Selesai/Berakhir
- **Deskripsi** - Keterangan kontrak
- **Dokumen** - Upload file kontrak

#### **Cara Menggunakan:**
1. **Tambah Kontrak:**
   - Klik "Tambah Kontrak"
   - Isi form dengan data lengkap
   - Upload dokumen kontrak
   - Klik "Simpan"

2. **Monitor Kontrak:**
   - Dashboard menampilkan kontrak yang akan berakhir
   - Alert untuk kontrak yang perlu diperpanjang
   - Status tracking real-time

### **2. REALISASI MANAGEMENT**

#### **Fitur Utama:**
- **CRUD Operations** - Kelola realisasi kontrak
- **Progress Tracking** - Monitor progress realisasi
- **Chart Visualization** - Grafik realisasi per bulan
- **Export Reports** - Download laporan realisasi

#### **Field Data Realisasi:**
- **Kontrak** - Kontrak terkait
- **Bulan** - Bulan realisasi
- **Tahun** - Tahun realisasi
- **Target** - Target realisasi
- **Realisasi** - Realisasi aktual
- **Persentase** - Persentase pencapaian
- **Status** - On Track/Delay/Overdue
- **Keterangan** - Catatan realisasi

### **3. OKUPANSI MANAGEMENT**

#### **Fitur Utama:**
- **CRUD Operations** - Kelola data okupansi
- **Real-time Monitoring** - Monitor penggunaan ruang
- **Chart Visualization** - Grafik okupansi per ruang
- **Alert System** - Notifikasi ruang kosong/penuh

#### **Field Data Okupansi:**
- **Ruang** - Ruang yang diukur
- **Tanggal** - Tanggal pengukuran
- **Jumlah Orang** - Jumlah orang di ruang
- **Kapasitas** - Kapasitas maksimal ruang
- **Persentase** - Persentase okupansi
- **Status** - Normal/Penuh/Kosong
- **Keterangan** - Catatan okupansi

---

## 📦 **INVENTARIS MANAGEMENT**

### **1. INVENTARIS DATA**

#### **Fitur Utama:**
- **CRUD Operations** - Kelola data inventaris
- **Kategori Management** - Kelola kategori inventaris
- **Location Tracking** - Track lokasi inventaris
- **Condition Monitoring** - Monitor kondisi aset
- **Export Excel** - Download data inventaris

#### **Field Data Inventaris:**
- **Nama Barang** - Nama item inventaris
- **Kode Barang** - Kode unik barang
- **Kategori** - Kategori inventaris
- **Merek** - Merek barang
- **Model** - Model barang
- **Serial Number** - Nomor seri
- **Tanggal Pembelian** - Tanggal beli
- **Harga** - Harga pembelian
- **Kondisi** - Baik/Rusak/Hilang
- **Lokasi** - Ruang/kantor lokasi
- **Status** - Aktif/Non-aktif
- **Foto** - Upload foto barang

### **2. KATEGORI INVENTARIS**

#### **Fitur Utama:**
- **CRUD Operations** - Kelola kategori inventaris
- **Hierarchy Support** - Kategori dan sub kategori
- **Icon Management** - Set icon untuk kategori

#### **Field Data Kategori:**
- **Nama Kategori** - Nama kategori
- **Kode Kategori** - Kode unik kategori
- **Deskripsi** - Keterangan kategori
- **Icon** - Icon kategori
- **Status** - Aktif/Non-aktif

---

## 📊 **ANALYTICS & REPORTING**

### **1. ANALYTICS DASHBOARD**

#### **Fitur Utama:**
- **Interactive Charts** - Grafik interaktif dengan Chart.js
- **Real-time Data** - Data real-time dari database
- **Export Charts** - Download grafik sebagai gambar
- **Filter Options** - Filter berdasarkan periode, lokasi, dll

#### **Chart Types:**
- **Bar Chart** - Data per kategori
- **Pie Chart** - Distribusi data
- **Line Chart** - Trend data per waktu
- **Area Chart** - Area coverage data

### **2. REPORTING SYSTEM**

#### **Fitur Utama:**
- **Custom Reports** - Buat laporan custom
- **Scheduled Reports** - Laporan terjadwal
- **Export Options** - PDF, Excel, CSV
- **Email Reports** - Kirim laporan via email

#### **Report Types:**
- **Kantor Report** - Laporan data kantor
- **Kontrak Report** - Laporan kontrak
- **Okupansi Report** - Laporan okupansi
- **Inventaris Report** - Laporan inventaris
- **Financial Report** - Laporan keuangan

---

## 🔍 **SEARCH & FILTER**

### **1. GLOBAL SEARCH**

#### **Fitur Utama:**
- **Real-time Search** - Pencarian real-time
- **Multi-table Search** - Cari di semua tabel
- **Search Suggestions** - Saran pencarian
- **Search History** - Riwayat pencarian

#### **Cara Menggunakan:**
1. Ketik di search box di header
2. Sistem akan menampilkan hasil real-time
3. Klik hasil untuk langsung ke detail
4. Filter hasil berdasarkan kategori

### **2. ADVANCED FILTER**

#### **Fitur Utama:**
- **Multiple Filters** - Filter berdasarkan multiple criteria
- **Date Range Filter** - Filter berdasarkan tanggal
- **Status Filter** - Filter berdasarkan status
- **Location Filter** - Filter berdasarkan lokasi
- **Save Filters** - Simpan filter untuk digunakan lagi

---

## 📤 **IMPORT/EXPORT**

### **1. IMPORT DATA**

#### **Fitur Utama:**
- **Excel Import** - Import data dari Excel
- **CSV Import** - Import data dari CSV
- **Template Download** - Download template import
- **Validation** - Validasi data sebelum import
- **Error Handling** - Handle error import

#### **Cara Menggunakan:**
1. Download template Excel
2. Isi data sesuai template
3. Upload file Excel
4. Preview data yang akan diimport
5. Konfirmasi import

### **2. EXPORT DATA**

#### **Fitur Utama:**
- **Excel Export** - Export ke Excel dengan format rapi
- **CSV Export** - Export ke CSV
- **PDF Export** - Export ke PDF
- **Custom Format** - Format export custom
- **Bulk Export** - Export multiple data sekaligus

---

## 🗺️ **MAP INTEGRATION**

### **1. INTERACTIVE MAP**

#### **Fitur Utama:**
- **Leaflet.js Integration** - Peta interaktif
- **Marker Clustering** - Cluster marker untuk performa
- **Custom Markers** - Marker custom untuk kantor/gedung
- **Popup Information** - Info detail di popup
- **Layer Control** - Kontrol layer peta

#### **Map Features:**
- **Zoom Control** - Zoom in/out
- **Layer Toggle** - Toggle kantor/gedung layer
- **Search on Map** - Cari lokasi di peta
- **Fullscreen Mode** - Mode fullscreen
- **Export Map** - Export peta sebagai gambar

### **2. LOCATION MANAGEMENT**

#### **Fitur Utama:**
- **GPS Coordinates** - Set koordinat GPS
- **Address Validation** - Validasi alamat
- **Map Preview** - Preview lokasi di peta
- **Bulk Location Update** - Update lokasi bulk

---

## 👥 **ADMIN MANAGEMENT**

### **1. ADMIN USERS**

#### **Fitur Utama:**
- **CRUD Operations** - Kelola admin users
- **Role Management** - Set role admin
- **Password Management** - Reset password
- **Activity Logging** - Log aktivitas admin

#### **Field Data Admin:**
- **Nama Admin** - Nama lengkap admin
- **Email** - Email admin (untuk login)
- **Password** - Password admin
- **Role** - Super Admin/Admin
- **Status** - Aktif/Non-aktif
- **Last Login** - Terakhir login
- **Created At** - Tanggal dibuat

### **2. AUDIT LOG**

#### **Fitur Utama:**
- **Activity Tracking** - Track semua aktivitas
- **User Tracking** - Track per user
- **Action Tracking** - Track per aksi
- **Export Logs** - Export log ke Excel
- **Search Logs** - Cari di log

#### **Log Information:**
- **User** - Admin yang melakukan aksi
- **Action** - Jenis aksi (Create, Update, Delete)
- **Model** - Model yang diubah
- **Old Values** - Nilai lama
- **New Values** - Nilai baru
- **IP Address** - IP address user
- **User Agent** - Browser user
- **Timestamp** - Waktu aksi

---

## 🔧 **SYSTEM SETTINGS**

### **1. APPLICATION SETTINGS**

#### **Fitur Utama:**
- **Site Configuration** - Konfigurasi situs
- **Email Settings** - Konfigurasi email
- **File Upload Settings** - Konfigurasi upload
- **Backup Settings** - Konfigurasi backup

### **2. SECURITY SETTINGS**

#### **Fitur Utama:**
- **Password Policy** - Kebijakan password
- **Session Settings** - Konfigurasi session
- **Login Attempts** - Batas percobaan login
- **IP Whitelist** - Daftar IP yang diizinkan

---

## 🚀 **PERFORMANCE & OPTIMIZATION**

### **1. CACHING SYSTEM**

#### **Fitur Utama:**
- **Database Caching** - Cache query database
- **View Caching** - Cache view template
- **Config Caching** - Cache konfigurasi
- **Route Caching** - Cache routing

### **2. DATABASE OPTIMIZATION**

#### **Fitur Utama:**
- **Query Optimization** - Optimasi query
- **Index Management** - Kelola database index
- **Connection Pooling** - Pool koneksi database
- **Query Monitoring** - Monitor query performance

---

## 🔒 **SECURITY FEATURES**

### **1. AUTHENTICATION**

#### **Fitur Utama:**
- **Strong Password Policy** - Kebijakan password kuat
- **Password History** - Riwayat password
- **Account Lockout** - Lock account setelah percobaan gagal
- **Session Management** - Kelola session user

### **2. AUTHORIZATION**

#### **Fitur Utama:**
- **Role-based Access** - Akses berdasarkan role
- **Permission System** - Sistem permission detail
- **Middleware Protection** - Proteksi middleware
- **CSRF Protection** - Proteksi CSRF

### **3. DATA PROTECTION**

#### **Fitur Utama:**
- **Input Validation** - Validasi input
- **SQL Injection Protection** - Proteksi SQL injection
- **XSS Protection** - Proteksi XSS
- **File Upload Security** - Keamanan upload file

---

## 📱 **RESPONSIVE DESIGN**

### **1. MOBILE OPTIMIZATION**

#### **Fitur Utama:**
- **Responsive Layout** - Layout responsif
- **Touch-friendly Interface** - Interface touch-friendly
- **Mobile Navigation** - Navigasi mobile
- **Progressive Web App** - PWA support

### **2. CROSS-BROWSER SUPPORT**

#### **Fitur Utama:**
- **Chrome Support** - Support Chrome
- **Firefox Support** - Support Firefox
- **Safari Support** - Support Safari
- **Edge Support** - Support Edge

---

## 🛠️ **TROUBLESHOOTING**

### **1. COMMON ISSUES**

#### **Login Issues:**
- **Forgot Password:** Klik "Lupa Password" di halaman login
- **Account Locked:** Hubungi super admin untuk unlock
- **Session Expired:** Login ulang

#### **Data Issues:**
- **Data Not Loading:** Refresh halaman atau clear cache
- **Export Failed:** Check file permission atau disk space
- **Import Failed:** Check format file atau data validation

#### **Performance Issues:**
- **Slow Loading:** Clear cache atau restart server
- **Memory Issues:** Increase PHP memory limit
- **Database Issues:** Check database connection

### **2. ERROR CODES**

#### **Common Error Codes:**
- **500:** Internal Server Error - Check server logs
- **404:** Page Not Found - Check URL atau route
- **403:** Forbidden - Check permission
- **422:** Validation Error - Check input data

### **3. LOG FILES**

#### **Log Locations:**
- **Laravel Log:** `storage/logs/laravel.log`
- **Error Log:** `storage/logs/error.log`
- **Access Log:** `storage/logs/access.log`

---

## 📞 **SUPPORT & MAINTENANCE**

### **1. REGULAR MAINTENANCE**

#### **Daily Tasks:**
- Check system logs
- Monitor disk space
- Check backup status
- Monitor performance

#### **Weekly Tasks:**
- Review audit logs
- Check security updates
- Optimize database
- Clean temporary files

#### **Monthly Tasks:**
- Full system backup
- Security audit
- Performance review
- Update documentation

### **2. BACKUP & RECOVERY**

#### **Backup Types:**
- **Database Backup:** Daily, weekly, monthly
- **File Backup:** Daily backup of uploads
- **Config Backup:** Backup configuration files
- **Code Backup:** Version control backup

#### **Recovery Procedures:**
- **Database Recovery:** Restore from backup
- **File Recovery:** Restore uploaded files
- **System Recovery:** Full system restore
- **Data Recovery:** Recover deleted data

---

## 🎯 **BEST PRACTICES**

### **1. DATA MANAGEMENT**

#### **Best Practices:**
- **Regular Backups:** Backup data secara berkala
- **Data Validation:** Validasi data sebelum input
- **Data Integrity:** Maintain data integrity
- **Data Security:** Protect sensitive data

### **2. USER MANAGEMENT**

#### **Best Practices:**
- **Strong Passwords:** Gunakan password kuat
- **Regular Updates:** Update password berkala
- **Access Control:** Kontrol akses user
- **Activity Monitoring:** Monitor aktivitas user

### **3. SYSTEM MAINTENANCE**

#### **Best Practices:**
- **Regular Updates:** Update sistem berkala
- **Security Patches:** Apply security patches
- **Performance Monitoring:** Monitor performa sistem
- **Documentation:** Update dokumentasi

---

## 📋 **QUICK REFERENCE**

### **Keyboard Shortcuts:**
- **Ctrl + S:** Save form
- **Ctrl + E:** Export data
- **Ctrl + F:** Search
- **Ctrl + N:** New record
- **Ctrl + D:** Delete record
- **F5:** Refresh page

### **URL Patterns:**
- **Dashboard:** `/admin/dashboard`
- **Kantor:** `/admin/kantor`
- **Kontrak:** `/admin/kontrak`
- **Analytics:** `/admin/analytics`
- **Admin:** `/admin/admin`

### **File Upload Limits:**
- **Max File Size:** 10MB
- **Allowed Types:** JPG, PNG, PDF, DOC, XLS
- **Upload Path:** `storage/app/uploads/`

---

## 🏆 **SYSTEM STATISTICS**

### **Current Stats:**
- **Total Files:** 200+ files
- **Controllers:** 25+ controllers
- **Models:** 18 models
- **Views:** 60+ view files
- **Routes:** 100+ routes
- **Database Tables:** 16 tables
- **Lines of Code:** 15,000+ lines

### **Performance Metrics:**
- **Page Load Time:** < 2 seconds
- **Database Response:** < 500ms
- **Memory Usage:** < 128MB
- **CPU Usage:** < 50%

---

**Last Updated:** 20 October 2025  
**Version:** 1.0.0  
**Status:** Production Ready ✅  
**Developer:** Rafly Juliano

---

*Dokumentasi ini dibuat khusus untuk PLN Icon Plus Kantor Management System. Untuk pertanyaan atau support, hubungi developer.*
