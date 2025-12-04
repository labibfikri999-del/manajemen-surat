
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

// Auth routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// PUBLIC routes (tidak perlu login)
Route::get('/', function(){ return redirect()->route('dashboard'); });
Route::get('/test-vite', function(){ return view('test_vite'); });

// Page routes
Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
Route::get('/surat-masuk', [PageController::class, 'suratMasuk'])->name('surat-masuk');
Route::get('/surat-keluar', [PageController::class, 'suratKeluar'])->name('surat-keluar');
Route::get('/arsip-digital', [PageController::class, 'arsipDigital'])->name('arsip-digital');
Route::get('/laporan', [PageController::class, 'laporan'])->name('laporan');
Route::get('/data-master', [PageController::class, 'dataMaster'])->name('data-master');

// PUBLIC API (agar fetch dari halaman bisa langsung JSON tanpa redirect login)
Route::prefix('api')->group(function(){
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
});

// PROTECTED routes (halaman web saja jika perlu dibatasi)
Route::middleware('auth')->group(function () {
    // Tambahkan route yang perlu login di sini bila diperlukan
});
