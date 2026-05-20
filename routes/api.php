<?php

use App\Http\Controllers\Api\BackupController;
use App\Http\Controllers\Api\BalasanApiController;
use App\Http\Controllers\Api\ExportController;
use App\Http\Controllers\ArsipDigitalController;
use App\Http\Controllers\DataMasterController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\SuratMasukController;
use App\Models\Dokumen;
use App\Models\User;
use App\Services\SuratStatsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/surat-masuk/count', function () {
        return response()->json([
            'count' => app(SuratStatsService::class)->suratMasukCount(auth()->user()),
        ]);
    })->middleware('role:direktur,staff,instansi');

    Route::get('/surat-keluar/count', function () {
        return response()->json([
            'count' => app(SuratStatsService::class)->suratKeluarCount(auth()->user()),
        ]);
    })->middleware('role:direktur,staff,instansi');

    Route::get('/arsip-digital/count', function () {
        return response()->json([
            'count' => app(SuratStatsService::class)->arsipDigitalCount(auth()->user()),
        ]);
    })->middleware('role:direktur,staff,instansi');

    Route::get('/pengguna-aktif', function () {
        $user = auth()->user();
        $count = ($user->isDirektur() || $user->isStaff())
            ? User::where('is_active', true)->count()
            : 0;

        return response()->json(['count' => $count]);
    })->middleware('role:direktur,staff,instansi');

    Route::middleware('role:direktur,staff,instansi')->group(function () {
        Route::get('/arsip-stats', [ArsipDigitalController::class, 'getStats']);
        Route::get('/arsip-kategori-count', [ArsipDigitalController::class, 'getKategoriCount']);
        Route::get('/arsip-by-kategori/{kategori}', [ArsipDigitalController::class, 'getByKategori']);
        Route::get('/arsip-download-kategori/{kategori}', [ArsipDigitalController::class, 'downloadKategori']);

        Route::get('arsip-digital', [ArsipDigitalController::class, 'index']);
        Route::get('arsip-digital/{id}/download', [ArsipDigitalController::class, 'download']);

        Route::get('/balasan/unread-count', [BalasanApiController::class, 'unreadCount']);
        Route::get('/balasan/unread-list', [BalasanApiController::class, 'unreadList']);
        Route::post('/balasan/mark-read/{id}', [BalasanApiController::class, 'markRead']);

        Route::get('surat-masuk/export/excel', [SuratMasukController::class, 'export']);
        Route::get('surat-masuk/{id}/download', [SuratMasukController::class, 'download']);
        Route::get('surat-masuk/{id}/audits', [SuratMasukController::class, 'audits']);
        Route::apiResource('surat-masuk', SuratMasukController::class);

        Route::get('surat-keluar/generate-nomor', [SuratKeluarController::class, 'generateNomor']);
        Route::get('surat-keluar/export/excel', [SuratKeluarController::class, 'export']);
        Route::get('surat-keluar/{id}/download', [SuratKeluarController::class, 'download']);
        Route::get('surat-keluar/{id}/audits', [SuratKeluarController::class, 'audits']);
        Route::apiResource('surat-keluar', SuratKeluarController::class);

        Route::get('/export/pdf', [ExportController::class, 'exportPdf']);
        Route::get('/export/csv', [ExportController::class, 'exportCsv']);

        Route::get('dokumen/{id}/download', [DokumenController::class, 'download'])->name('dokumen.download');
        Route::get('dokumen/{id}/preview', [DokumenController::class, 'preview'])->name('dokumen.preview');
        Route::get('dokumen/{id}/audits', [DokumenController::class, 'audits'])->name('dokumen.audits');
        Route::get('/dokumen/{id}/download-balasan', [DokumenController::class, 'downloadBalasan']);
        Route::delete('dokumen/{id}/broadcast', [DokumenController::class, 'destroyBroadcast']);
        Route::post('dokumen/{id}/validasi', [DokumenController::class, 'validasi']);
        Route::post('dokumen/{id}/proses', [DokumenController::class, 'proses']);
        Route::post('dokumen/{id}/revisi', [DokumenController::class, 'revisi']);
        Route::apiResource('dokumen', DokumenController::class);

        Route::get('/laporan/stats', [DataMasterController::class, 'getLaporanStats']);

        Route::get('/notifikasi/count', function () {
            $user = auth()->user();

            if ($user->isDirektur()) {
                return response()->json(['count' => Dokumen::where('status', 'pending')->count()]);
            }

            if ($user->isStaff()) {
                return response()->json(['count' => Dokumen::where('status', 'disetujui')->count()]);
            }

            return response()->json(['count' => 0]);
        });
    });

    Route::middleware('role:direktur,staff')->group(function () {
        Route::post('arsip-digital', [ArsipDigitalController::class, 'store']);
        Route::match(['put', 'patch'], 'arsip-digital/{id}', [ArsipDigitalController::class, 'update']);
        Route::delete('arsip-digital/{id}', [ArsipDigitalController::class, 'destroy']);

        Route::post('/arsip-upload', [ArsipDigitalController::class, 'store']);

        Route::get('/klasifikasi-list', [DataMasterController::class, 'indexKlasifikasi']);
        Route::post('/klasifikasi-store', [DataMasterController::class, 'storeKlasifikasi']);
        Route::put('/klasifikasi/{id}', [DataMasterController::class, 'updateKlasifikasi']);
        Route::delete('/klasifikasi/{id}', [DataMasterController::class, 'destroyKlasifikasi']);

        Route::get('/master/stats', [DataMasterController::class, 'getStats']);

        Route::get('/departemen-list', [DataMasterController::class, 'indexDepartemen']);
        Route::post('/departemen-store', [DataMasterController::class, 'storeDepartemen']);
        Route::put('/departemen/{id}', [DataMasterController::class, 'updateDepartemen']);
        Route::delete('/departemen/{id}', [DataMasterController::class, 'destroyDepartemen']);

        Route::get('/pengguna-list', [DataMasterController::class, 'indexPengguna']);
        Route::post('/pengguna-store', [DataMasterController::class, 'storePengguna']);
        Route::put('/pengguna/{id}', [DataMasterController::class, 'updatePengguna']);
        Route::delete('/pengguna/{id}', [DataMasterController::class, 'destroyPengguna']);

        Route::get('/lampiran-list', [DataMasterController::class, 'indexTipeLampiran']);
        Route::post('/lampiran-store', [DataMasterController::class, 'storeTipeLampiran']);
        Route::put('/lampiran/{id}', [DataMasterController::class, 'updateTipeLampiran']);
        Route::delete('/lampiran/{id}', [DataMasterController::class, 'destroyTipeLampiran']);
    });

    Route::middleware('role:direktur')->group(function () {
        Route::get('/backup/db', [BackupController::class, 'backupDb']);
        Route::get('/backup/files', [BackupController::class, 'backupFiles']);
    });
});
