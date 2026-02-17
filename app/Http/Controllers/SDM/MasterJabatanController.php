<?php

namespace App\Http\Controllers\SDM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SDM\SdmMasterJabatan;

class MasterJabatanController extends Controller
{
    public function index(Request $request)
    {
        $query = SdmMasterJabatan::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nama_jabatan', 'like', "%{$search}%");
        }

        $jabatans = $query->orderBy('urutan')->orderBy('nama_jabatan')->paginate(10);

        return view('sdm.master-jabatan.index', compact('jabatans'));
    }

    public function create()
    {
        return view('sdm.master-jabatan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jabatan' => 'required|unique:sdm_master_jabatans,nama_jabatan',
            'urutan' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        SdmMasterJabatan::create([
            'nama_jabatan' => $request->nama_jabatan,
            'urutan' => $request->urutan ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('sdm.master-jabatan.index')->with('success', 'Master jabatan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $jabatan = SdmMasterJabatan::findOrFail($id);
        return view('sdm.master-jabatan.edit', compact('jabatan'));
    }

    public function update(Request $request, $id)
    {
        $jabatan = SdmMasterJabatan::findOrFail($id);

        $request->validate([
            'nama_jabatan' => 'required|unique:sdm_master_jabatans,nama_jabatan,' . $id,
            'urutan' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $jabatan->update([
            'nama_jabatan' => $request->nama_jabatan,
            'urutan' => $request->urutan ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('sdm.master-jabatan.index')->with('success', 'Master jabatan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jabatan = SdmMasterJabatan::findOrFail($id);
        $jabatan->delete();

        return redirect()->route('sdm.master-jabatan.index')->with('success', 'Master jabatan berhasil dihapus.');
    }
}
