<?php

use Illuminate\Support\Facades\Route;
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


// Route admin dengan prefix /admin
Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    // Route dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Route peta
    Route::get('/peta', [PetaController::class, 'index'])->name('peta.index');
    Route::get('/peta/locations', [PetaController::class, 'getLocations'])->name('peta.locations');
    
    // Analytics routes
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics/chart-data', [AnalyticsController::class, 'getChartData'])->name('analytics.chart-data');
    
    // Admin Management routes (Super Admin only)
    Route::middleware(['role:super_admin', 'prevent.self.role.change'])->group(function () {
        Route::resource('admin', AdminController::class);
        Route::post('/admin/{id}/toggle-status', [AdminController::class, 'toggleStatus'])->name('admin.toggle-status');
    });
    
    // Audit Log routes (Super Admin only)
    Route::middleware(['role:super_admin'])->group(function () {
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
    Route::get('/import', [ImportController::class, 'index'])->name('import.index');
    Route::get('/import/download-template', [ImportController::class, 'downloadTemplate'])->name('import.download-template');
    Route::post('/import/process', [ImportController::class, 'processImport'])->name('import.process');
    
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
