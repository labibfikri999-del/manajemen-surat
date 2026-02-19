<?php

namespace App\Http\Controllers\SDM;

use App\Http\Controllers\Controller;
use App\Models\SDM\SdmDocument;
use App\Models\SDM\SdmPegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'sdm_pegawai_id' => 'required|exists:sdm_pegawais,id',
            'nama_dokumen' => 'required|string|max:255',
            'kategori' => 'required|in:Identitas,Pendidikan,Legalitas,Kompetensi,Lainnya',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // Max 5MB
            'tgl_kadaluarsa' => 'nullable|date',
            'keterangan' => 'nullable|string',
        ]);

        $pegawai = SdmPegawai::findOrFail($request->sdm_pegawai_id);

        if ($request->hasFile('file')) {
            // Store in 'local' disk (storage/app/private) for security
            $filePath = $request->file('file')->store('sdm-documents/' . $pegawai->id, 'local');

            SdmDocument::create([
                'sdm_pegawai_id' => $pegawai->id,
                'nama_dokumen' => $request->nama_dokumen,
                'kategori' => $request->kategori,
                'file_path' => $filePath,
                'tgl_kadaluarsa' => $request->tgl_kadaluarsa,
                'keterangan' => $request->keterangan,
            ]);

            return back()->with('success', 'Dokumen berhasil diupload.');
        }

        return back()->with('error', 'Gagal mengupload dokumen.');
    }

    public function download($id)
    {
        $document = SdmDocument::findOrFail($id);
        
        // Check if file exists in local storage
        if (!Storage::disk('local')->exists($document->file_path)) {
            abort(404, 'File not found');
        }

        // Return file download response
        return Storage::disk('local')->download($document->file_path, $document->nama_dokumen . '.' . pathinfo($document->file_path, PATHINFO_EXTENSION));
    }

    public function destroy($id)
    {
        $document = SdmDocument::findOrFail($id);

        if (Storage::disk('local')->exists($document->file_path)) {
            Storage::disk('local')->delete($document->file_path);
        }

        $document->delete();

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }
}
