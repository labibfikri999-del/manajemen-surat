
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
    
    // ===== DIREKTUR & STAFF =====
    Route::middleware('role:direktur,staff')->group(function () {
        Route::get('/validasi-dokumen', [PageController::class, 'validasiDokumen'])->name('validasi-dokumen');
        Route::get('/data-master', [PageController::class, 'dataMaster'])->name('data-master');
    });
    
    // ===== STAFF ONLY =====
    Route::middleware('role:staff')->group(function () {
        Route::get('/proses-dokumen', [PageController::class, 'prosesDokumen'])->name('proses-dokumen');
        Route::get('/buat-surat', [DokumenController::class, 'createSurat'])->name('buat-surat');
        Route::post('/buat-surat', [DokumenController::class, 'storeGeneratedSurat'])->name('buat-surat.store');
    });
    
    // ===== INSTANSI & STAFF =====
    Route::middleware('role:instansi,staff')->group(function () {
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
    
    // Arsip Digital API endpoints
    Route::get('/arsip-stats', [ArsipDigitalController::class, 'getStats']);
    Route::get('/arsip-kategori-count', [ArsipDigitalController::class, 'getKategoriCount']);
    Route::get('/arsip-by-kategori/{kategori}', [ArsipDigitalController::class, 'getByKategori']);
    // Note: POST /api/arsip-digital already handled by apiResource above (line 75)
    
    // Route::apiResource('klasifikasi', DataMasterController::class); // Removed to avoid conflict with manual routes
    Route::get('/klasifikasi-list', [DataMasterController::class,'indexKlasifikasi']);
    Route::post('/klasifikasi-store', [DataMasterController::class,'storeKlasifikasi']);
    Route::put('/klasifikasi/{id}', [DataMasterController::class,'updateKlasifikasi']);
    Route::delete('/klasifikasi/{id}', [DataMasterController::class,'destroyKlasifikasi']);

    // Data Master Extras (Stats, Departemen, Pengguna, Tipe Lampiran)
    Route::get('/master/stats', [DataMasterController::class, 'getStats']);
    Route::get('/laporan/stats', [DataMasterController::class, 'getLaporanStats']);
    
    Route::get('/departemen-list', [DataMasterController::class,'indexDepartemen']);
    Route::post('/departemen-store', [DataMasterController::class,'storeDepartemen']);
    Route::put('/departemen/{id}', [DataMasterController::class,'updateDepartemen']);
    Route::delete('/departemen/{id}', [DataMasterController::class,'destroyDepartemen']);

    Route::get('/pengguna-list', [DataMasterController::class,'indexPengguna']);
    Route::post('/pengguna-store', [DataMasterController::class,'storePengguna']);
    Route::put('/pengguna/{id}', [DataMasterController::class,'updatePengguna']);
    Route::delete('/pengguna/{id}', [DataMasterController::class,'destroyPengguna']);

    Route::get('/lampiran-list', [DataMasterController::class,'indexTipeLampiran']);
    Route::post('/lampiran-store', [DataMasterController::class,'storeTipeLampiran']);
    Route::put('/lampiran/{id}', [DataMasterController::class,'updateTipeLampiran']);
    Route::delete('/lampiran/{id}', [DataMasterController::class,'destroyTipeLampiran']);

    Route::get('/export/pdf', [ExportController::class,'exportPdf']);
    Route::get('/export/csv', [ExportController::class,'exportCsv']);

    // Backup Routes
    Route::get('/backup/db', [\App\Http\Controllers\Api\BackupController::class, 'backupDb']);
    Route::get('/backup/files', [\App\Http\Controllers\Api\BackupController::class, 'backupFiles']);
    
    // Dokumen API
    Route::apiResource('dokumen', DokumenController::class);
    Route::get('dokumen/{id}/download', [DokumenController::class, 'download'])->name('dokumen.download');
    Route::post('dokumen/{id}/validasi', [DokumenController::class, 'validasi']);
    Route::post('dokumen/{id}/proses', [DokumenController::class, 'proses']);
    Route::post('dokumen/{id}/proses', [DokumenController::class, 'proses']);
});

Route::get('/debug-db', function() {
    return App\Models\Dokumen::latest()->take(5)->get(['id', 'judul', 'file_name', 'file_path', 'status']);
});

Route::get('/concept-qr', function () {
    return view('concept-qr');
})->name('concept-qr');
