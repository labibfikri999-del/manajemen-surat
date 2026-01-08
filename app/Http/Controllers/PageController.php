<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\Instansi;
use App\Models\User;

class PageController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $data = [
            'user' => $user,
        ];

        // Statistik berdasarkan role
        if ($user->isDirektur()) {
            $data['total_dokumen'] = Dokumen::count();
            $data['pending'] = Dokumen::where('status', 'pending')->count();
            $data['disetujui'] = Dokumen::where('status', 'disetujui')->count();
            $data['ditolak'] = Dokumen::where('status', 'ditolak')->count();
            $data['instansis'] = Instansi::withCount('dokumens')->get();
        } elseif ($user->isStaff()) {
            $data['total_dokumen'] = Dokumen::whereIn('status', ['disetujui', 'diproses', 'selesai'])->count();
            $data['disetujui'] = Dokumen::where('status', 'disetujui')->count();
            $data['diproses'] = Dokumen::where('status', 'diproses')->count();
            $data['selesai'] = Dokumen::where('status', 'selesai')->count();
        } else {
            // User Instansi
            $data['total_dokumen'] = Dokumen::where('instansi_id', $user->instansi_id)->count();
            $data['pending'] = Dokumen::where('instansi_id', $user->instansi_id)->where('status', 'pending')->count();
            $data['disetujui'] = Dokumen::where('instansi_id', $user->instansi_id)->where('status', 'disetujui')->count();
            $data['ditolak'] = Dokumen::where('instansi_id', $user->instansi_id)->where('status', 'ditolak')->count();
        }

        return view('dashboard', $data);
    }

    // ===== Halaman Direktur =====
    public function validasiDokumen()
    {
        $dokumens = Dokumen::with(['instansi', 'user'])
            ->whereIn('status', ['pending', 'review'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('validasi-dokumen', compact('dokumens'));
    }

    public function dataMaster()
    {
        $instansis = Instansi::all();
        $users = User::with('instansi')->get();

        return view('data-master', compact('instansis', 'users'));
    }

    // ===== Halaman Staff =====
    public function prosesDokumen()
    {
        $dokumens = Dokumen::with(['instansi', 'user', 'validator'])
            ->whereIn('status', ['disetujui', 'diproses'])
            ->orderBy('tanggal_validasi', 'desc')
            ->get();

        return view('proses-dokumen', compact('dokumens'));
    }

    // ===== Halaman Instansi =====
    public function uploadDokumen()
    {
        return view('upload-dokumen');
    }

    public function trackingDokumen()
    {
        $user = auth()->user();
        $dokumens = Dokumen::with(['validator', 'processor'])
            ->where('instansi_id', $user->instansi_id)
            ->whereHas('user', function ($q) {
                // Hanya tampilkan dokumen yang diupload oleh Instansi sendiri
                // (Mencegah dokumen dari Staff/Direktur muncul disini)
                $q->where('role', 'instansi');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tracking-dokumen', compact('dokumens'));
    }

    // ===== Halaman Shared =====
    public function hasilValidasi()
    {
        $user = auth()->user();

        $query = Dokumen::with(['instansi', 'user', 'validator', 'processor'])
            ->whereIn('status', ['disetujui', 'ditolak', 'diproses', 'selesai'])
            ->whereNotNull('validated_by'); // Hanya tampilkan dokumen yang SUDAH DIVALIDASI

        if ($user->isInstansi()) {
            $query->where('instansi_id', $user->instansi_id)
                ->whereHas('user', function ($q) {
                    $q->where('role', 'instansi'); // Only show own uploads
                });
        }

        $dokumens = $query->orderBy('tanggal_validasi', 'desc')->get();

        return view('hasil-validasi', compact('dokumens'));
    }

    public function laporan()
    {
        return view('laporan');
    }

    public function arsipDigital()
    {
        // Hitung metrik arsip digital dari tabel Dokumen (Unified)
        $query = Dokumen::where('is_archived', true);

        $totalArsip = $query->count();
        $totalBytes = $query->sum('file_size') ?? 0;

        // Format ukuran ke string human readable
        $formatSize = function ($bytes) {
            if ($bytes >= 1073741824) {
                return number_format($bytes / 1073741824, 2).' GB';
            }
            if ($bytes >= 1048576) {
                return number_format($bytes / 1048576, 2).' MB';
            }
            if ($bytes >= 1024) {
                return number_format($bytes / 1024, 2).' KB';
            }

            return $bytes.' B';
        };

        $totalSize = $formatSize($totalBytes);

        // Akses terakhir (gunakan created_at atau updated_at dari Dokumen)
        $lastAccess = $query->latest('created_at')->value('created_at');

        return view('arsip-digital', [
            'totalArsip' => $totalArsip,
            'totalSize' => $totalSize,
            'lastAccess' => $lastAccess,
        ]);
    }

    // Legacy routes
    public function suratMasuk()
    {
        $user = auth()->user();
        $dokumenDigital = collect([]);
        $surat_masuks = collect([]);

        if ($user->isInstansi()) {
            // Get documents sent by Staff/Direktur TO this instansi
            $dokumenDigital = Dokumen::with(['user'])
                ->where('instansi_id', $user->instansi_id)
                ->whereHas('user', function ($q) {
                    $q->whereIn('role', ['staff', 'direktur', 'sekjen']);
                })
                ->orderBy('created_at', 'desc')
                ->get();

            // Get Manual Surat Masuk
            $surat_masuks = \App\Models\SuratMasuk::with(['klasifikasi'])
                ->where('instansi_id', $user->instansi_id)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Staff/Admin sees all?
            $surat_masuks = \App\Models\SuratMasuk::with(['klasifikasi', 'instansi'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('surat-masuk', compact('dokumenDigital', 'surat_masuks'));
    }

    public function suratKeluar()
    {
        return view('surat-keluar');
    }
}
