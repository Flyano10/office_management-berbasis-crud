# 📚 PLN ICON PLUS - API DOCUMENTATION

## 🎯 **OVERVIEW**
RESTful API untuk sistem manajemen kantor PLN Icon Plus dengan fitur lengkap untuk CRUD operations, analytics, dan monitoring.

**Base URL:** `http://your-domain.com/api/v1`  
**Version:** 1.0.0  
**Authentication:** Bearer Token (Admin)  

---

## 🔐 **AUTHENTICATION**

### Login
```http
POST /api/v1/auth/login
Content-Type: application/json

{
    "email": "admin@pln.co.id",
    "password": "password"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "nama_admin": "Super Admin",
            "email": "admin@pln.co.id",
            "role": "super_admin"
        },
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
    },
    "timestamp": "2025-01-24T10:30:00.000Z"
}
```

### Logout
```http
POST /api/v1/auth/logout
Authorization: Bearer {token}
```

---

## 📊 **DASHBOARD API**

### Get Dashboard Data
```http
GET /api/v1/dashboard
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "message": "Dashboard data retrieved successfully",
    "data": {
        "total_kantor": 25,
        "total_gedung": 45,
        "total_ruang": 120,
        "total_kontrak": 15,
        "total_okupansi": 85,
        "status_stats": {
            "aktif": 20,
            "non_aktif": 5
        },
        "recent_activities": [...],
        "kantor_by_kota": [...],
        "realisasi_by_month": [...]
    },
    "timestamp": "2025-01-24T10:30:00.000Z"
}
```

### Get Dashboard Statistics
```http
GET /api/v1/dashboard/statistics
Authorization: Bearer {token}
```

---

## 🏢 **KANTOR API**

### List Kantor
```http
GET /api/v1/kantor?page=1&per_page=15&search=jakarta&jenis_kantor_id=1&status=aktif
Authorization: Bearer {token}
```

**Query Parameters:**
- `page` (int): Page number (default: 1)
- `per_page` (int): Items per page (default: 15, max: 100)
- `search` (string): Search in nama_kantor, alamat, kode_kantor
- `jenis_kantor_id` (int): Filter by jenis kantor
- `kota_id` (int): Filter by kota
- `status` (string): Filter by status (aktif, non_aktif)

**Response:**
```json
{
    "success": true,
    "message": "Kantor data retrieved successfully",
    "data": {
        "kantor": [
            {
                "id": 1,
                "nama_kantor": "Kantor Pusat Jakarta",
                "kode_kantor": "KPJ001",
                "alamat": "Jl. Thamrin No. 1",
                "status": "aktif",
                "status_kepemilikan": "milik",
                "luas_tanah": 5000,
                "luas_bangunan": 3000,
                "latitude": -6.2088,
                "longitude": 106.8456,
                "jenis_kantor": {
                    "id": 1,
                    "nama_jenis": "Kantor Pusat"
                },
                "kota": {
                    "id": 1,
                    "nama_kota": "Jakarta"
                }
            }
        ],
        "pagination": {
            "current_page": 1,
            "per_page": 15,
            "total": 25,
            "last_page": 2,
            "from": 1,
            "to": 15,
            "has_more_pages": true
        }
    },
    "timestamp": "2025-01-24T10:30:00.000Z"
}
```

### Create Kantor
```http
POST /api/v1/kantor
Authorization: Bearer {token}
Content-Type: application/json

{
    "nama_kantor": "Kantor Cabang Bandung",
    "kode_kantor": "KCB001",
    "alamat": "Jl. Asia Afrika No. 1",
    "jenis_kantor_id": 2,
    "kota_id": 2,
    "status": "aktif",
    "status_kepemilikan": "milik",
    "luas_tanah": 3000,
    "luas_bangunan": 2000,
    "latitude": -6.9175,
    "longitude": 107.6191
}
```

### Get Kantor by ID
```http
GET /api/v1/kantor/{id}
Authorization: Bearer {token}
```

### Update Kantor
```http
PUT /api/v1/kantor/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "nama_kantor": "Kantor Cabang Bandung Updated",
    "status": "aktif"
}
```

### Delete Kantor
```http
DELETE /api/v1/kantor/{id}
Authorization: Bearer {token}
```

### Get Kantor Statistics
```http
GET /api/v1/kantor/{id}/statistics
Authorization: Bearer {token}
```

---

## 📋 **KONTRAK API**

### List Kontrak
```http
GET /api/v1/kontrak?page=1&per_page=15&search=sewa&kantor_id=1&status=aktif
Authorization: Bearer {token}
```

**Query Parameters:**
- `page` (int): Page number (default: 1)
- `per_page` (int): Items per page (default: 15, max: 100)
- `search` (string): Search in nomor_kontrak, nama_penyewa, deskripsi
- `kantor_id` (int): Filter by kantor
- `bidang_id` (int): Filter by bidang
- `status` (string): Filter by status (aktif, selesai, berakhir)
- `tanggal_mulai_from` (date): Filter from date
- `tanggal_mulai_to` (date): Filter to date

### Create Kontrak
```http
POST /api/v1/kontrak
Authorization: Bearer {token}
Content-Type: application/json

{
    "nomor_kontrak": "KTR/2025/001",
    "nama_penyewa": "PT. ABC Sejahtera",
    "kantor_id": 1,
    "bidang_id": 1,
    "sub_bidang_id": 1,
    "tanggal_mulai": "2025-01-01",
    "tanggal_selesai": "2025-12-31",
    "nilai_kontrak": 1000000000,
    "status": "aktif",
    "deskripsi": "Kontrak sewa ruang kantor"
}
```

### Get Kontrak by ID
```http
GET /api/v1/kontrak/{id}
Authorization: Bearer {token}
```

### Update Kontrak
```http
PUT /api/v1/kontrak/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "status": "selesai",
    "deskripsi": "Kontrak selesai sesuai jadwal"
}
```

### Delete Kontrak
```http
DELETE /api/v1/kontrak/{id}
Authorization: Bearer {token}
```

### Get Kontrak Statistics
```http
GET /api/v1/kontrak/{id}/statistics
Authorization: Bearer {token}
```

---

## 📈 **ANALYTICS API**

### Get Analytics Data
```http
GET /api/v1/analytics
Authorization: Bearer {token}
```

### Get Chart Data
```http
GET /api/v1/analytics/chart-data?type=bar&period=monthly
Authorization: Bearer {token}
```

**Query Parameters:**
- `type` (string): Chart type (bar, pie, line)
- `period` (string): Time period (daily, weekly, monthly, yearly)

---

## 🔄 **BULK OPERATIONS**

### Bulk Delete
```http
POST /api/v1/bulk/delete/{model}
Authorization: Bearer {token}
Content-Type: application/json

{
    "ids": [1, 2, 3, 4, 5]
}
```

### Bulk Export
```http
POST /api/v1/bulk/export/{model}
Authorization: Bearer {token}
Content-Type: application/json

{
    "ids": [1, 2, 3, 4, 5],
    "format": "excel"
}
```

---

## ⚡ **RATE LIMITING**

### Limits
- **General API:** 60 requests per minute per user/IP
- **Create/Update/Delete:** 20 requests per minute per user/IP
- **Statistics:** 30 requests per minute per user/IP
- **Bulk Operations:** 10 requests per minute per user/IP

### Rate Limit Headers
```http
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 59
X-RateLimit-Reset: 1640995200
```

### Rate Limit Exceeded Response
```json
{
    "success": false,
    "message": "Too many requests. Please try again later.",
    "retry_after": 60,
    "timestamp": "2025-01-24T10:30:00.000Z"
}
```

---

## 📝 **ERROR HANDLING**

### Standard Error Response
```json
{
    "success": false,
    "message": "Error description",
    "errors": {
        "field_name": ["Error message"]
    },
    "timestamp": "2025-01-24T10:30:00.000Z"
}
```

### HTTP Status Codes
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `429` - Rate Limit Exceeded
- `500` - Internal Server Error

---

## 🔧 **TESTING**

### Health Check
```http
GET /api/v1/health
```

**Response:**
```json
{
    "status": "ok",
    "timestamp": "2025-01-24T10:30:00.000Z",
    "version": "1.0.0"
}
```

---

## 📋 **SUPPORTED MODELS**

- **Kantor** - `/api/v1/kantor`
- **Gedung** - `/api/v1/gedung`
- **Lantai** - `/api/v1/lantai`
- **Ruang** - `/api/v1/ruang`
- **Kontrak** - `/api/v1/kontrak`
- **Realisasi** - `/api/v1/realisasi`
- **Okupansi** - `/api/v1/okupansi`
- **Bidang** - `/api/v1/bidang`
- **Sub Bidang** - `/api/v1/sub-bidang`

---

## 🚀 **QUICK START**

1. **Login** to get authentication token
2. **Use token** in Authorization header: `Bearer {token}`
3. **Make requests** to desired endpoints
4. **Handle responses** according to documentation

### Example cURL Request
```bash
curl -X GET "http://your-domain.com/api/v1/kantor" \
  -H "Authorization: Bearer your-token-here" \
  -H "Accept: application/json"
```

---

**Last Updated:** 24 January 2025  
**Version:** 1.0.0  
**Status:** Production Ready ✅
