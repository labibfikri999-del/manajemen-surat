
<?php
use App\Http\Controllers\Api\ExportController;
use App\Http\Controllers\ArsipDigitalController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataMasterController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\SuratMasukController;
use Illuminate\Support\Facades\Route;

// Auth routes (Guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ===== SISTEM ASET (PUBLIC) =====
Route::get('/aset/login', function () {
    if (auth()->check()) {
        if (in_array('aset', auth()->user()->module_access ?? [])) {
            return redirect()->route('aset.dashboard');
        }
        return redirect()->route('dashboard');
    }
    return view('aset.auth.login');
})->name('aset.login');

// ===== SISTEM SDM (PUBLIC) =====
Route::get('/sdm/login', function () {
    if (auth()->check()) {
        if (in_array('sdm', auth()->user()->module_access ?? [])) {
            return redirect()->route('sdm.dashboard');
        }
        return redirect()->route('dashboard');
    }
    return view('sdm.auth.login');
})->name('sdm.login');

// ===== SISTEM KEUANGAN (PUBLIC) =====
Route::get('/keuangan/login', function () {
    if (auth()->check()) {
        if (in_array('keuangan', auth()->user()->module_access ?? [])) {
            return redirect()->route('keuangan.dashboard');
        }
        return redirect()->route('dashboard');
    }
    return view('keuangan.auth.login');
})->name('keuangan.login');



// Root route (Portal)
Route::get('/', function () {
    // Jika sudah login, tetap arahkan ke dashboard
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    // Jika belum, tampilkan portal landing page
    return view('welcome');
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
        Route::post('/buat-surat/download-word', [DokumenController::class, 'downloadWord'])->name('buat-surat.download-word');
    });

    // ===== INSTANSI & STAFF =====
    Route::middleware('role:instansi,staff')->group(function () {
        Route::get('/upload-dokumen', [PageController::class, 'uploadDokumen'])->name('upload-dokumen');
        Route::get('/tracking-dokumen', [PageController::class, 'trackingDokumen'])->name('tracking-dokumen');
    });

    // ===== DIREKTUR & STAFF =====
    Route::middleware('role:direktur,staff')->group(function () {
        Route::get('/arsip-dokumen', [PageController::class, 'arsipDigital'])->name('arsip-digital');
    });

    // ===== ALL ROLES (hasil validasi bisa dilihat semua) =====
    Route::get('/hasil-validasi', [PageController::class, 'hasilValidasi'])->name('hasil-validasi');
    Route::get('/laporan', [PageController::class, 'laporan'])->name('laporan');

    // Protected Routes Aset
    Route::prefix('aset')->name('aset.')->middleware(['auth', 'module.access:aset'])->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Aset\DashboardController::class, 'index'])->name('dashboard');
        
        // Inventaris
        Route::resource('inventory', App\Http\Controllers\Aset\InventoryController::class);
        
        // Mutasi
        Route::resource('mutation', App\Http\Controllers\Aset\MutationController::class);

        // Maintenance
        Route::resource('maintenance', App\Http\Controllers\Aset\MaintenanceController::class);

        // Laporan
        Route::get('report', [App\Http\Controllers\Aset\ReportController::class, 'index'])->name('report.index');
    });

    // Protected Routes SDM
    Route::prefix('sdm')->name('sdm.')->middleware(['auth', 'module.access:sdm'])->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\SDM\DashboardController::class, 'index'])->name('dashboard');
        
        // Gaji / Payroll
        Route::resource('payroll', App\Http\Controllers\SDM\PayrollController::class);
        
        // Pegawai
        Route::get('/pegawai/export', [App\Http\Controllers\SDM\PegawaiController::class, 'export'])->name('pegawai.export');
        Route::get('/pegawai/template', [App\Http\Controllers\SDM\PegawaiController::class, 'downloadTemplate'])->name('pegawai.template');
        Route::post('/pegawai/import', [App\Http\Controllers\SDM\PegawaiController::class, 'import'])->name('pegawai.import');
        Route::get('/pegawai', [App\Http\Controllers\SDM\PegawaiController::class, 'index'])->name('pegawai.index');
        Route::get('/pegawai/create', [App\Http\Controllers\SDM\PegawaiController::class, 'create'])->name('pegawai.create');
        Route::post('/pegawai', [App\Http\Controllers\SDM\PegawaiController::class, 'store'])->name('pegawai.store');
        Route::get('/pegawai/{id}', [App\Http\Controllers\SDM\PegawaiController::class, 'show'])->name('pegawai.show');
        Route::get('/pegawai/{id}/edit', [App\Http\Controllers\SDM\PegawaiController::class, 'edit'])->name('pegawai.edit');
        Route::put('/pegawai/{id}', [App\Http\Controllers\SDM\PegawaiController::class, 'update'])->name('pegawai.update');
        Route::delete('/pegawai/{id}', [App\Http\Controllers\SDM\PegawaiController::class, 'destroy'])->name('pegawai.destroy');
        
        // Pendidikan
        Route::get('pendidikan/{id}/download', [App\Http\Controllers\SDM\PendidikanController::class, 'download'])->name('pendidikan.download');
        Route::resource('pendidikan', App\Http\Controllers\SDM\PendidikanController::class);

        // Jabatan
        Route::resource('master-jabatan', App\Http\Controllers\SDM\MasterJabatanController::class);
        Route::resource('riwayat-jabatan', App\Http\Controllers\SDM\RiwayatJabatanController::class);
        Route::get('monitoring-pangkat', [App\Http\Controllers\SDM\RiwayatPangkatController::class, 'monitoring'])->name('monitoring-pangkat.index');
        Route::resource('riwayat-pangkat', App\Http\Controllers\SDM\RiwayatPangkatController::class);

        // Keluarga
        Route::get('keluarga/{id}/download', [App\Http\Controllers\SDM\KeluargaController::class, 'download'])->name('keluarga.download');
        Route::resource('keluarga', App\Http\Controllers\SDM\KeluargaController::class);

        // Dokumen
        Route::post('/dokumen', [App\Http\Controllers\SDM\DocumentController::class, 'store'])->name('dokumen.store');
        Route::get('/dokumen/{id}/download', [App\Http\Controllers\SDM\DocumentController::class, 'download'])->name('dokumen.download');
        Route::delete('/dokumen/{id}', [App\Http\Controllers\SDM\DocumentController::class, 'destroy'])->name('dokumen.destroy');
        
        // Laporan
        Route::get('/laporan', [App\Http\Controllers\SDM\LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/data-karyawan', [App\Http\Controllers\SDM\LaporanController::class, 'dataKaryawan'])->name('laporan.data-karyawan');
        Route::get('/laporan/rekap-jabatan', [App\Http\Controllers\SDM\LaporanController::class, 'rekapJabatan'])->name('laporan.rekap-jabatan');
        Route::get('/laporan/rekap-golongan', [App\Http\Controllers\SDM\LaporanController::class, 'rekapGolongan'])->name('laporan.rekap-golongan');
        Route::get('/laporan/masa-kerja', [App\Http\Controllers\SDM\LaporanController::class, 'masaKerja'])->name('laporan.masa-kerja');
        Route::get('/laporan/pendidikan', [App\Http\Controllers\SDM\LaporanController::class, 'pendidikan'])->name('laporan.pendidikan');
        Route::get('/laporan/keluarga', [App\Http\Controllers\SDM\LaporanController::class, 'keluarga'])->name('laporan.keluarga');

        // Pengaturan
        Route::get('/settings', [App\Http\Controllers\SDM\SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings', [App\Http\Controllers\SDM\SettingsController::class, 'update'])->name('settings.update');
        Route::post('/settings/user', [App\Http\Controllers\SDM\SettingsController::class, 'storeUser'])->name('settings.user.store');
        Route::put('/settings/user/{id}', [App\Http\Controllers\SDM\SettingsController::class, 'updateUser'])->name('settings.user.update');
        Route::delete('/settings/user/{id}', [App\Http\Controllers\SDM\SettingsController::class, 'destroyUser'])->name('settings.user.destroy');
    });

    // Protected Routes Keuangan
    Route::prefix('keuangan')->name('keuangan.')->middleware(['auth', 'module.access:keuangan'])->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Keuangan\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/download-pdf', [App\Http\Controllers\Keuangan\DashboardController::class, 'downloadPdf'])->name('dashboard.pdf');
        
        // Laporan Routes (Moved from DashboardController to LaporanController)
        Route::get('/neraca', [App\Http\Controllers\Keuangan\LaporanController::class, 'neraca'])->name('neraca');
        Route::get('/arus-kas', [App\Http\Controllers\Keuangan\LaporanController::class, 'arusKas'])->name('arus-kas');
        Route::get('/catatan', [App\Http\Controllers\Keuangan\LaporanController::class, 'catatan'])->name('catatan');
        
        // Catatan CRUD
        Route::post('/catatan', [App\Http\Controllers\Keuangan\LaporanController::class, 'storeCatatan'])->name('catatan.store');
        Route::put('/catatan/{id}', [App\Http\Controllers\Keuangan\LaporanController::class, 'updateCatatan'])->name('catatan.update');
        Route::delete('/catatan/{id}', [App\Http\Controllers\Keuangan\LaporanController::class, 'destroyCatatan'])->name('catatan.destroy');

        Route::get('/pemasukan', [App\Http\Controllers\Keuangan\TransactionController::class, 'pemasukan'])->name('pemasukan');
        Route::get('/pengeluaran', [App\Http\Controllers\Keuangan\TransactionController::class, 'pengeluaran'])->name('pengeluaran');
        
        Route::get('/transaksi/catat', [App\Http\Controllers\Keuangan\TransactionController::class, 'create'])->name('transaksi.create');
        Route::post('/transaksi/store', [App\Http\Controllers\Keuangan\TransactionController::class, 'store'])->name('transaksi.store');
        Route::get('/transaksi/{id}/edit', [App\Http\Controllers\Keuangan\TransactionController::class, 'edit'])->name('transaksi.edit');
        Route::put('/transaksi/{id}', [App\Http\Controllers\Keuangan\TransactionController::class, 'update'])->name('transaksi.update');
        Route::delete('/transaksi/{id}', [App\Http\Controllers\Keuangan\TransactionController::class, 'destroy'])->name('transaksi.destroy');

        // Klaim Asuransi
        Route::resource('klaim', App\Http\Controllers\Keuangan\ClaimController::class);

        // Laporan
        Route::get('/laporan/laba-rugi', [App\Http\Controllers\Keuangan\LaporanController::class, 'labaRugi'])->name('laporan.laba-rugi');
    });





    // Legacy routes (Sistem Surat) - Apply Access Control
    Route::middleware(['module.access:surat'])->group(function () {
        Route::get('/surat-masuk', [PageController::class, 'suratMasuk'])->name('surat-masuk');
        Route::get('/surat-keluar', [PageController::class, 'suratKeluar'])->name('surat-keluar');
    });
});

// PUBLIC API (agar fetch dari halaman bisa langsung JSON tanpa redirect login)
Route::prefix('api')->middleware('auth')->group(function () {
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
    Route::get('/arsip-download-kategori/{kategori}', [ArsipDigitalController::class, 'downloadKategori']);
    // Note: POST /api/arsip-digital already handled by apiResource above (line 75)

    // Modifikasi: Hapus rute duplikat dan komentar yang tidak perlu
    // Route::apiResource('klasifikasi', DataMasterController::class); // Hapus baris ini jika benar-benar tidak digunakan

    Route::get('/klasifikasi-list', [DataMasterController::class, 'indexKlasifikasi']);
    Route::post('/klasifikasi-store', [DataMasterController::class, 'storeKlasifikasi']);
    Route::put('/klasifikasi/{id}', [DataMasterController::class, 'updateKlasifikasi']);
    Route::delete('/klasifikasi/{id}', [DataMasterController::class, 'destroyKlasifikasi']);

    // Data Master Extras (Stats, Departemen, Pengguna, Tipe Lampiran)
    Route::get('/master/stats', [DataMasterController::class, 'getStats']);
    Route::get('/laporan/stats', [DataMasterController::class, 'getLaporanStats']);

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

    Route::get('/export/pdf', [ExportController::class, 'exportPdf']);
    Route::get('/export/csv', [ExportController::class, 'exportCsv']);

    // Backup Routes
    Route::get('/backup/db', [\App\Http\Controllers\Api\BackupController::class, 'backupDb']);
    Route::get('/backup/files', [\App\Http\Controllers\Api\BackupController::class, 'backupFiles']);

    // Dokumen API
    Route::apiResource('dokumen', DokumenController::class);
    Route::get('dokumen/{id}/download', [DokumenController::class, 'download'])->name('dokumen.download');
    Route::get('dokumen/{id}/preview', [DokumenController::class, 'preview'])->name('dokumen.preview');
    Route::post('dokumen/{id}/validasi', [DokumenController::class, 'validasi']);
    Route::post('dokumen/{id}/proses', [DokumenController::class, 'proses']);
});
