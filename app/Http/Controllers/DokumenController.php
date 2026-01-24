<?php

namespace App\Http\Controllers;

use App\Mail\DokumenMasukMail;
use App\Models\Dokumen;
use App\Models\Instansi;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use App\Models\User;
use App\Services\TelegramService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class DokumenController extends Controller
{
    protected $telegram;

    public function __construct(TelegramService $telegram)
    {
        $this->telegram = $telegram;
    }

    /**
     * Download file balasan dokumen
     */
    public function downloadBalasan($id)
    {
        $dokumen = Dokumen::findOrFail($id);
        if (! $dokumen->balasan_file || ! Storage::disk('public')->exists($dokumen->balasan_file)) {
            return response()->json(['error' => 'File balasan tidak ditemukan'], 404);
        }
        $downloadName = 'balasan_'.($dokumen->file_name ?? 'dokumen').'.'.pathinfo($dokumen->balasan_file, PATHINFO_EXTENSION);

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
        if (! $user->isInstansi() && ! $user->isStaff()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'nomor_surat' => 'nullable|string|max:100', // Validation
            'jenis' => 'required|string|in:surat_masuk,surat_keluar,proposal,laporan,sk,kontrak,lainnya',
            'deskripsi' => 'nullable|string',
            'tujuan_instansi_id' => 'nullable|exists:instansis,id',
            'email_eksternal' => 'nullable|email', // Validasi email eksternal
            'kategori_arsip' => 'nullable|string|in:UMUM,SDM,ASSET,HUKUM,KEUANGAN,SURAT_KELUAR,SK', // Opsi arsip langsung
            'file' => 'required|file|mimes:doc,docx,pdf|max:10240', // Word/PDF, Max 10MB
        ]);

        // Upload file
        // Instansi Kode Logic
        $instansiKode = 'YAYASAN';
        $targetInstansiId = null;
        $file = $request->file('file');
        $fileName = $file->getClientOriginalName(); // Define fileName early

        // === LOGIC BARU: KIRIM KE SEMUA UNIT USAHA ===
        if ($user->isStaff() && $request->filled('send_to_all') && $request->send_to_all) {
            
            // 1. Ambil semua unit usaha aktif
            $allInstansis = Instansi::where('is_active', true)->get();
            
            if ($allInstansis->isEmpty()) {
                return response()->json(['error' => 'Tidak ada unit usaha aktif ditemukan.'], 400);
            }

            // 2. Simpan file master sementara (untuk dicopy nanti)
            // Gunakan folder temp khusus
            $masterPath = $file->store('dokumen/temp_broadcast', 'public');
            $createdDocs = [];

            foreach ($allInstansis as $targetInstansi) {
                try {
                    // A. Prepare File Path & Instansi Data
                    $instansiKode = $targetInstansi->kode;
                    $targetFolder = 'dokumen/' . $instansiKode;
                    
                    // Generate unique filename for this unit
                    $extension = $file->getClientOriginalExtension();
                    $uniqueName = pathinfo($fileName, PATHINFO_FILENAME) . '_' . time() . '_' . \Illuminate\Support\Str::random(5) . '.' . $extension;
                    $finalPath = $targetFolder . '/' . $uniqueName;

                    // B. Copy File
                    Storage::disk('public')->copy($masterPath, $finalPath);

                    // C. Generate Nomor Dokumen (Unique per Instansi)
                    $nomorDokumen = Dokumen::generateNomorDokumen($instansiKode);

                    // D. Prepare Data
                    $createData = [
                        'nomor_dokumen' => $nomorDokumen,
                        'nomor_surat' => $request->nomor_surat,
                        'judul' => $request->judul,
                        'jenis_dokumen' => $request->jenis,
                        'deskripsi' => $request->deskripsi,
                        'file_path' => $finalPath,
                        'file_name' => $fileName,
                        'file_type' => $extension,
                        'file_size' => $file->getSize(),
                        'user_id' => $user->id,
                        'instansi_id' => $targetInstansi->id,
                        // Staff sending -> Auto Complete/Selesai/Surat Keluar
                        'status' => 'selesai',
                        'is_archived' => true,
                        'tanggal_arsip' => now(),
                        'tanggal_selesai' => now(),
                        'kategori_arsip' => 'SURAT_KELUAR', // Default categories if not specified? 
                        // Note: If sending logic implies Surat Keluar functionality:
                    ];
                    
                    // Optional: If user selected Kategori Arsip in form (though usually hidden if disabled?)
                    if ($request->filled('kategori_arsip')) {
                         $createData['kategori_arsip'] = $request->kategori_arsip;
                    }

                    // E. Create Dokumen
                    $dokumen = Dokumen::create($createData);
                    $createdDocs[] = $dokumen;

                    // F. Notifications & Relations
                    
                    // 1. Notif DB: Balasan Read Status (for receiver)
                    $targetUsers = User::where('instansi_id', $targetInstansi->id)->get();
                    foreach ($targetUsers as $targetUser) {
                        DB::table('balasan_read_status')->insert([
                            'dokumen_id' => $dokumen->id,
                            'user_id' => $targetUser->id,
                            'terbaca' => false,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }

                    // 2. Email
                    if ($targetInstansi->email) {
                        try {
                            Mail::to($targetInstansi->email)->send(new DokumenMasukMail($dokumen));
                        } catch (\Exception $e) {
                            Log::error('Gagal kirim email broadcast ke ' . $targetInstansi->nama . ': ' . $e->getMessage());
                        }
                    }

                    // 3. Telegram
                    $telegramUsers = User::where('instansi_id', $targetInstansi->id)->whereNotNull('telegram_chat_id')->get();
                    foreach ($telegramUsers as $tUser) {
                        $msg = "*SURAT MASUK DARI PUSAT* 📩\n".
                               "Judul: _{$request->judul}_\n".
                               "Pengirim: _Staff Pusat (Broadcast)_\n\n".
                               "Silakan cek menu Surat Masuk.\n".
                               '[Login Aplikasi]('.url('/login').')';
                        try {
                            $this->telegram->sendMessage($tUser->telegram_chat_id, $msg);
                        } catch (\Exception $e) {
                            Log::error('Telegram broadcast error: ' . $e->getMessage());
                        }
                    }

                } catch (\Exception $e) {
                    Log::error('Error processing broadcast for ' . $targetInstansi->nama . ': ' . $e->getMessage());
                    // Continue to next instansi even if one fails
                }
            }

            // Cleanup Master File
            Storage::disk('public')->delete($masterPath);

            return response()->json([
                'message' => 'Dokumen berhasil dikirim ke ' . count($createdDocs) . ' unit usaha.',
                'dokumen' => $createdDocs[0] ?? null, // Return first as sample
            ], 201);
        }

        // === END LOGIC BARU ===

        // Jika user adalah instansi, gunakan kodenya
        if ($user->isInstansi()) {
            if (! $user->instansi) {
                return response()->json(['error' => 'User Instansi tidak memiliki data instansi yang valid.'], 400);
            }
            $instansiKode = $user->instansi->kode;
            $targetInstansiId = $user->instansi_id;
        }
        // Jika Staff memilih tujuan instansi
        elseif ($request->filled('tujuan_instansi_id') && $user->isStaff()) {
            $targetInstansi = Instansi::find($request->tujuan_instansi_id);
            if ($targetInstansi) {
                $instansiKode = $targetInstansi->kode;
                $targetInstansiId = $targetInstansi->id;
            }
        }

        $filePath = $file->store('dokumen/'.$instansiKode, 'public');

        // Generate nomor dokumen
        $nomorDokumen = Dokumen::generateNomorDokumen($instansiKode);

        $createData = [
            'nomor_dokumen' => $nomorDokumen,
            'nomor_surat' => $request->nomor_surat, // Save manual nomor_surat
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

        // Jika Staff mengupload (bukan broadcast)
        if ($user->isStaff()) {
            // Validasi tambahan untuk kategori arsip
            if ($request->filled('kategori_arsip')) {
                $createData['status'] = 'selesai';
                $createData['is_archived'] = true;
                $createData['tanggal_arsip'] = now();
                $createData['tanggal_selesai'] = now(); // Mark as finished too
                $createData['kategori_arsip'] = $request->kategori_arsip;
            } elseif ($targetInstansiId || $request->filled('email_eksternal')) {
                $createData['status'] = 'selesai';
                $createData['is_archived'] = true;
                $createData['tanggal_arsip'] = now();
                $createData['tanggal_selesai'] = now();
                $createData['kategori_arsip'] = 'SURAT_KELUAR';
            } else {
                $createData['status'] = 'disetujui';
            }

            // Set balasan_file ONLY if targetInstansiId is set (internal flow), otherwise it's just an external send
            if ($targetInstansiId) {
                $createData['balasan_file'] = $filePath;
            }
        }

        $dokumen = Dokumen::create($createData);

        // Jika Staff mengirim ke Instansi, buat notifikasi balasan
        if ($user->isStaff() && $targetInstansiId) {
            $targetUsers = User::where('instansi_id', $targetInstansiId)->get();
            foreach ($targetUsers as $targetUser) {
                DB::table('balasan_read_status')->insert([
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
                    Log::error('Gagal kirim email dokumen masuk: '.$e->getMessage());
                }
            }
        }

        // KIRIM EMAIL EKSTERNAL (Jika Staff input email manual)
        if ($user->isStaff() && $request->filled('email_eksternal')) {
            try {
                Mail::to($request->email_eksternal)->send(new DokumenMasukMail($dokumen));
            } catch (\Exception $e) {
                Log::error('Gagal kirim email eksternal: '.$e->getMessage());
            }
        }

        // Auto-create Surat Keluar ONLY for instansi users
        if ($user->isInstansi()) {
            SuratKeluar::create([
                'instansi_id' => $user->instansi_id,
                'nomor_surat' => $nomorDokumen,
                'tanggal_keluar' => now(),
                'tujuan' => 'Direktur YARSI NTB',
                'perihal' => $request->judul,
                'file' => $filePath,
                'status' => 'Terkirim',
            ]);
        }

        // === NOTIFIKASI TELEGRAM ===

        // Skenario 1: STAFF Mengirim Dokumen ke Unit Usaha (Status: Disetujui/Selesai) -> Notif ke UNIT USAHA
        if (($createData['status'] === 'disetujui' || $createData['status'] === 'selesai') && $targetInstansiId && $user->isStaff()) {
            $targetUsers = User::where('instansi_id', $targetInstansiId)->whereNotNull('telegram_chat_id')->get();
            foreach ($targetUsers as $tUser) {
                $msg = "*SURAT MASUK DARI PUSAT* 📩\n".
                       "Judul: _{$request->judul}_\n".
                       "Pengirim: _Staff Pusat_\n\n".
                       "Silakan cek menu Surat Masuk.\n".
                       '[Login Aplikasi]('.url('/login').')';
                try {
                    $this->telegram->sendMessage($tUser->telegram_chat_id, $msg);
                } catch (\Exception $e) {
                    Log::error('Telegram error to Unit: '.$e->getMessage());
                }
            }
        }
        // Skenario 2: INSTANSI Upload Dokumen (Status: Pending) -> Notif ke DIREKTUR (Minta Validasi)
        elseif ($createData['status'] === 'pending') {
            $direkturs = User::where('role', 'direktur')->whereNotNull('telegram_chat_id')->get();
            foreach ($direkturs as $dir) {
                // If uploader is Staff (internal pending?), use 'Staff'. Else 'Instansi Name'.
                $senderName = $user->instansi ? $user->instansi->nama : 'Staff Internal';
                $msg = "*PERMOHONAN VALIDASI DOKUMEN* ⏳\n".
                       "Judul: _{$request->judul}_\n".
                       "Pengirim: _{$senderName}_\n\n".
                       "Mohon segera divalidasi.\n".
                       '[Login Aplikasi]('.url('/login').')';
                try {
                    $this->telegram->sendMessage($dir->telegram_chat_id, $msg);
                } catch (\Exception $e) {
                    Log::error('Telegram error to Direktur: '.$e->getMessage());
                }
            }
        }

        return response()->json([
            'message' => 'Dokumen berhasil diupload dan surat keluar telah dibuat',
            'dokumen' => $dokumen->load(['instansi', 'user']),
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
            'dokumen' => $dokumen->load(['instansi', 'user']),
        ]);
    }

    /**
     * Remove the specified dokumen.
     */
    public function destroy(string $id)
    {
        $dokumen = Dokumen::findOrFail($id);
        $user = Auth::user();

        // Check permission: Owner or Direktur can delete
        if ($dokumen->user_id !== $user->id && ! $user->isDirektur()) {
            return response()->json(['error' => 'Tidak dapat menghapus dokumen ini. Hanya pembuat atau Direktur yang diizinkan.'], 403);
        }

        // Restrict deletion: Only allow deleting Pending (cancelled), Selesai (finished), or Ditolak (rejected)
        // Prevent deleting active workflows (Review, Disetujui, Diproses)
        if (! in_array($dokumen->status, ['pending', 'selesai', 'ditolak'])) {
            return response()->json(['error' => 'Dokumen sedang diproses (status: '.ucfirst($dokumen->status).') dan tidak dapat dihapus.'], 403);
        }

        // Hapus file
        if ($dokumen->file_path && Storage::disk('public')->exists($dokumen->file_path)) {
            Storage::disk('public')->delete($dokumen->file_path);
        }
        if ($dokumen->balasan_file && Storage::disk('public')->exists($dokumen->balasan_file)) {
            Storage::disk('public')->delete($dokumen->balasan_file);
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

        if (! $user->isDirektur()) {
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
            $catatanVallidasi = '[DISPOSISI: '.$request->disposisi_tujuan.'] '.$catatanVallidasi;
        }

        $updateData = [
            'status' => $request->status,
            'prioritas' => $request->prioritas,
            'catatan_validasi' => $catatanVallidasi,
            'validated_by' => $user->id,
            'tanggal_validasi' => now(),
        ];

        $dokumen->update($updateData);

        // === NOTIFIKASI TELEGRAM KE STAFF (JIKA DISETUJUI) ===
        if ($request->status === 'disetujui') {
            $staffs = User::where('role', 'staff')->whereNotNull('telegram_chat_id')->get();
            foreach ($staffs as $staff) {
                $loginUrl = url('/login');
                $msg = "*DOKUMEN TELAH DIVALIDASI* ✅\n".
                       "Judul: _{$dokumen->judul}_\n".
                       "Oleh: _Direktur_\n".
                       "Status: *DISETUJUI*\n\n".
                       "Mohon segera diproses/tindak lanjuti.\n".
                       "[Login Aplikasi]($loginUrl)";
                $this->telegram->sendMessage($staff->telegram_chat_id, $msg);
            }
        }

        return response()->json([
            'message' => 'Dokumen berhasil divalidasi',
            'dokumen' => $dokumen->load(['instansi', 'user', 'validator']),
        ]);
    }

    /**
     * Proses dokumen (Staff only)
     */
    public function proses(Request $request, string $id)
    {
        $user = Auth::user();

        if (! $user->isStaff()) {
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
        if (! in_array($dokumen->status, ['disetujui', 'diproses'])) {
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
                $folderCode = $dokumen->instansi ? $dokumen->instansi->kode : 'INTERNAL';
                $filePath = $file->store('dokumen/'.$folderCode.'/balasan', 'public');
                $updateData['balasan_file'] = $filePath;
            }

            // Set status terbaca balasan ke false untuk user pengirim
            DB::table('balasan_read_status')->updateOrInsert([
                'dokumen_id' => $dokumen->id,
                'user_id' => $dokumen->user_id,
            ], [
                'terbaca' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Auto-create Surat Masuk for instansi user (balasan dari staff)
            if ($dokumen->instansi_id) {
                // Determine file to attach: newly uploaded balasan OR null
                $fileToAttach = $updateData['balasan_file'] ?? null;

                SuratMasuk::create([
                    'instansi_id' => $dokumen->instansi_id,
                    'nomor_surat' => 'BALASAN/'.$dokumen->nomor_dokumen, // Distinct numbering
                    'tanggal_diterima' => now(),
                    'pengirim' => 'Pusat (Administrator)',
                    'perihal' => 'SURAT BALASAN DARI PUSAT: '.$dokumen->judul,
                    'file' => $fileToAttach,
                    // 'klasifikasi_id' => null // Optional
                ]);
            }
        }

        $dokumen->update($updateData);

        return response()->json([
            'message' => $request->status === 'selesai'
                ? 'Dokumen berhasil diselesaikan, diarsipkan ke folder '.$request->kategori_arsip.', dan surat masuk telah dibuat untuk instansi'
                : 'Status dokumen berhasil diupdate',
            'dokumen' => $dokumen->load(['instansi', 'user', 'validator', 'processor']),
        ]);
    }

    /**
     * Download dokumen
     */
    public function download(string $id)
    {
        $dokumen = Dokumen::findOrFail($id);

        if (! Storage::disk('public')->exists($dokumen->file_path)) {
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
            if (empty($type) || $type === 'file') {
                $type = 'pdf';
            }
            $downloadName .= '.'.$type;
        }

        // Explicitly return download response with headers
        return response()->download(
            Storage::disk('public')->path($dokumen->file_path),
            $downloadName,
            ['Content-Type' => Storage::disk('public')->mimeType($dokumen->file_path)]
        );
    }

    /**
     * Show form for automatic letter generation
     */
    public function createSurat()
    {
        return view('buat-surat');
    }

    /**
     * Generate PDF and Store
     */
    public function storeGeneratedSurat(Request $request)
    {
        $request->validate([
            'nomor_surat' => 'required|string|unique:dokumens,nomor_dokumen',
            'lampiran' => 'nullable|string',
            'perihal' => 'required|string',
            'tujuan' => 'required|string',
            'tempat' => 'required|string',
            'tanggal' => 'required|date',
            'isi' => 'required|string',
            'nama_ttd' => 'required|string',
            'jabatan_ttd' => 'required|string',
        ]);

        $data = $request->all();

        // 1. Generate PDF
        $pdf = Pdf::loadView('pdf.kop-surat', compact('data'));
        $pdf->setPaper('A4', 'portrait');

        // 2. Store PDF
        $fileName = 'surat_'.time().'.pdf';
        $filePath = 'dokumen/generated/'.$fileName;
        Storage::disk('public')->put($filePath, $pdf->output());

        // 3. Create Database Record
        $user = Auth::user();
        $dokumen = Dokumen::create([
            'nomor_dokumen' => $request->nomor_surat,
            'judul' => $request->perihal,
            'jenis_dokumen' => 'surat_keluar',
            'deskripsi' => 'Surat Keluar Otomatis',
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_type' => 'pdf',
            'file_size' => Storage::disk('public')->size($filePath),
            'user_id' => $user->id,
            'instansi_id' => $user->instansi_id,
            'status' => 'pending',
        ]);

        // 4. Notify Direktur
        $direkturs = User::where('role', 'direktur')->whereNotNull('telegram_chat_id')->get();
        foreach ($direkturs as $dir) {
            $loginUrl = url('/login'); // Fixed URL
            $msg = "*SURAT KELUAR BARU* 📤\n".
                   "Judul: _{$request->perihal}_\n".
                   "Oleh: _{$user->name}_\n\n".
                   "Mohon diperiksa/ditandatangani.\n".
                   "[Login Aplikasi]($loginUrl)";
            $this->telegram->sendMessage($dir->telegram_chat_id, $msg);
        }

        return redirect()->route('arsip-digital')->with('success', 'Surat berhasil dibuat dan disimpan ke Arsip!');
    }

    /**
     * Download Generated Word (HTML method)
     */
    public function downloadWord(Request $request)
    {
        $request->validate([
            'nomor_surat' => 'required|string',
            'perihal' => 'required|string',
            'tempat' => 'required|string',
            'tanggal' => 'required|date',
            'isi' => 'required|string',
            'nama_ttd' => 'required|string',
            'jabatan_ttd' => 'required|string',
        ]);

        $data = $request->all();

        // Convert logo to base64
        $logoPath = public_path('images/Logo Yayasan Bersih.png');
        if (file_exists($logoPath)) {
            $logoData = base64_encode(file_get_contents($logoPath));
            $data['logo_base64'] = 'data:image/png;base64,'.$logoData;
        }

        $content = view('word.surat', compact('data'))->render();

        $filename = 'Surat_'.str_replace(['/', '\\'], '-', $request->nomor_surat).'.doc';

        return response($content)
            ->header('Content-Type', 'application/msword')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }
}
