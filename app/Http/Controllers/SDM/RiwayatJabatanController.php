<?php

namespace App\Http\Controllers\SDM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SDM\SdmRiwayatJabatan;
use App\Models\SDM\SdmPegawai;
use App\Models\SDM\SdmMasterJabatan;

class RiwayatJabatanController extends Controller
{
    public function index(Request $request)
    {
        $query = SdmRiwayatJabatan::with(['pegawai', 'masterJabatan']);

        // Filter: Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('pegawai', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('masterJabatan', function($q) use ($search) {
                $q->where('nama_jabatan', 'like', "%{$search}%");
            });
        }

        // Filter: Status
        if ($request->has('status') && $request->status != '') {
            if ($request->status == 'active') {
                $query->where('is_active', true);
            } elseif ($request->status == 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filter: Kategori
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori', $request->kategori);
        }

        $riwayats = $query->orderBy('tgl_mulai', 'desc')->paginate(10);
        
        $totalAktif = SdmRiwayatJabatan::where('is_active', true)->count();

        return view('sdm.riwayat-jabatan.index', compact('riwayats', 'totalAktif'));
    }

    public function create()
    {
        $pegawais = SdmPegawai::orderBy('name')->get();
        $jabatans = SdmMasterJabatan::where('is_active', true)->orderBy('nama_jabatan')->get();
        return view('sdm.riwayat-jabatan.create', compact('pegawais', 'jabatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sdm_pegawai_id' => 'required|exists:sdm_pegawais,id',
            'sdm_master_jabatan_id' => 'required|exists:sdm_master_jabatans,id',
            'kategori' => 'required',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'nullable|date|after_or_equal:tgl_mulai',
            'is_active' => 'boolean',
            'dokumen' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('dokumen')) {
            $path = $request->file('dokumen')->store('public/dokumen_jabatan');
            $data['dokumen_path'] = str_replace('public/', '', $path);
        }

        // If this is set to active, deactivate other active positions for this employee if necessary? 
        // Usage rule depends on organization policy (can have multiple positions?).
        // For now, let's assume one active position per category or generally.
        // Let's not auto-deactivate for now unless requested.

        SdmRiwayatJabatan::create($data);

        return redirect()->route('sdm.riwayat-jabatan.index')->with('success', 'Riwayat jabatan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $riwayat = SdmRiwayatJabatan::findOrFail($id);
        $pegawais = SdmPegawai::orderBy('name')->get();
        $jabatans = SdmMasterJabatan::where('is_active', true)->orderBy('nama_jabatan')->get();
        return view('sdm.riwayat-jabatan.edit', compact('riwayat', 'pegawais', 'jabatans'));
    }

    public function update(Request $request, $id)
    {
        $riwayat = SdmRiwayatJabatan::findOrFail($id);

        $request->validate([
            'sdm_pegawai_id' => 'required|exists:sdm_pegawais,id',
            'sdm_master_jabatan_id' => 'required|exists:sdm_master_jabatans,id',
            'kategori' => 'required',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'nullable|date|after_or_equal:tgl_mulai',
            'is_active' => 'boolean',
            'dokumen' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('dokumen')) {
            $path = $request->file('dokumen')->store('public/dokumen_jabatan');
            $data['dokumen_path'] = str_replace('public/', '', $path);
        }

        $riwayat->update($data);

        return redirect()->route('sdm.riwayat-jabatan.index')->with('success', 'Riwayat jabatan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $riwayat = SdmRiwayatJabatan::findOrFail($id);
        $riwayat->delete();

        return redirect()->route('sdm.riwayat-jabatan.index')->with('success', 'Riwayat jabatan berhasil dihapus.');
    }
}
