<?php

namespace App\Http\Controllers;

use App\Models\Klasifikasi;
use App\Models\Instansi;
use App\Models\Dokumen;
use App\Models\User;
use App\Models\TipeLampiran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DataMasterController extends Controller
{
    // === Stats for Realtime Updates ===
    public function getStats()
    {
        return response()->json([
            'klasifikasi' => Klasifikasi::count(),
            'departemen' => Instansi::count(),
            'pengguna' => User::count(),
            'lampiran' => TipeLampiran::count(),
        ]);
    }

    public function getLaporanStats()
    {
        $user = auth()->user();
        $isInstansi = $user->role === 'instansi';
        $instansiId = $user->instansi_id;

        // Definitions for scope
        $scopeMasuk = function($q) use ($isInstansi, $instansiId) {
            $q->where('jenis_dokumen', 'surat_masuk');
            
            if ($isInstansi) {
                // Instansi only sees their own incoming letters
                $q->where('instansi_id', $instansiId);
            } else {
                 // Global scope for staff/direktur (or maybe they want unrelated ones too?)
                 // Original logic had OR whereNull logic, but let's keep it safe.
                 // Actually, original logic was:
                 // $q->where('jenis_dokumen', 'surat_masuk')
                 //   ->orWhere(function($q2) { ... });
                 
                 // If Instansi: STRICTLY their instansi_id. 
                 // If Staff: GLOBAL.
                 
                 $q->orWhere(function($q2) {
                      $q2->whereNull('jenis_dokumen')->whereNull('instansi_id');
                 });
            }
        };

        $scopeKeluar = function($q) use ($isInstansi, $instansiId) {
            $q->where('jenis_dokumen', 'surat_keluar');
            
            if ($isInstansi) {
                $q->where('instansi_id', $instansiId);
            } else {
                $q->orWhere(function($q2) {
                  $q2->whereNull('jenis_dokumen')->whereNotNull('instansi_id');
                });
            }
        };

        // 1. Counts
        $suratMasuk = Dokumen::where($scopeMasuk)->count();
        $suratKeluar = Dokumen::where($scopeKeluar)->count();
        
        // Unified Arsip Logic (Match with api.php)
        $arsipQuery = Dokumen::where('is_archived', true);
        if ($isInstansi) {
             $arsipQuery->where('instansi_id', $instansiId);
        }
        $arsip = $arsipQuery->count();

        // 2. Monthly Data (Current Year)
        $monthlyMasuk = Dokumen::where($scopeMasuk)
            ->whereYear('created_at', date('Y'))
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();
            
        $monthlyKeluar = Dokumen::where($scopeKeluar)
            ->whereYear('created_at', date('Y'))
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Fill 0 for missing months
        $monthsMasuk = [];
        $monthsKeluar = [];
        for($i=1; $i<=12; $i++) {
            $monthsMasuk[] = $monthlyMasuk[$i] ?? 0;
            $monthsKeluar[] = $monthlyKeluar[$i] ?? 0;
        }

        // 3. Arsip Distribution
        $arsipDistQuery = Dokumen::where('is_archived', true);
        if ($isInstansi) {
             $arsipDistQuery->where('instansi_id', $instansiId);
        }
        $arsipDist = $arsipDistQuery->selectRaw('kategori_arsip as kategori, COUNT(*) as count')
             ->groupBy('kategori_arsip')
             ->get()
             ->map(function($item) {
                 return [
                     'label' => $item->kategori ?? 'Umum',
                     'count' => $item->count
                 ];
             });
        
        // If arsip distribution is empty, fake it for UI demo if no data
        if ($arsipDist->isEmpty() && $arsip > 0) {
             $arsipDist = [['label' => 'Umum', 'count' => $arsip]];
        }

        return response()->json([
            'surat_masuk' => $suratMasuk,
            'surat_keluar' => $suratKeluar,
            'arsip_digital' => $arsip,
            'monthly_masuk' => $monthsMasuk,
            'monthly_keluar' => $monthsKeluar,
            'arsip_distribution' => $arsipDist
        ]);
    }

    // === Klasifikasi ===
    public function indexKlasifikasi()
    {
        return response()->json(Klasifikasi::latest()->get());
    }

    public function storeKlasifikasi(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|unique:klasifikasi',
        ]);

        $klasifikasi = Klasifikasi::create($validated);
        return response()->json($klasifikasi, 201);
    }

    public function updateKlasifikasi(Request $request, $id)
    {
        $klasifikasi = Klasifikasi::findOrFail($id);
        
        $validated = $request->validate([
            'nama' => 'required|string|unique:klasifikasi,nama,' . $id,
        ]);

        $klasifikasi->update($validated);
        return response()->json($klasifikasi);
    }

    public function destroyKlasifikasi($id)
    {
        Klasifikasi::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }

    // === Departemen (Instansi) ===
    public function indexDepartemen()
    {
        return response()->json(Instansi::latest()->get());
    }

    public function storeDepartemen(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|unique:instansis',
            'nama' => 'required|string',
            'alamat' => 'nullable|string',
        ]);

        $instansi = Instansi::create($validated);
        return response()->json($instansi, 201);
    }

    public function updateDepartemen(Request $request, $id)
    {
        $instansi = Instansi::findOrFail($id);
        
        $validated = $request->validate([
            'kode' => 'required|string|unique:instansis,kode,' . $id,
            'nama' => 'required|string',
            'alamat' => 'nullable|string',
        ]);

        $instansi->update($validated);
        return response()->json($instansi);
    }

    public function destroyDepartemen($id)
    {
        Instansi::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }

    // === Pengguna (User) ===
    public function indexPengguna()
    {
        return response()->json(User::with('instansi')->latest()->get());
    }

    public function storePengguna(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:direktur,staff,instansi',
            'instansi_id' => 'required_if:role,instansi|nullable|exists:instansis,id',
            'jabatan' => 'nullable|string',
            'telepon' => 'nullable|string',
            'telegram_chat_id' => 'nullable|string',
        ]);

        $validated['plain_password'] = $validated['password'];
        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = true;

        $user = User::create($validated);
        return response()->json($user->load('instansi'), 201);
    }

    public function updatePengguna(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $rules = [
            'name' => 'required|string',
            'username' => 'required|string|unique:users,username,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:direktur,staff,instansi',
            'instansi_id' => 'required_if:role,instansi|nullable|exists:instansis,id',
            'jabatan' => 'nullable|string',
            'telepon' => 'nullable|string',
            'telegram_chat_id' => 'nullable|string',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'min:6';
        }

        $validated = $request->validate($rules);

        if ($request->filled('password')) {
            $validated['plain_password'] = $validated['password'];
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);
        return response()->json($user->load('instansi'));
    }

    public function destroyPengguna($id)
    {
        User::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }

    // === Tipe Lampiran ===
    public function indexTipeLampiran()
    {
        return response()->json(TipeLampiran::latest()->get());
    }

    public function storeTipeLampiran(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|unique:tipe_lampirans',
            'kode' => 'nullable|string',
        ]);

        $tipe = TipeLampiran::create($validated);
        return response()->json($tipe, 201);
    }

    public function updateTipeLampiran(Request $request, $id)
    {
        $tipe = TipeLampiran::findOrFail($id);
        
        $validated = $request->validate([
            'nama' => 'required|string|unique:tipe_lampirans,nama,' . $id,
            'kode' => 'nullable|string',
        ]);

        $tipe->update($validated);
        return response()->json($tipe);
    }

    public function destroyTipeLampiran($id)
    {
        TipeLampiran::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
