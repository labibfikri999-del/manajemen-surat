<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\Instansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\DokumenMasukMail;

class DokumenController extends Controller
{
    /**
     * Download file balasan dokumen
     */
    public function downloadBalasan($id)
    {
        $dokumen = Dokumen::findOrFail($id);
        if (!$dokumen->balasan_file || !Storage::disk('public')->exists($dokumen->balasan_file)) {
            return response()->json(['error' => 'File balasan tidak ditemukan'], 404);
        }
        $downloadName = 'balasan_' . ($dokumen->file_name ?? 'dokumen') . '.' . pathinfo($dokumen->balasan_file, PATHINFO_EXTENSION);
        return response()->download(
            Storage::disk('public')->path($dokumen->balasan_file),
            $downloadName,
            ['Content-Type' => Storage::disk('public')->mimeType($dokumen->balasan_file)]
        );
    }
    /**
     * Display a listing of dokumen.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
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
        // Filter by instansi (untuk direktur)
        if ($request->has('instansi_id') && $request->instansi_id) {
            $query->where('instansi_id', $request->instansi_id);
        }

        // Sorting Custom: AMAT SEGERA > SEGERA > BIASA > Created At
        $dokumens = $query->orderByRaw("
            CASE 
                WHEN prioritas = 'AMAT SEGERA' THEN 1 
                WHEN prioritas = 'SEGERA' THEN 2 
                WHEN prioritas = 'BIASA' THEN 3 
                ELSE 4 
            END
        ")->orderBy('created_at', 'desc')->get();

        return response()->json($dokumens);
    }

    /**
     * Store a newly created dokumen.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Hanya user instansi dan staff yang bisa upload (Direktur tidak boleh)
        if (!$user->isInstansi() && !$user->isStaff()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'jenis' => 'required|string|in:surat_masuk,surat_keluar,proposal,laporan,sk,kontrak,lainnya',
            'deskripsi' => 'nullable|string',
            'tujuan_instansi_id' => 'nullable|exists:instansis,id',
            'email_eksternal' => 'nullable|email', // Validasi email eksternal
            'file' => 'required|file|mimes:doc,docx,pdf|max:10240', // Word/PDF, Max 10MB
        ]);

        // Upload file
        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        // Instansi Kode Logic
        $instansiKode = 'YAYASAN';
        $targetInstansiId = null;

        // Jika user adalah instansi, gunakan kodenya
        if ($user->isInstansi()) {
            $instansiKode = $user->instansi->kode;
            $targetInstansiId = $user->instansi_id;
        } 
        // Jika Staff memilih tujuan instansi
        elseif ($request->filled('tujuan_instansi_id') && $user->isStaff()) {
            $targetInstansi = \App\Models\Instansi::find($request->tujuan_instansi_id);
            if ($targetInstansi) {
                $instansiKode = $targetInstansi->kode;
                $targetInstansiId = $targetInstansi->id;
            }
        }
        
        $filePath = $file->store('dokumen/' . $instansiKode, 'public');

        // Generate nomor dokumen
        $nomorDokumen = Dokumen::generateNomorDokumen($instansiKode);

        $createData = [
            'nomor_dokumen' => $nomorDokumen,
            'judul' => $request->judul,
            'jenis_dokumen' => $request->jenis,
            'deskripsi' => $request->deskripsi,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'user_id' => $user->id,
            'instansi_id' => $targetInstansiId,
            'status' => 'pending',
        ];

        // Jika Staff mengirim ke Instansi atau Email Eksternal
        if ($user->isStaff() && ($targetInstansiId || $request->filled('email_eksternal'))) {
            $createData['status'] = 'disetujui'; // Bypass validation logic
            // Set balasan_file ONLY if targetInstansiId is set (internal flow), otherwise it's just an external send
            if ($targetInstansiId) {
                $createData['balasan_file'] = $filePath;
            }
        }

        $dokumen = Dokumen::create($createData);

        // Jika Staff mengirim ke Instansi, buat notifikasi balasan
        if ($user->isStaff() && $targetInstansiId) {
             $targetUsers = \App\Models\User::where('instansi_id', $targetInstansiId)->get();
             foreach ($targetUsers as $targetUser) {
                 \DB::table('balasan_read_status')->insert([
                    'dokumen_id' => $dokumen->id,
                    'user_id' => $targetUser->id,
                    'terbaca' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                 ]);
             }
             
             // KIRIM EMAIL KE INSTANSI
             if (isset($targetInstansi) && $targetInstansi->email) {
                 try {
                     Mail::to($targetInstansi->email)->send(new DokumenMasukMail($dokumen));
                 } catch (\Exception $e) {
                     \Log::error('Gagal kirim email dokumen masuk: ' . $e->getMessage());
                 }
             }
        }
        
        // KIRIM EMAIL EKSTERNAL (Jika Staff input email manual)
        if ($user->isStaff() && $request->filled('email_eksternal')) {
             try {
                 Mail::to($request->email_eksternal)->send(new DokumenMasukMail($dokumen));
             } catch (\Exception $e) {
                 \Log::error('Gagal kirim email eksternal: ' . $e->getMessage());
             }
        }

        // Auto-create Surat Keluar ONLY for instansi users
        if ($user->isInstansi()) {
             \App\Models\SuratKeluar::create([
                'instansi_id' => $user->instansi_id,
                'nomor_surat' => $nomorDokumen,
                'tanggal_keluar' => now(),
                'tujuan' => 'Direktur YARSI NTB',
                'perihal' => $request->judul,
                'file' => $filePath,
                'status' => 'Terkirim',
            ]);
        }

        return response()->json([
            'message' => 'Dokumen berhasil diupload dan surat keluar telah dibuat',
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
        $user = Auth::user();

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
        $user = Auth::user();

        // Hanya user yang upload bisa hapus, dan hanya jika status masih pending
        if ($dokumen->user_id !== $user->id || $dokumen->status !== 'pending') {
            return response()->json(['error' => 'Tidak dapat menghapus dokumen ini'], 403);
        }

        // Hapus file
        if (Storage::disk('public')->exists($dokumen->file_path)) {
            Storage::disk('public')->delete($dokumen->file_path);
        }
        if ($dokumen->signature_path && Storage::disk('public')->exists($dokumen->signature_path)) {
            Storage::disk('public')->delete($dokumen->signature_path);
        }

        $dokumen->delete();

        return response()->json(['message' => 'Dokumen berhasil dihapus']);
    }

    /**
     * Validasi dokumen (Direktur only)
     */
    /**
     * Validasi dokumen (Direktur only)
     */
    public function validasi(Request $request, string $id)
    {
        $user = Auth::user();

        if (!$user->isDirektur()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'prioritas' => 'nullable|in:BIASA,SEGERA,AMAT SEGERA', // Update prioritas values
            'disposisi_tujuan' => 'nullable|in:KEUANGAN,SDM,HUKUM,ASSET,UMUM', // New field
            'catatan' => 'nullable|string',
        ]);

        $dokumen = Dokumen::findOrFail($id);

        // Append disposisi instruction to catatan if exists
        $catatanVallidasi = $request->catatan;
        if ($request->filled('disposisi_tujuan')) {
            $catatanVallidasi = "[DISPOSISI: " . $request->disposisi_tujuan . "] " . $catatanVallidasi;
        }

        $updateData = [
            'status' => $request->status,
            'prioritas' => $request->prioritas,
            'catatan_validasi' => $catatanVallidasi,
            'validated_by' => $user->id,
            'tanggal_validasi' => now(),
        ];



        $dokumen->update($updateData);

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
        $user = Auth::user();

        if (!$user->isStaff()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $rules = [
            'status' => 'required|in:diproses,selesai',
            'catatan' => 'nullable|string',
        ];

        // Jika status selesai, wajib pilih kategori dan file balasan opsional
        if ($request->status === 'selesai') {
            $rules['kategori_arsip'] = 'required|in:UMUM,SDM,ASSET,HUKUM,KEUANGAN';
            $rules['file_balasan'] = 'nullable|file|max:10240'; // Max 10MB
        }

        $request->validate($rules);

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
            $updateData['kategori_arsip'] = $request->kategori_arsip;
            $updateData['is_archived'] = true;
            $updateData['tanggal_arsip'] = now();

            // Handle file balasan upload
            if ($request->hasFile('file_balasan')) {
                $file = $request->file('file_balasan');
                $fileName = $file->getClientOriginalName();
                $filePath = $file->store('dokumen/' . $dokumen->instansi->kode . '/balasan', 'public');
                $updateData['balasan_file'] = $filePath;
            }

            // Set status terbaca balasan ke false untuk user pengirim
            \DB::table('balasan_read_status')->updateOrInsert([
                'dokumen_id' => $dokumen->id,
                'user_id' => $dokumen->user_id,
            ], [
                'terbaca' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Auto-create Surat Masuk for instansi user (balasan dari staff)
            \App\Models\SuratMasuk::create([
                'instansi_id' => $dokumen->instansi_id,
                'nomor_surat' => 'BAL/' . $dokumen->nomor_dokumen,
                'tanggal_diterima' => now(),
                'pengirim' => 'Staff YARSI NTB',
                'perihal' => 'Balasan: ' . $dokumen->judul,
                'file' => $updateData['balasan_file'] ?? null,
            ]);
        }

        $dokumen->update($updateData);

        return response()->json([
            'message' => $request->status === 'selesai' 
                ? 'Dokumen berhasil diselesaikan, diarsipkan ke folder ' . $request->kategori_arsip . ', dan surat masuk telah dibuat untuk instansi'
                : 'Status dokumen berhasil diupdate',
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

        // Ensure filename has extension
        $downloadName = $dokumen->file_name;
        
        // Sanitize filename
        $downloadName = str_replace(['/', '\\'], '_', $downloadName);
        
        $extension = pathinfo($downloadName, PATHINFO_EXTENSION);
        
        // If no extension, try to guess or default to pdf
        if (empty($extension)) {
            $type = $dokumen->file_type ?? 'pdf';
            // Default to pdf if type is unknown or just 'file'
            if (empty($type) || $type === 'file') $type = 'pdf';
            $downloadName .= '.' . $type;
        }

        // Explicitly return download response with headers
        return response()->download(
            Storage::disk('public')->path($dokumen->file_path), 
            $downloadName,
            ['Content-Type' => Storage::disk('public')->mimeType($dokumen->file_path)]
        );
    }
}
