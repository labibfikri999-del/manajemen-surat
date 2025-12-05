<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('tracking-dokumen', compact('dokumens'));
    }

    // ===== Halaman Shared =====
    public function hasilValidasi()
    {
        $user = auth()->user();
        
        $query = Dokumen::with(['instansi', 'user', 'validator', 'processor'])
            ->whereIn('status', ['disetujui', 'ditolak', 'diproses', 'selesai']);
        
        if ($user->isInstansi()) {
            $query->where('instansi_id', $user->instansi_id);
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
        return view('arsip-digital');
    }

    // Legacy routes
    public function suratMasuk()
    {
        return view('surat-masuk');
    }

    public function suratKeluar()
    {
        return view('surat-keluar');
    }
}
