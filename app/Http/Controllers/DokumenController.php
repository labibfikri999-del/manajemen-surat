<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\Instansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DokumenController extends Controller
{
    /**
     * Display a listing of dokumen.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Dokumen::with(['instansi', 'user', 'validator', 'processor']);

        // Filter berdasarkan role
        if ($user->isInstansi()) {
            // User instansi hanya lihat dokumen dari instansi mereka
            $query->where('instansi_id', $user->instansi_id);
        } elseif ($user->isStaff()) {
            // Staff lihat dokumen yang sudah divalidasi (disetujui)
            $query->whereIn('status', ['disetujui', 'diproses', 'selesai']);
        }
        // Direktur bisa lihat semua

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by instansi (untuk direktur)
        if ($request->has('instansi_id') && $request->instansi_id) {
            $query->where('instansi_id', $request->instansi_id);
        }

        $dokumens = $query->orderBy('created_at', 'desc')->get();

        return response()->json($dokumens);
    }

    /**
     * Store a newly created dokumen.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        // Hanya user instansi yang bisa upload
        if (!$user->isInstansi()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file' => 'required|file|max:10240', // Max 10MB
        ]);

        // Upload file
        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $filePath = $file->store('dokumen/' . $user->instansi->kode, 'public');

        // Generate nomor dokumen
        $nomorDokumen = Dokumen::generateNomorDokumen($user->instansi->kode);

        $dokumen = Dokumen::create([
            'nomor_dokumen' => $nomorDokumen,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'instansi_id' => $user->instansi_id,
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Dokumen berhasil diupload',
            'dokumen' => $dokumen->load(['instansi', 'user'])
        ], 201);
    }

    /**
     * Display the specified dokumen.
     */
    public function show(string $id)
    {
        $dokumen = Dokumen::with(['instansi', 'user', 'validator', 'processor'])->findOrFail($id);

        return response()->json($dokumen);
    }

    /**
     * Update the specified dokumen.
     */
    public function update(Request $request, string $id)
    {
        $dokumen = Dokumen::findOrFail($id);
        $user = auth()->user();

        // Hanya user yang upload bisa edit, dan hanya jika status masih pending
        if ($dokumen->user_id !== $user->id || $dokumen->status !== 'pending') {
            return response()->json(['error' => 'Tidak dapat mengedit dokumen ini'], 403);
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $dokumen->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
        ]);

        return response()->json([
            'message' => 'Dokumen berhasil diupdate',
            'dokumen' => $dokumen->load(['instansi', 'user'])
        ]);
    }

    /**
     * Remove the specified dokumen.
     */
    public function destroy(string $id)
    {
        $dokumen = Dokumen::findOrFail($id);
        $user = auth()->user();

        // Hanya user yang upload bisa hapus, dan hanya jika status masih pending
        if ($dokumen->user_id !== $user->id || $dokumen->status !== 'pending') {
            return response()->json(['error' => 'Tidak dapat menghapus dokumen ini'], 403);
        }

        // Hapus file
        if (Storage::disk('public')->exists($dokumen->file_path)) {
            Storage::disk('public')->delete($dokumen->file_path);
        }

        $dokumen->delete();

        return response()->json(['message' => 'Dokumen berhasil dihapus']);
    }

    /**
     * Validasi dokumen (Direktur only)
     */
    public function validasi(Request $request, string $id)
    {
        $user = auth()->user();

        if (!$user->isDirektur()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'catatan' => 'nullable|string',
        ]);

        $dokumen = Dokumen::findOrFail($id);

        $dokumen->update([
            'status' => $request->status,
            'catatan_validasi' => $request->catatan,
            'validated_by' => $user->id,
            'tanggal_validasi' => now(),
        ]);

        return response()->json([
            'message' => 'Dokumen berhasil divalidasi',
            'dokumen' => $dokumen->load(['instansi', 'user', 'validator'])
        ]);
    }

    /**
     * Proses dokumen (Staff only)
     */
    public function proses(Request $request, string $id)
    {
        $user = auth()->user();

        if (!$user->isStaff()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|in:diproses,selesai',
            'catatan' => 'nullable|string',
        ]);

        $dokumen = Dokumen::findOrFail($id);

        // Hanya dokumen yang sudah disetujui yang bisa diproses
        if (!in_array($dokumen->status, ['disetujui', 'diproses'])) {
            return response()->json(['error' => 'Dokumen belum divalidasi direktur'], 400);
        }

        $updateData = [
            'status' => $request->status,
            'catatan_proses' => $request->catatan,
            'processed_by' => $user->id,
        ];

        if ($request->status === 'diproses') {
            $updateData['tanggal_proses'] = now();
        } elseif ($request->status === 'selesai') {
            $updateData['tanggal_selesai'] = now();
        }

        $dokumen->update($updateData);

        return response()->json([
            'message' => 'Status dokumen berhasil diupdate',
            'dokumen' => $dokumen->load(['instansi', 'user', 'validator', 'processor'])
        ]);
    }

    /**
     * Download dokumen
     */
    public function download(string $id)
    {
        $dokumen = Dokumen::findOrFail($id);

        if (!Storage::disk('public')->exists($dokumen->file_path)) {
            return response()->json(['error' => 'File tidak ditemukan'], 404);
        }

        return Storage::disk('public')->download($dokumen->file_path, $dokumen->file_name);
    }
}
