x# Deployment Checklist - PLN Kantor Management

##  Pre-Deployment Checklist

### 1. Environment Configuration
- [ ] Set `APP_ENV=production` di `.env`
- [ ] Set `APP_DEBUG=false` di `.env`
- [ ] Set `APP_URL` ke URL production (contoh: `https://pln-kantor.plniconplus.com`)
- [ ] Set `CACHE_DRIVER=redis` atau `file` (sesuai server)
- [ ] Set `SESSION_DRIVER=redis` atau `database` (sesuai server)
- [ ] Set `QUEUE_CONNECTION=database` atau `redis` (sesuai server)

### 2. Database
- [ ] Backup database production
- [ ] Run migrations: `php artisan migrate --force`
- [ ] Run seeders jika diperlukan: `php artisan db:seed --class=AdminSeeder`
- [ ] Verify database indexes sudah ada

### 3. Cache & Optimization
- [ ] Clear all cache: `php artisan cache:clear`
- [ ] Clear config cache: `php artisan config:clear`
- [ ] Clear route cache: `php artisan route:clear`
- [ ] Clear view cache: `php artisan view:clear`
- [ ] Build cache untuk production:
  ```bash
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  ```

### 4. Security
- [ ] Generate new `APP_KEY` jika belum ada: `php artisan key:generate`
- [ ] Verify `.env` tidak commit ke git (ada di `.gitignore`)
- [ ] Verify `CORS_ALLOWED_ORIGINS` di `.env` sesuai domain production
- [ ] Verify `TRUSTED_PROXIES` di `.env` jika pakai load balancer/proxy
- [ ] Test MFA login flow
- [ ] Test rate limiting

### 5. File Permissions
- [ ] Set permissions untuk storage: `chmod -R 775 storage bootstrap/cache`
- [ ] Set ownership: `chown -R www-data:www-data storage bootstrap/cache`
- [ ] Verify `storage/logs` writable

### 6. Server Configuration
- [ ] Verify PHP version >= 8.1
- [ ] Verify required PHP extensions: `mbstring`, `openssl`, `pdo`, `tokenizer`, `xml`, `json`, `bcmath`
- [ ] Verify web server (Apache/Nginx) configured correctly
- [ ] Verify SSL certificate valid
- [ ] Set up log rotation untuk Laravel logs

### 7. Testing
- [ ] Test public homepage
- [ ] Test public map page (marker muncul)
- [ ] Test public directory
- [ ] Test admin login
- [ ] Test MFA setup & login
- [ ] Test CRUD operations (Kantor, Gedung, Lantai, Ruang)
- [ ] Test API endpoints (`/api/kantor`, `/api/inventaris/{id}`, dll)
- [ ] Test export Excel functionality
- [ ] Test search & filter

### 8. Performance
- [ ] Enable OPcache di PHP
- [ ] Verify cache working (dashboard stats cached)
- [ ] Test page load time (< 2 detik untuk first load, < 1 detik untuk cached)
- [ ] Monitor database query count (should be < 20 per page)

### 9. Monitoring
- [ ] Set up error logging (Sentry, Loggly, atau Laravel log)
- [ ] Set up uptime monitoring
- [ ] Set up database backup schedule
- [ ] Set up log rotation

## ✅ Post-Deployment Checklist

### 1. Immediate Checks
- [ ] Verify website accessible
- [ ] Verify HTTPS working
- [ ] Verify no 500 errors in logs
- [ ] Verify cache working
- [ ] Test critical user flows

### 2. Monitoring (First 24 Hours)
- [ ] Monitor error logs
- [ ] Monitor server resources (CPU, Memory, Disk)
- [ ] Monitor database performance
- [ ] Monitor API response times
- [ ] Check for any CSP violations
- [ ] Check for any rate limiting issues

### 3. User Communication
- [ ] Notify users about deployment
- [ ] Provide support contact if issues arise
- [ ] Document any breaking changes

## 🔧 Production Commands

```bash
# Clear all cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Build production cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Clear dashboard cache (if needed)
php artisan dashboard:clear-cache

# Check application status
php artisan about

# Run migrations
php artisan migrate --force

# Seed database (if needed)
php artisan db:seed --class=AdminSeeder
```

## ⚠️ Important Notes

1. **CSP (Content Security Policy)**: 
   - Sudah otomatis detect environment
   - Production akan menggunakan `connect-src 'self'` (tidak allow localhost)
   - Development akan allow localhost untuk debugging

2. **Cache**:
   - Dashboard stats: 5 menit
   - API responses: 5 menit
   - Filter options: 10 menit
   - Cache akan auto-clear saat ada perubahan data

3. **Rate Limiting**:
   - Public API: 40 requests per minute per IP
   - Sudah configured di `RouteServiceProvider`

4. **MFA**:
   - Sudah implemented dan tested
   - Backup codes harus disimpan dengan aman

5. **Performance**:
   - Query sudah dioptimasi
   - Eager loading sudah diimplementasi
   - Pagination sudah diimplementasi untuk data besar

## 🚨 Rollback Plan

Jika ada masalah setelah deployment:

1. **Quick Rollback**:
   ```bash
   # Restore previous version
   git checkout <previous-commit>
   php artisan migrate:rollback --step=1  # Jika ada migration baru
   php artisan cache:clear
   php artisan config:clear
   ```

2. **Database Rollback**:
   ```bash
   # Restore database backup
   mysql -u user -p database_name < backup.sql
   ```

3. **Clear Cache**:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```

## 📞 Support

Jika ada masalah:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Check web server logs
3. Check browser console untuk errors
4. Check database connection
5. Verify `.env` configuration

