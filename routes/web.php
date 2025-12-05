
<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SinglePageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\ArsipDigitalController;
use App\Http\Controllers\DataMasterController;
use App\Http\Controllers\Api\SuratApiController;
use App\Http\Controllers\Api\KlasifikasiApiController;
use App\Http\Controllers\Api\ExportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DokumenController;

// Auth routes (Guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Redirect root to login or dashboard
Route::get('/', function(){ 
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// PROTECTED routes (perlu login)
Route::middleware('auth')->group(function () {
    
    // Dashboard - semua role bisa akses
    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
    
    // ===== DIREKTUR ONLY =====
    Route::middleware('role:direktur')->group(function () {
        Route::get('/validasi-dokumen', [PageController::class, 'validasiDokumen'])->name('validasi-dokumen');
        Route::get('/data-master', [PageController::class, 'dataMaster'])->name('data-master');
    });
    
    // ===== STAFF ONLY =====
    Route::middleware('role:staff')->group(function () {
        Route::get('/proses-dokumen', [PageController::class, 'prosesDokumen'])->name('proses-dokumen');
    });
    
    // ===== INSTANSI ONLY =====
    Route::middleware('role:instansi')->group(function () {
        Route::get('/upload-dokumen', [PageController::class, 'uploadDokumen'])->name('upload-dokumen');
        Route::get('/tracking-dokumen', [PageController::class, 'trackingDokumen'])->name('tracking-dokumen');
    });
    
    // ===== DIREKTUR & STAFF =====
    Route::middleware('role:direktur,staff')->group(function () {
        Route::get('/arsip-digital', [PageController::class, 'arsipDigital'])->name('arsip-digital');
    });
    
    // ===== ALL ROLES (hasil validasi bisa dilihat semua) =====
    Route::get('/hasil-validasi', [PageController::class, 'hasilValidasi'])->name('hasil-validasi');
    Route::get('/laporan', [PageController::class, 'laporan'])->name('laporan');
    
    // Legacy routes (untuk kompatibilitas)
    Route::get('/surat-masuk', [PageController::class, 'suratMasuk'])->name('surat-masuk');
    Route::get('/surat-keluar', [PageController::class, 'suratKeluar'])->name('surat-keluar');
});

// PUBLIC API (agar fetch dari halaman bisa langsung JSON tanpa redirect login)
Route::prefix('api')->middleware('auth')->group(function(){
    Route::apiResource('surat-masuk', SuratMasukController::class);
    Route::get('surat-masuk/{id}/download', [SuratMasukController::class, 'download']);
    Route::apiResource('surat-keluar', SuratKeluarController::class);
    Route::get('surat-keluar/{id}/download', [SuratKeluarController::class, 'download']);
    Route::apiResource('arsip-digital', ArsipDigitalController::class);
    Route::get('arsip-digital/{id}/download', [ArsipDigitalController::class, 'download']);
    Route::apiResource('klasifikasi', DataMasterController::class);
    Route::get('/klasifikasi-list', [DataMasterController::class,'indexKlasifikasi']);
    Route::post('/klasifikasi-store', [DataMasterController::class,'storeKlasifikasi']);
    Route::put('/klasifikasi/{id}', [DataMasterController::class,'updateKlasifikasi']);
    Route::delete('/klasifikasi/{id}', [DataMasterController::class,'destroyKlasifikasi']);
    Route::get('/export/pdf', [ExportController::class,'exportPdf']);
    Route::get('/export/csv', [ExportController::class,'exportCsv']);
    
    // Dokumen API
    Route::apiResource('dokumen', DokumenController::class);
    Route::post('dokumen/{id}/validasi', [DokumenController::class, 'validasi']);
    Route::post('dokumen/{id}/proses', [DokumenController::class, 'proses']);
});
