
<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\Kepegawaian\PortalController as KepegawaianPortalController;
use App\Http\Controllers\PageController;
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

// ===== SISTEM KEPEGAWAIAN (PUBLIC) =====
Route::get('/kepegawaian/login', function () {
    if (auth()->check()) {
        $access = auth()->user()->module_access ?? [];
        if (array_intersect(['sdm', 'kepegawaian', 'pegawai'], $access)) {
            return redirect()->route('kepegawaian.dashboard');
        }
        return redirect()->route('dashboard');
    }
    return view('kepegawaian.auth.login');
})->name('kepegawaian.login');
Route::get('/kepegawaian/lupa-password', [KepegawaianPortalController::class, 'forgotPassword'])->name('kepegawaian.forgot-password');
Route::post('/kepegawaian/lupa-password', [KepegawaianPortalController::class, 'requestPasswordReset'])->name('kepegawaian.forgot-password.store');

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
    Route::post('/upload-dokumen', [DokumenController::class, 'store'])->name('upload-dokumen.store');

    // ===== DIREKTUR ONLY =====
    Route::middleware('role:direktur')->group(function () {
        Route::get('/validasi-dokumen', [PageController::class, 'validasiDokumen'])->name('validasi-dokumen');
    });

    // ===== DIREKTUR & STAFF =====
    Route::middleware('role:direktur,staff')->group(function () {
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
    });

    // ===== INSTANSI ONLY =====
    Route::middleware('role:instansi')->group(function () {
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

        // Static Pages / Placeholders
        Route::get('unit', [App\Http\Controllers\Aset\PageController::class, 'unit'])->name('unit.index');
        Route::get('category', [App\Http\Controllers\Aset\PageController::class, 'category'])->name('category.index');
        Route::get('loan', [App\Http\Controllers\Aset\PageController::class, 'loan'])->name('loan.index');
        Route::get('damage', [App\Http\Controllers\Aset\PageController::class, 'damage'])->name('damage.index');
        Route::get('usage', [App\Http\Controllers\Aset\PageController::class, 'usage'])->name('usage.index');
        Route::get('audit', [App\Http\Controllers\Aset\PageController::class, 'audit'])->name('audit.index');
        Route::get('scan-qr', [App\Http\Controllers\Aset\PageController::class, 'scanQr'])->name('scan_qr');
        Route::get('settings', [App\Http\Controllers\Aset\PageController::class, 'settings'])->name('settings');
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

    // Protected Routes Kepegawaian Dokumen
    Route::prefix('kepegawaian')->name('kepegawaian.')->middleware(['auth', 'module.access:sdm,kepegawaian,pegawai'])->group(function () {
        Route::get('/ganti-password', [KepegawaianPortalController::class, 'changePasswordForm'])->name('password.change');
        Route::post('/ganti-password', [KepegawaianPortalController::class, 'updatePassword'])->name('password.update');
        Route::get('/dashboard', [KepegawaianPortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/dokumen/{document}/preview', [KepegawaianPortalController::class, 'previewDocument'])->name('dokumen.preview');
        Route::get('/dokumen/{document}/download', [KepegawaianPortalController::class, 'downloadDocument'])->name('dokumen.download');

        Route::middleware('role:pegawai,staff_kepegawaian,staff')->group(function () {
            Route::get('/upload', [KepegawaianPortalController::class, 'upload'])->name('upload');
            Route::post('/upload', [KepegawaianPortalController::class, 'storeUpload'])->name('upload.store');
        });

        Route::middleware('role:staff_kepegawaian,staff')->group(function () {
            Route::get('/verifikasi', [KepegawaianPortalController::class, 'verifikasi'])->name('verifikasi');
            Route::post('/verifikasi/action', [KepegawaianPortalController::class, 'verifikasiPanelAction'])->name('verifikasi.panel');
            Route::post('/verifikasi/{document}', [KepegawaianPortalController::class, 'verifikasiAction'])->name('verifikasi.action');
            Route::get('/verifikasi-export', [KepegawaianPortalController::class, 'exportVerifikasi'])->name('verifikasi.export');
            Route::get('/akun', [KepegawaianPortalController::class, 'akun'])->name('akun');
            Route::post('/akun/action', [KepegawaianPortalController::class, 'akunAction'])->name('akun.action');
            Route::get('/akun-template', [KepegawaianPortalController::class, 'downloadAccountTemplate'])->name('akun.template');
            Route::get('/reset-password', [KepegawaianPortalController::class, 'resetPassword'])->name('reset-password');
            Route::post('/reset-password/{resetRequest}', [KepegawaianPortalController::class, 'resetPasswordAction'])->name('reset-password.action');
        });

        Route::middleware('role:sekjen,direktur')->group(function () {
            Route::get('/persetujuan', [KepegawaianPortalController::class, 'persetujuan'])->name('persetujuan');
            Route::post('/persetujuan/{document}', [KepegawaianPortalController::class, 'persetujuanAction'])->name('persetujuan.action');
        });
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

        // Budget Management
        Route::resource('budget', \App\Http\Controllers\Keuangan\BudgetController::class)->only(['index', 'update']);

        // Laporan
        Route::get('/laporan/laba-rugi', [App\Http\Controllers\Keuangan\LaporanController::class, 'labaRugi'])->name('laporan.laba-rugi');
    });





    // Legacy routes (Sistem Surat) - Apply Access Control
    Route::middleware('role:direktur,staff,instansi')->group(function () {
        Route::get('/surat-masuk', [PageController::class, 'suratMasuk'])->name('surat-masuk');
        Route::get('/surat-keluar', [PageController::class, 'suratKeluar'])->name('surat-keluar');
    });
});

// Chatbot AI dengan proteksi Spam (Rate Limit: Maksimal 5 pesan per menit per IP). Diletakkan di web agar bisa akses Session.
Route::middleware(['web', 'throttle:5,1'])->group(function () {
    Route::post('/chatbot/send', [\App\Http\Controllers\ChatbotController::class, 'sendMessage']);
});
Route::middleware('web')->post('/chatbot/reset', [\App\Http\Controllers\ChatbotController::class, 'resetSession']);
