<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuratKeluarController extends Controller
{
    // Get all surat keluar
    public function index()
    {
        $data = SuratKeluar::latest()->get()->map(function($item) {
            $item->file_url = $item->file ? Storage::url($item->file) : null;
            return $item;
        });
        return response()->json($data);
    }

    // Store new surat keluar
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_surat' => 'required|string',
            'tanggal_keluar' => 'required|date',
            'tujuan' => 'required|string',
            'perihal' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,png,jpg,jpeg,doc,docx,xlsx|max:10240',
        ]);

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('surat-keluar', $filename, 'public');
            $validated['file'] = $path;
        }

        $validated['status'] = 'Draft';
        $surat = SuratKeluar::create($validated);
        $surat->file_url = $surat->file ? Storage::url($surat->file) : null;

        return response()->json($surat, 201);
    }

    // Update surat keluar
    public function update(Request $request, $id)
    {
        $surat = SuratKeluar::findOrFail($id);
        
        $validated = $request->validate([
            'nomor_surat' => 'required|string',
            'tanggal_keluar' => 'required|date',
            'tujuan' => 'required|string',
            'perihal' => 'required|string',
            'status' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,png,jpg,jpeg,doc,docx,xlsx|max:10240',
        ]);

        // Handle file upload
        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($surat->file && Storage::disk('public')->exists($surat->file)) {
                Storage::disk('public')->delete($surat->file);
            }
            
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('surat-keluar', $filename, 'public');
            $validated['file'] = $path;
        }

        $surat->update($validated);
        $surat->file_url = $surat->file ? Storage::url($surat->file) : null;

        return response()->json($surat);
    }

    // Delete surat keluar
    public function destroy($id)
    {
        $surat = SuratKeluar::findOrFail($id);
        
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
        $surat = SuratKeluar::findOrFail($id);
        
        if (!$surat->file || !Storage::disk('public')->exists($surat->file)) {
            return response()->json(['message' => 'File not found'], 404);
        }
        
        $path = Storage::disk('public')->path($surat->file);
        return response()->download($path);
    }
}
