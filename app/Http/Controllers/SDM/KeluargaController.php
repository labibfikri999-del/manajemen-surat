<?php

namespace App\Http\Controllers\SDM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SDM\SdmKeluarga;
use App\Models\SDM\SdmPegawai;
use Carbon\Carbon;

class KeluargaController extends Controller
{
    public function index(Request $request)
    {
        $query = SdmKeluarga::with('pegawai');

        // Filter: Search (Name of family member or employee)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhereHas('pegawai', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }

        // Filter: Hubungan
        if ($request->has('hubungan') && $request->hubungan != '') {
            $query->where('hubungan', $request->hubungan);
        }

        $keluargas = $query->orderBy('sdm_pegawai_id')->orderBy('hubungan')->paginate(10);
        $total = SdmKeluarga::count();

        return view('sdm.keluarga.index', compact('keluargas', 'total'));
    }

    public function create()
    {
        $pegawais = SdmPegawai::orderBy('name')->get();
        return view('sdm.keluarga.create', compact('pegawais'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sdm_pegawai_id' => 'required|exists:sdm_pegawais,id',
            'nama' => 'required|string|max:255',
            'hubungan' => 'required|string',
            'tgl_lahir' => 'required|date',
            'pekerjaan' => 'nullable|string|max:255',
            'dokumen' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'keterangan' => 'nullable|string',
        ]);

        $data = $request->all();

        if ($request->hasFile('dokumen')) {
            $path = $request->file('dokumen')->store('public/dokumen_keluarga');
            $data['dokumen_path'] = str_replace('public/', '', $path);
        }

        SdmKeluarga::create($data);

        return redirect()->route('sdm.keluarga.index')->with('success', 'Data keluarga berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $keluarga = SdmKeluarga::findOrFail($id);
        $pegawais = SdmPegawai::orderBy('name')->get();
        return view('sdm.keluarga.edit', compact('keluarga', 'pegawais'));
    }

    public function update(Request $request, $id)
    {
        $keluarga = SdmKeluarga::findOrFail($id);

        $request->validate([
            'sdm_pegawai_id' => 'required|exists:sdm_pegawais,id',
            'nama' => 'required|string|max:255',
            'hubungan' => 'required|string',
            'tgl_lahir' => 'required|date',
            'pekerjaan' => 'nullable|string|max:255',
            'dokumen' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'keterangan' => 'nullable|string',
        ]);

        $data = $request->all();

        if ($request->hasFile('dokumen')) {
            $path = $request->file('dokumen')->store('public/dokumen_keluarga');
            $data['dokumen_path'] = str_replace('public/', '', $path);
        }

        $keluarga->update($data);

        return redirect()->route('sdm.keluarga.index')->with('success', 'Data keluarga berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $keluarga = SdmKeluarga::findOrFail($id);
        $keluarga->delete();

        return redirect()->route('sdm.keluarga.index')->with('success', 'Data keluarga berhasil dihapus.');
    }
}
