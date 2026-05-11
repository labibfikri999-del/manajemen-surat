<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\Instansi;
use App\Models\Klasifikasi;
use App\Models\TipeLampiran;
use App\Models\User;
use App\Services\SuratStatsService;
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

    public function getLaporanStats(SuratStatsService $suratStats)
    {
        return response()->json($suratStats->laporanStats(auth()->user()));
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
            'nama' => 'required|string|unique:klasifikasi,nama,'.$id,
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
            'kode' => 'required|string|unique:instansis,kode,'.$id,
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
            'username' => 'required|string|unique:users,username,'.$id,
            'email' => 'required|email|unique:users,email,'.$id,
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
            'nama' => 'required|string|unique:tipe_lampirans,nama,'.$id,
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
