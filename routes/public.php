<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;

/*
|--------------------------------------------------------------------------
| Public Website Routes - PLN Icon Plus
|--------------------------------------------------------------------------
|
| Routes untuk halaman publik PLN Icon Plus (4 halaman)
| Home | Peta | Directory | Kontak
|
*/

// Homepage - Landing Page
Route::get('/', [PublicController::class, 'home'])->name('public.home');

// Peta - Map Interaktif
Route::get('/peta', [PublicController::class, 'peta'])->name('public.peta');

// Bantuan / Help
Route::get('/bantuan', [PublicController::class, 'help'])->name('public.help');

// Profil Perusahaan
Route::get('/profil', [PublicController::class, 'about'])->name('public.about');

// Scan Barcode Inventaris
Route::get('/scan-barcode', [PublicController::class, 'scanBarcode'])->name('public.scan-barcode');
Route::post('/scan-barcode/result', [PublicController::class, 'scanResult'])->name('public.scan-result');

// Detail Inventaris Public (Info Terbatas)
Route::get('/inventaris/{id}', [PublicController::class, 'inventarisDetail'])->name('public.inventaris.detail');


Route::prefix('api')
    ->middleware(['throttle:public-api', 'public.api'])
    ->group(function () {
// API untuk data kantor (untuk peta)
        Route::get('/kantor', [PublicController::class, 'getKantorData'])->name('public.api.kantor');

// API untuk data inventaris berdasarkan kantor
        Route::get('/inventaris/{kantorId}', [PublicController::class, 'getInventarisData'])->name('public.api.inventaris');

// API untuk data kontrak berdasarkan kantor
        Route::get('/kontrak/{kantorId}', [PublicController::class, 'getKontrakData'])->name('public.api.kontrak');

// API untuk kontrak yang mendekati jatuh tempo berdasarkan kantor (6/3/1 bulan)
        Route::get('/kontrak-expiring/{kantorId}', [PublicController::class, 'getExpiringContracts'])
    ->name('public.api.kontrak-expiring');

// API untuk laporan inventaris berdasarkan kantor
        Route::get('/laporan-inventaris/{kantorId}', [PublicController::class, 'getLaporanInventarisData'])->name('public.api.laporan-inventaris');

// API untuk data pegawai berdasarkan kantor (dari okupansi)
        Route::get('/pegawai/{kantorId}', [PublicController::class, 'getEmployeeData'])->name('public.api.pegawai');
    });
