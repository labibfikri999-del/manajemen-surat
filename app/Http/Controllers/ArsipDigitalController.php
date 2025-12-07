<?php

namespace App\Http\Controllers;

use App\Models\ArsipDigital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArsipDigitalController extends Controller
{
    // Get all files
    public function index()
    {
        $data = ArsipDigital::latest()->get()->map(function($item) {
            $item->file_url = $item->file_path ? Storage::url($item->file_path) : null;
            return $item;
        });
        return response()->json($data);
    }

    // Upload file
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_dokumen' => 'required|string',
            'kategori' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,png,jpg,jpeg,doc,docx,xlsx,xls,ppt,pptx|max:10240',
        ]);

        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('arsip-digital', $filename, 'public');
        
        // Get file extension
        $extension = strtoupper($file->getClientOriginalExtension());
        
        // Format file size
        $bytes = $file->getSize();
        if ($bytes >= 1048576) {
            $ukuran = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $ukuran = number_format($bytes / 1024, 2) . ' KB';
        } else {
            $ukuran = $bytes . ' B';
        }

        $user = auth()->user();
        $arsip = ArsipDigital::create([
            'instansi_id' => $user->instansi_id ?? null,
            'nama_dokumen' => $validated['nama_dokumen'],
            'kategori' => $validated['kategori'] ?? null,
            'deskripsi' => $validated['deskripsi'] ?? null,
            'nama_file' => $file->getClientOriginalName(),
            'file_path' => $path,
            'tipe' => $extension,
            'ukuran' => $ukuran,
            'tanggal_upload' => now(),
        ]);

        $arsip->file_url = Storage::url($arsip->file_path);

        return response()->json($arsip, 201);
    }

    // Update arsip
    public function update(Request $request, $id)
    {
        $arsip = ArsipDigital::findOrFail($id);
        
        $validated = $request->validate([
            'nama_dokumen' => 'required|string',
            'kategori' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,png,jpg,jpeg,doc,docx,xlsx,xls,ppt,pptx|max:10240',
        ]);

        $updateData = [
            'nama_dokumen' => $validated['nama_dokumen'],
            'kategori' => $validated['kategori'] ?? null,
            'deskripsi' => $validated['deskripsi'] ?? null,
        ];

        // Handle file upload
        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($arsip->file_path && Storage::disk('public')->exists($arsip->file_path)) {
                Storage::disk('public')->delete($arsip->file_path);
            }
            
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('arsip-digital', $filename, 'public');
            
            $extension = strtoupper($file->getClientOriginalExtension());
            $bytes = $file->getSize();
            if ($bytes >= 1048576) {
                $ukuran = number_format($bytes / 1048576, 2) . ' MB';
            } elseif ($bytes >= 1024) {
                $ukuran = number_format($bytes / 1024, 2) . ' KB';
            } else {
                $ukuran = $bytes . ' B';
            }

            $updateData['nama_file'] = $file->getClientOriginalName();
            $updateData['file_path'] = $path;
            $updateData['tipe'] = $extension;
            $updateData['ukuran'] = $ukuran;
        }

        $arsip->update($updateData);
        $arsip->file_url = $arsip->file_path ? Storage::url($arsip->file_path) : null;

        return response()->json($arsip);
    }

    // Delete file
    public function destroy($id)
    {
        $arsip = ArsipDigital::findOrFail($id);
        
        // Delete file from storage
        if ($arsip->file_path && Storage::disk('public')->exists($arsip->file_path)) {
            Storage::disk('public')->delete($arsip->file_path);
        }
        
        $arsip->delete();
        return response()->json(['message' => 'File deleted successfully']);
    }
    
    // Download file
    public function download($id)
    {
        $arsip = ArsipDigital::findOrFail($id);
        
        if (!$arsip->file_path || !Storage::disk('public')->exists($arsip->file_path)) {
            return response()->json(['message' => 'File not found'], 404);
        }
        
        $path = Storage::disk('public')->path($arsip->file_path);
        return response()->download($path, $arsip->nama_file);
    }
}
