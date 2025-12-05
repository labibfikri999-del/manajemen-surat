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

    // Arsip Digital = Dokumen dengan is_archived = true
    Route::get('/arsip-digital', function () {
        $count = Dokumen::where('is_archived', true)->count();
        return response()->json(['count' => $count]);
    });

    // Total Pengguna Aktif
    Route::get('/pengguna-aktif', function () {
        $count = User::count();
        return response()->json(['count' => $count]);
    });

    // Arsip per kategori (count)
    Route::get('/arsip-kategori-count', function () {
        $counts = Dokumen::where('is_archived', true)
            ->selectRaw('kategori_arsip, COUNT(*) as count')
            ->groupBy('kategori_arsip')
            ->pluck('count', 'kategori_arsip');
        
        return response()->json([
            'UMUM' => $counts['UMUM'] ?? 0,
            'SDM' => $counts['SDM'] ?? 0,
            'ASSET' => $counts['ASSET'] ?? 0,
            'HUKUM' => $counts['HUKUM'] ?? 0,
            'KEUANGAN' => $counts['KEUANGAN'] ?? 0,
        ]);
    });

    // Statistik arsip (total, ukuran, akses terakhir)
    Route::get('/arsip-stats', function () {
        $totalArsip = Dokumen::where('is_archived', true)->count();
        
        $totalBytes = Dokumen::where('is_archived', true)->sum('file_size');
        
        // Format ukuran
        if ($totalBytes >= 1073741824) {
            $totalSize = number_format($totalBytes / 1073741824, 2) . ' GB';
        } elseif ($totalBytes >= 1048576) {
            $totalSize = number_format($totalBytes / 1048576, 2) . ' MB';
        } elseif ($totalBytes >= 1024) {
            $totalSize = number_format($totalBytes / 1024, 2) . ' KB';
        } else {
            $totalSize = $totalBytes . ' B';
        }
        
        $lastAccess = Dokumen::where('is_archived', true)
            ->orderBy('tanggal_arsip', 'desc')
            ->first();
        
        $lastAccessText = 'Belum ada data';
        if ($lastAccess && $lastAccess->tanggal_arsip) {
            $lastAccessText = $lastAccess->tanggal_arsip->diffForHumans();
        }
        
        return response()->json([
            'total_dokumen' => $totalArsip,
            'ukuran_total' => $totalSize,
            'akses_terakhir' => $lastAccessText
        ]);
    });

    // Arsip by kategori (list dokumen)
    Route::get('/arsip-by-kategori/{kategori}', function ($kategori) {
        $dokumens = Dokumen::with(['instansi', 'processor'])
            ->where('is_archived', true)
            ->where('kategori_arsip', strtoupper($kategori))
            ->orderBy('tanggal_arsip', 'desc')
            ->get();
        
        return response()->json($dokumens);
    });

    // Upload file ke arsip digital (Staff/Direktur)
    Route::post('/arsip-upload', function (Request $request) {
        $user = auth()->user();
        
        // Check authorization
        if (!$user) {
            return response()->json(['error' => 'Unauthorized', 'message' => 'Silakan login terlebih dahulu'], 401);
        }
        
        // Hanya staff dan direktur yang bisa upload langsung ke arsip
        if (!$user->isStaff() && !$user->isDirektur()) {
            return response()->json(['error' => 'Forbidden', 'message' => 'Hanya Staff dan Direktur yang dapat mengupload'], 403);
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori_arsip' => 'required|in:UMUM,SDM,ASSET,HUKUM,KEUANGAN',
            'deskripsi' => 'nullable|string',
            'file' => 'required|file|max:10240', // Max 10MB
        ]);

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        // Create dummy instansi kode if user tidak punya instansi
        $instansiKode = $user->instansi?->kode ?? 'ARSIP';
        $filePath = $file->store('dokumen/' . $instansiKode . '/arsip', 'public');

        // Create dokumen record - instansi_id bisa null
        $dokumenData = [
            'nomor_dokumen' => Dokumen::generateNomorDokumen($instansiKode),
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'user_id' => $user->id,
            'status' => 'selesai',
            'kategori_arsip' => $request->kategori_arsip,
            'is_archived' => true,
            'tanggal_arsip' => now(),
            'processed_by' => $user->id,
            'tanggal_selesai' => now(),
        ];
        
        // Hanya tambahkan instansi_id jika ada
        if ($user->instansi_id) {
            $dokumenData['instansi_id'] = $user->instansi_id;
        }
        
        $dokumen = Dokumen::create($dokumenData);

        return response()->json([
            'message' => 'File berhasil diupload ke arsip',
            'dokumen' => $dokumen
        ]);
    });
});