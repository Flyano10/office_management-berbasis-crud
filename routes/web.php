<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\KantorController;
use App\Http\Controllers\GedungController;
use App\Http\Controllers\KontrakController;
use App\Http\Controllers\RealisasiController;
use App\Http\Controllers\LantaiController;
use App\Http\Controllers\RuangController;
use App\Http\Controllers\OkupansiController;
use App\Http\Controllers\BidangController;
use App\Http\Controllers\SubBidangController;
use App\Http\Controllers\PetaController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\BulkController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventarisController;
use App\Http\Controllers\KategoriInventarisController;
// Route auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// MFA routes
Route::get('/mfa/verify', [AuthController::class, 'showMFAVerify'])->name('mfa.verify');
Route::post('/mfa/verify', [AuthController::class, 'verifyMFA'])->name('mfa.verify.post');

// Route CSRF token refresh untuk mencegah error 419
Route::get('/csrf-token', function () {
    return response()->json(['token' => csrf_token()]);
});


// Route admin dengan prefix /admin
Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    // Route dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Route peta
    Route::get('/peta', [PetaController::class, 'index'])->name('peta.index');
    Route::get('/peta/locations', [PetaController::class, 'getLocations'])->name('peta.locations');
    Route::get('/peta/kontrak-expiring', [PetaController::class, 'getExpiringContracts'])->name('peta.kontrak-expiring');
    
    // Analytics routes
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics/chart-data', [AnalyticsController::class, 'getChartData'])->name('analytics.chart-data');
    
    // Admin Management routes
    // Note: Controller enforces finer RBAC (admin_regional limited to staf in-scope)
    Route::middleware(['role:super_admin,admin_regional,manager_bidang,admin,staf', 'prevent.self.role.change'])->group(function () {
        Route::resource('admin', AdminController::class);
        Route::post('/admin/{id}/toggle-status', [AdminController::class, 'toggleStatus'])->name('admin.toggle-status');
    });

    // Profile routes for staf (self only)
    Route::middleware(['auth:admin'])->group(function () {
        Route::get('/profile', function () {
            $id = Auth::guard('admin')->id();
            return redirect()->route('admin.edit', $id);
        })->name('profile.edit');
        Route::put('/profile', function (\Illuminate\Http\Request $request) {
            $id = Auth::guard('admin')->id();
            return app(\App\Http\Controllers\AdminController::class)->update($request, $id);
        })->name('profile.update');
    });
    
    // MFA Setup routes (requires authentication)
    Route::middleware(['auth:admin'])->group(function () {
        Route::get('/mfa/setup', [AuthController::class, 'showMFASetup'])->name('mfa.setup');
        Route::post('/mfa/enable', [AuthController::class, 'enableMFA'])->name('mfa.enable');
        Route::post('/mfa/disable', [AuthController::class, 'disableMFA'])->name('mfa.disable');
        Route::post('/mfa/regenerate-backup-codes', [AuthController::class, 'regenerateBackupCodes'])->name('mfa.regenerate-backup-codes');
    });
    
    // Audit Log routes (Super Admin and Admin Regional)
    Route::middleware(['role:super_admin,admin_regional,admin'])->group(function () {
        Route::get('/audit-log', [AuditLogController::class, 'index'])->name('audit-log.index');
        Route::get('/audit-log/{id}', [AuditLogController::class, 'show'])->name('audit-log.show');
        Route::get('/audit-log/export', [AuditLogController::class, 'export'])->name('audit-log.export');
        Route::get('/audit-log/statistics', [AuditLogController::class, 'statistics'])->name('audit-log.statistics');
        Route::delete('/audit-log/reset', [AuditLogController::class, 'reset'])->name('audit-log.reset');
    });
    
    // Bulk operations routes
    Route::post('/bulk/delete/{model}', [BulkController::class, 'bulkDelete'])->name('bulk.delete');
    Route::post('/bulk/export/{model}', [BulkController::class, 'bulkExport'])->name('bulk.export');
    
    // Import operations
    // Data Management Center routes
    Route::get('/import', [ImportController::class, 'index'])->name('import.index');
    Route::get('/import/download-template', [ImportController::class, 'downloadTemplate'])->name('import.download-template');
    Route::post('/import/process', [ImportController::class, 'processImport'])->name('import.process');
    
    // Export routes
    Route::get('/data-management/export', [ImportController::class, 'exportData'])->name('data-management.export');
    
    // Backup & Restore routes
    Route::post('/data-management/backup', [ImportController::class, 'createBackup'])->name('data-management.backup');
    Route::get('/data-management/backup/download/{filename}', [ImportController::class, 'downloadBackup'])->name('data-management.download-backup');
    Route::post('/data-management/restore', [ImportController::class, 'restoreBackup'])->name('data-management.restore');
    
    // Report routes
    Route::get('/data-management/report', [ImportController::class, 'generateReport'])->name('data-management.report');
    
    // Search routes
    Route::get('/search/global', [SearchController::class, 'globalSearch'])->name('search.global');
    Route::get('/search/suggestions', [SearchController::class, 'getSuggestions'])->name('search.suggestions');
            



           // CRUD routes
           Route::resource('kantor', KantorController::class);
           Route::resource('gedung', GedungController::class);
           Route::resource('kontrak', KontrakController::class);
           Route::get('/kontrak/export', [KontrakController::class, 'exportExcel'])->name('kontrak.export');
           Route::resource('realisasi', RealisasiController::class);
           Route::resource('lantai', LantaiController::class);
           Route::resource('ruang', RuangController::class);
           Route::resource('okupansi', OkupansiController::class);
           Route::resource('bidang', BidangController::class);
           Route::resource('sub-bidang', SubBidangController::class);
           
            // Inventaris routes
            Route::resource('inventaris', InventarisController::class);
            Route::resource('kategori-inventaris', KategoriInventarisController::class);
});

// Existing routes
// Route::resource('pic-kantor', PicKantorController::class); // Commented out - controller doesn't exist yet
// Route::resource('occupancy-kantor', OccupancyKantorController::class); // Commented out - controller doesn't exist yet
