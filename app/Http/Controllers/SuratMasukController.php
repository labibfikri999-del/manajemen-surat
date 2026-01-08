<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SuratMasukController extends Controller
{
    // Get all surat masuk
    public function index()
    {
        $user = Auth::user();
        $query = SuratMasuk::query();

        // Role-based filtering
        if ($user->role === 'instansi') {
            $query->where('instansi_id', $user->instansi_id);
        }
        // staff & direktur see all data

        $data = $query->latest()->get()->map(function ($item) {
            $item->file_url = $item->file ? Storage::url($item->file) : null;

            return $item;
        });

        return response()->json($data);
    }

    // Store new surat masuk
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_surat' => 'required|string|unique:surat_masuk',
            'tanggal_diterima' => 'required|date',
            'pengirim' => 'required|string',
            'perihal' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,png,jpg,jpeg,doc,docx,xlsx|max:10240',
        ]);

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('surat-masuk', $filename, 'public');
            $validated['file'] = $path;
        }

        // Add instansi_id from authenticated user
        if ($request->user()->instansi_id) {
            $validated['instansi_id'] = $request->user()->instansi_id;
        }

        $surat = SuratMasuk::create($validated);
        $surat->file_url = $surat->file ? Storage::url($surat->file) : null;

        return response()->json($surat, 201);
    }

    // Update surat masuk
    public function update(Request $request, $id)
    {
        $surat = SuratMasuk::findOrFail($id);

        $validated = $request->validate([
            'nomor_surat' => 'required|string|unique:surat_masuk,nomor_surat,'.$id,
            'tanggal_diterima' => 'required|date',
            'pengirim' => 'required|string',
            'perihal' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,png,jpg,jpeg,doc,docx,xlsx|max:10240',
        ]);

        // Handle file upload
        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($surat->file && Storage::disk('public')->exists($surat->file)) {
                Storage::disk('public')->delete($surat->file);
            }

            $file = $request->file('file');
            $filename = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('surat-masuk', $filename, 'public');
            $validated['file'] = $path;
        }

        $surat->update($validated);
        $surat->file_url = $surat->file ? Storage::url($surat->file) : null;

        return response()->json($surat);
    }

    // Delete surat masuk
    public function destroy($id)
    {
        $surat = SuratMasuk::findOrFail($id);

        // Delete file if exists
        if ($surat->file && Storage::disk('public')->exists($surat->file)) {
            Storage::disk('public')->delete($surat->file);
        }

        $surat->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }

    // Download file
    public function download($id)
    {
        $surat = SuratMasuk::findOrFail($id);

        if (! $surat->file || ! Storage::disk('public')->exists($surat->file)) {
            return response()->json(['message' => 'File not found'], 404);
        }

        $path = Storage::disk('public')->path($surat->file);

        return response()->download($path);
    }
}
