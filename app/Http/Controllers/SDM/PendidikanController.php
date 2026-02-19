<?php

namespace App\Http\Controllers\SDM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SDM\SdmPendidikan;
use App\Models\SDM\SdmPegawai;
use Illuminate\Support\Facades\Storage;

class PendidikanController extends Controller
{
    public function index(Request $request)
    {
        $query = SdmPendidikan::with('pegawai');

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('pegawai', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhere('institusi', 'like', "%{$search}%")
              ->orWhere('jurusan', 'like', "%{$search}%");
        }

        if ($request->has('jenjang') && $request->jenjang != '') {
            $query->where('jenjang', $request->jenjang);
        }

        $pendidikans = $query->orderBy('tahun_lulus', 'desc')->paginate(10);

        return view('sdm.pendidikan.index', compact('pendidikans'));
    }

    public function create()
    {
        $pegawais = SdmPegawai::orderBy('name')->get();
        return view('sdm.pendidikan.create', compact('pegawais'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sdm_pegawai_id' => 'required|exists:sdm_pegawais,id',
            'jenjang' => 'required',
            'institusi' => 'required',
            'jurusan' => 'nullable',
            'tahun_lulus' => 'required|numeric|digits:4',
            'dokumen' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('dokumen')) {
            $path = $request->file('dokumen')->store('dokumen_pendidikan', 'local');
            $data['dokumen_path'] = $path;
        }

        SdmPendidikan::create($data);

        return redirect()->route('sdm.pendidikan.index')->with('success', 'Riwayat pendidikan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pendidikan = SdmPendidikan::findOrFail($id);
        $pegawais = SdmPegawai::orderBy('name')->get();
        return view('sdm.pendidikan.edit', compact('pendidikan', 'pegawais'));
    }

    public function update(Request $request, $id)
    {
        $pendidikan = SdmPendidikan::findOrFail($id);

        $request->validate([
            'sdm_pegawai_id' => 'required|exists:sdm_pegawais,id',
            'jenjang' => 'required',
            'institusi' => 'required',
            'jurusan' => 'nullable',
            'tahun_lulus' => 'required|numeric|digits:4',
            'dokumen' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('dokumen')) {
            $path = $request->file('dokumen')->store('dokumen_pendidikan', 'local');
            $data['dokumen_path'] = $path;
        }

        $pendidikan->update($data);

        return redirect()->route('sdm.pendidikan.index')->with('success', 'Riwayat pendidikan berhasil diperbarui.');
    }

    public function download($id)
    {
        $pendidikan = SdmPendidikan::findOrFail($id);
        
        // Cek disk 'local'
        if (Storage::disk('local')->exists($pendidikan->dokumen_path)) {
             return Storage::disk('local')->download($pendidikan->dokumen_path);
        }
        
        // Fallback cek disk 'public'
        if (Storage::disk('public')->exists('dokumen_pendidikan/' . $pendidikan->dokumen_path)) {
             return Storage::disk('public')->download('dokumen_pendidikan/' . $pendidikan->dokumen_path);
        } elseif (Storage::disk('public')->exists($pendidikan->dokumen_path)) {
             return Storage::disk('public')->download($pendidikan->dokumen_path);
        }

        abort(404, 'File not found');
    }

    public function destroy($id)
    {
        $pendidikan = SdmPendidikan::findOrFail($id);
        $pendidikan->delete();

        return redirect()->route('sdm.pendidikan.index')->with('success', 'Riwayat pendidikan berhasil dihapus.');
    }
}
