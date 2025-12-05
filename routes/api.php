<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Dokumen;
use App\Models\User;
use App\Models\ArsipDigital;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Statistics API endpoints
Route::middleware('web')->group(function () {
    
    // Surat Masuk = Dokumen dengan status pending, disetujui, review, diproses, selesai
    Route::get('/surat-masuk', function () {
        $count = Dokumen::whereIn('status', ['pending', 'review', 'disetujui', 'diproses', 'selesai'])->count();
        return response()->json(['count' => $count]);
    });

    // Surat Keluar = Dokumen dengan status ditolak atau yang sudah diarsipkan
    Route::get('/surat-keluar', function () {
        $count = Dokumen::where('status', 'ditolak')->count();
        return response()->json(['count' => $count]);
    });

    // Arsip Digital = Data dari tabel arsip_digital
    Route::get('/arsip-digital', function () {
        $count = ArsipDigital::count();
        return response()->json(['count' => $count]);
    });

    // Total Pengguna Aktif
    Route::get('/pengguna-aktif', function () {
        $count = User::count();
        return response()->json(['count' => $count]);
    });
});
