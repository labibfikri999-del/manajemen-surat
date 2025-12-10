<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ArsipDigitalController extends Controller
{
    // Get all files
    public function index()
    {
        $user = Auth::user();
        $query = Dokumen::where('is_archived', true);

        // Filter based on user role/instansi
        if ($user && $user->instansi_id) {
            $query->where('instansi_id', $user->instansi_id);
        } else if ($user && !$user->isDirektur() && !$user->isStaff()) {
             // If user has no instansi and is not staff/direktur, they only see their own
            $query->where('user_id', $user->id);
        }
        // Direktur & Staff can typically see all, or we might want to restrict them too? 
        // Based on existing api.php logic, usually they oversee everything or have specific logic.
        // But for safety, let's keep it consistent: Direktur/Staff see ALL, Instansi users restricted.
        
        $data = $query->latest('tanggal_arsip')->get()->map(function($item) {
            $item->file_url = $item->file_path ? Storage::url($item->file_path) : null;
            return $item;
        });
        return response()->json($data);
    }

    // Upload file
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string',
            'kategori_arsip' => 'nullable|string',
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

        $user = Auth::user();
        $arsip = ArsipDigital::create([
            'instansi_id' => $user->instansi_id ?? null,
            'nama_dokumen' => $validated['judul'],
            'kategori' => $validated['kategori_arsip'] ?? null,
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
        $dokumen = Dokumen::findOrFail($id);
        
        // Soft delete or hard delete? Usually hard delete for arsip cleanup if requested
        // But Dokumen might be referenced elsewhere. 
        // For now, let's just set is_archived = false? No, user wants to delete.
        // Let's do delete() which will be consistent.
        
        if ($dokumen->file_path && Storage::disk('public')->exists($dokumen->file_path)) {
            Storage::disk('public')->delete($dokumen->file_path);
        }
        
        $dokumen->delete();
        return response()->json(['message' => 'File deleted successfully']);
    }
    
    
    // Download file
    public function download($id)
    {
        $arsip = Dokumen::findOrFail($id);
        $user = Auth::user();

        // Security check: Ensure user belongs to the same instansi or is staff/direktur
        if ($user && $user->instansi_id && $arsip->instansi_id && $user->instansi_id != $arsip->instansi_id) {
            abort(403, 'Unauthorized access to this document.');
        }

        if (!$arsip->file_path || !Storage::disk('public')->exists($arsip->file_path)) {
            return response()->json(['message' => 'File not found'], 404);
        }
        
        $path = Storage::disk('public')->path($arsip->file_path);
        return response()->download($path, $arsip->file_name ?? 'dokumen.pdf');
    }

    // Get statistics for Arsip Digital page
    public function getStats()
    {
        $query = Dokumen::where('is_archived', true);
        $totalDokumen = $query->count();
        $totalBytes = $query->sum('file_size');
        
        // Format size
        if ($totalBytes >= 1073741824) {
            $ukuranTotal = number_format($totalBytes / 1073741824, 2) . ' GB';
        } elseif ($totalBytes >= 1048576) {
            $ukuranTotal = number_format($totalBytes / 1048576, 2) . ' MB';
        } elseif ($totalBytes >= 1024) {
            $ukuranTotal = number_format($totalBytes / 1024, 2) . ' KB';
        } else {
            $ukuranTotal = $totalBytes . ' B';
        }
        
        // Get last access
        $lastAccess = Dokumen::where('is_archived', true)->latest('updated_at')->first();
        $aksesTerakhir = $lastAccess ? $lastAccess->updated_at->diffForHumans() : 'Belum ada data';
        
        return response()->json([
            'total_dokumen' => $totalDokumen,
            'ukuran_total' => $ukuranTotal,
            'akses_terakhir' => $aksesTerakhir
        ]);
    }

    // Get document count by category
    public function getKategoriCount()
    {
        $counts = [
            'UMUM' => Dokumen::where('is_archived', true)->where('kategori_arsip', 'UMUM')->count(),
            'SDM' => Dokumen::where('is_archived', true)->where('kategori_arsip', 'SDM')->count(),
            'ASSET' => Dokumen::where('is_archived', true)->where('kategori_arsip', 'ASSET')->count(),
            'HUKUM' => Dokumen::where('is_archived', true)->where('kategori_arsip', 'HUKUM')->count(),
            'KEUANGAN' => Dokumen::where('is_archived', true)->where('kategori_arsip', 'KEUANGAN')->count(),
        ];
        
        return response()->json($counts);
    }

    // Get documents by category
    public function getByKategori($kategori)
    {
        $dokumens = Dokumen::where('is_archived', true)
            ->where('kategori_arsip', $kategori)
            ->with(['instansi', 'processor'])
            ->latest('tanggal_arsip')
            ->get();
        
        return response()->json($dokumens);
    }
}
