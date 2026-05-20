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
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class DokumenController extends Controller
{
    private const ALLOWED_FILE_MIMES = 'pdf,png,jpg,jpeg,doc,docx,xls,xlsx,ppt,pptx,zip,rar,csv,txt';

    protected $telegram;

    public function __construct(TelegramService $telegram)
    {
        $this->telegram = $telegram;
    }

    private function canAccessDokumen(Dokumen $dokumen): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        if ($user->isDirektur() || $user->isStaff()) {
            return true;
        }

        if ($user->isInstansi()) {
            return ($user->instansi_id && $dokumen->instansi_id === $user->instansi_id)
                || $dokumen->user_id === $user->id;
        }

        return $dokumen->user_id === $user->id;
    }

    private function findAccessibleDokumen(string $id, array $relations = []): Dokumen
    {
        $query = Dokumen::query();

        if (! empty($relations)) {
            $query->with($relations);
        }

        $dokumen = $query->findOrFail($id);

        abort_unless($this->canAccessDokumen($dokumen), 403, 'Anda tidak memiliki akses ke dokumen ini.');

        return $dokumen;
    }

    /**
     * Download file balasan dokumen
     */
    public function downloadBalasan($id)
    {
        $dokumen = $this->findAccessibleDokumen($id);
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
    public function storeJsonUpload(Request $request)
    {
        $request->validate([
            'file_data' => 'required|string',
            'file_name' => 'required|string|max:255',
        ]);

        $fileData = $request->input('file_data');
        $fileName = basename($request->input('file_name'));
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExtensions = explode(',', self::ALLOWED_FILE_MIMES);

        if (! in_array($extension, $allowedExtensions, true)) {
            return response()->json(['error' => 'Format file tidak didukung.'], 422);
        }

        if (str_contains($fileData, ',')) {
            [, $fileData] = explode(',', $fileData, 2);
        }

        $decodedFile = base64_decode($fileData, true);

        if ($decodedFile === false) {
            return response()->json(['error' => 'File upload tidak valid.'], 422);
        }

        if (strlen($decodedFile) > 10 * 1024 * 1024) {
            return response()->json(['error' => 'Ukuran file maksimal 10MB.'], 422);
        }

        $tempDir = storage_path('app/temp_uploads');
        if (! is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $tempPath = $tempDir.'/'.Str::uuid().'.'.$extension;
        file_put_contents($tempPath, $decodedFile);

        $uploadedFile = new UploadedFile(
            $tempPath,
            $fileName,
            mime_content_type($tempPath) ?: null,
            null,
            true
        );

        $payload = $request->except(['file_data', 'file_name']);
        $server = $request->server->all();
        unset($server['CONTENT_TYPE'], $server['HTTP_CONTENT_TYPE']);

        $uploadRequest = Request::create(
            $request->getRequestUri(),
            'POST',
            $payload,
            $request->cookies->all(),
            ['file' => $uploadedFile],
            $server
        );
        $uploadRequest->setUserResolver($request->getUserResolver());
        $uploadRequest->setRouteResolver($request->getRouteResolver());
        if ($request->hasSession()) {
            $uploadRequest->setLaravelSession($request->session());
        }

        try {
            return $this->store($uploadRequest);
        } finally {
            if (is_file($tempPath)) {
                @unlink($tempPath);
            }
        }
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $userIsInstansi = $user->isInstansi();
        $userIsStaff = $user->isStaff() || ! $userIsInstansi;

        $request->validate([
            'judul' => 'required|string|max:255',
            'nomor_surat' => 'nullable|string|max:100', // Validation
            'jenis' => 'required|string|in:surat_masuk,surat_keluar,proposal,laporan,sk,kontrak,lainnya',
            'deskripsi' => 'nullable|string',
            'tujuan_instansi_id' => 'nullable|exists:instansis,id',
            'email_eksternal' => 'nullable|email', // Validasi email eksternal
            'kategori_arsip' => 'nullable|string|in:UMUM,SDM,ASSET,HUKUM,KEUANGAN,SURAT_KELUAR,SK', // Opsi arsip langsung
            'file' => 'required|file|mimes:'.self::ALLOWED_FILE_MIMES.'|max:10240',
        ]);

        // Upload file
        // Instansi Kode Logic
        $instansiKode = 'YAYASAN';
        $targetInstansiId = null;
        $targetInstansi = null;
        $file = $request->file('file');
        $fileName = $file->getClientOriginalName(); // Define fileName early
        $staffIsSendingOut = $userIsStaff && (
            $request->boolean('send_to_all')
            || $request->filled('tujuan_instansi_id')
            || $request->filled('email_eksternal')
        );
        $jenisDokumen = $request->jenis;

        // === LOGIC BARU: KIRIM KE SEMUA UNIT USAHA ===
        if ($userIsStaff && $request->boolean('send_to_all')) {
            
            // 1. Ambil semua unit usaha aktif
            $allInstansis = Instansi::where('is_active', true)->get();
            
            if ($allInstansis->isEmpty()) {
                return response()->json(['error' => 'Tidak ada unit usaha aktif ditemukan.'], 400);
            }

            // 2. Simpan file master sementara (untuk dicopy nanti)
            // Gunakan folder temp khusus
            $masterPath = $file->store('dokumen/temp_broadcast', 'public');
            $broadcastGroupId = (string) Str::uuid();
            $createdDocs = [];

            foreach ($allInstansis as $targetInstansi) {
                $finalPath = null;

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
                        'jenis_dokumen' => $jenisDokumen,
                        'deskripsi' => $request->deskripsi,
                        'file_path' => $finalPath,
                        'file_name' => $fileName,
                        'file_type' => $extension,
                        'file_size' => $file->getSize(),
                        'broadcast_group_id' => $broadcastGroupId,
                        'user_id' => $user->id,
                        'instansi_id' => $targetInstansi->id,
                        // Staff sending -> Auto Complete/Selesai/Surat Keluar
                        'status' => 'selesai',
                        'is_archived' => true,
                        'tanggal_arsip' => now(),
                        'tanggal_selesai' => now(),
                        'kategori_arsip' => $request->filled('kategori_arsip') ? $request->kategori_arsip : 'SURAT_KELUAR',
                    ];

                    $targetUsers = User::where('instansi_id', $targetInstansi->id)->get();

                    $dokumen = DB::transaction(function () use (
                        $createData,
                        $request,
                        $nomorDokumen,
                        $targetInstansi,
                        $broadcastGroupId,
                        $targetUsers
                    ) {
                        $dokumen = Dokumen::create($createData);

                        $this->recordSuratKeluar(
                            $dokumen,
                            $request->filled('nomor_surat') ? $request->nomor_surat : $nomorDokumen,
                            $targetInstansi->nama,
                            null,
                            $broadcastGroupId
                        );

                        foreach ($targetUsers as $targetUser) {
                            DB::table('balasan_read_status')->insert([
                                'dokumen_id' => $dokumen->id,
                                'user_id' => $targetUser->id,
                                'terbaca' => false,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }

                        return $dokumen;
                    });

                    $createdDocs[] = $dokumen;

                    // F. Notifications
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
                    if ($finalPath && Storage::disk('public')->exists($finalPath)) {
                        Storage::disk('public')->delete($finalPath);
                    }
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
        if ($userIsInstansi) {
            if (! $user->instansi) {
                return response()->json(['error' => 'User Instansi tidak memiliki data instansi yang valid.'], 400);
            }
            $instansiKode = $user->instansi->kode;
            $targetInstansiId = $user->instansi_id;
        }
        // Jika Staff memilih tujuan instansi
        elseif ($request->filled('tujuan_instansi_id') && $userIsStaff) {
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
            'jenis_dokumen' => $jenisDokumen,
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
        if ($userIsStaff) {
            if ($staffIsSendingOut) {
                $createData['status'] = 'selesai';
                $createData['is_archived'] = true;
                $createData['tanggal_arsip'] = now();
                $createData['tanggal_selesai'] = now();
                $createData['kategori_arsip'] = $request->filled('kategori_arsip') ? $request->kategori_arsip : 'SURAT_KELUAR';
            } elseif ($request->filled('kategori_arsip')) {
                $createData['status'] = 'selesai';
                $createData['is_archived'] = true;
                $createData['tanggal_arsip'] = now();
                $createData['tanggal_selesai'] = now(); // Mark as finished too
                $createData['kategori_arsip'] = $request->kategori_arsip;
            } else {
                $createData['status'] = 'disetujui';
            }

            // Set balasan_file ONLY if targetInstansiId is set (internal flow), otherwise it's just an external send
            if ($targetInstansiId) {
                $createData['balasan_file'] = $filePath;
            }
        }

        $targetUsers = ($userIsStaff && $targetInstansiId)
            ? User::where('instansi_id', $targetInstansiId)->get()
            : collect();

        try {
            $dokumen = DB::transaction(function () use (
                $createData,
                $user,
                $targetInstansiId,
                $targetInstansi,
                $request,
                $nomorDokumen,
                $targetUsers,
                $userIsStaff,
                $userIsInstansi
            ) {
                $dokumen = Dokumen::create($createData);

                if ($userIsStaff && ($targetInstansiId || $request->filled('email_eksternal'))) {
                    $tujuanSuratKeluar = $targetInstansiId
                        ? ($targetInstansi->nama ?? 'Unit Usaha')
                        : $request->email_eksternal;

                    $this->recordSuratKeluar(
                        $dokumen,
                        $request->filled('nomor_surat') ? $request->nomor_surat : $nomorDokumen,
                        $tujuanSuratKeluar,
                        null
                    );
                }

                foreach ($targetUsers as $targetUser) {
                    DB::table('balasan_read_status')->insert([
                        'dokumen_id' => $dokumen->id,
                        'user_id' => $targetUser->id,
                        'terbaca' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                if ($userIsInstansi) {
                    $this->recordSuratKeluar(
                        $dokumen,
                        $request->filled('nomor_surat') ? $request->nomor_surat : $nomorDokumen,
                        'Direktur YARSI NTB',
                        $user->instansi_id
                    );
                }

                return $dokumen;
            });
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan dokumen dan agenda surat: '.$e->getMessage());
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            return response()->json([
                'error' => 'Dokumen gagal disimpan. Silakan coba lagi.',
            ], 500);
        }

        // Jika Staff mengirim ke Instansi, buat notifikasi balasan
        if ($userIsStaff && $targetInstansiId) {
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
        if ($userIsStaff && $request->filled('email_eksternal')) {
            try {
                Mail::to($request->email_eksternal)->send(new DokumenMasukMail($dokumen));
            } catch (\Exception $e) {
                Log::error('Gagal kirim email eksternal: '.$e->getMessage());
            }
        }

        // === NOTIFIKASI TELEGRAM ===

        // Skenario 1: STAFF Mengirim Dokumen ke Unit Usaha (Status: Disetujui/Selesai) -> Notif ke UNIT USAHA
        if (($createData['status'] === 'disetujui' || $createData['status'] === 'selesai') && $targetInstansiId && $userIsStaff) {
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
        $dokumen = $this->findAccessibleDokumen($id, ['instansi', 'user', 'validator', 'processor']);

        return response()->json($dokumen);
    }

    /**
     * Update the specified dokumen.
     */
    public function update(Request $request, string $id)
    {
        $dokumen = $this->findAccessibleDokumen($id);
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
        $dokumen = $this->findAccessibleDokumen($id);
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

        $this->deleteStoredFiles($dokumen);

        $dokumen->delete();

        return response()->json(['message' => 'Dokumen berhasil dihapus']);
    }

    public function destroyBroadcast(string $id)
    {
        $dokumen = $this->findAccessibleDokumen($id);
        $user = Auth::user();

        if (! $user->isStaff() && ! $user->isDirektur()) {
            return response()->json(['error' => 'Hanya Staff atau Direktur yang dapat menghapus kiriman ke semua unit.'], 403);
        }

        if ($dokumen->user_id !== $user->id && ! $user->isDirektur()) {
            return response()->json(['error' => 'Tidak dapat menghapus kiriman ini. Hanya pembuat atau Direktur yang diizinkan.'], 403);
        }

        if (! $dokumen->broadcast_group_id) {
            return response()->json([
                'error' => 'Dokumen ini bukan kiriman ke semua unit, sehingga tidak bisa dihapus massal.',
            ], 400);
        }

        $dokumens = Dokumen::where('broadcast_group_id', $dokumen->broadcast_group_id)
            ->where('user_id', $dokumen->user_id)
            ->get();

        if ($dokumens->isEmpty()) {
            return response()->json(['error' => 'Data kiriman ke semua unit tidak ditemukan.'], 404);
        }

        foreach ($dokumens as $item) {
            $this->deleteStoredFiles($item);
            $item->delete();
        }

        return response()->json([
            'message' => 'Dokumen berhasil dihapus dari semua unit usaha.',
            'deleted_count' => $dokumens->count(),
        ]);
    }

    private function deleteStoredFiles(Dokumen $dokumen): void
    {
        foreach (array_unique(array_filter([$dokumen->file_path, $dokumen->balasan_file])) as $path) {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }

    private function recordSuratKeluar(
        Dokumen $dokumen,
        string $nomorSurat,
        string $tujuan,
        ?int $instansiId,
        ?string $broadcastGroupId = null
    ): void {
        SuratKeluar::create([
            'dokumen_id' => $dokumen->id,
            'broadcast_group_id' => $broadcastGroupId,
            'instansi_id' => $instansiId,
            'nomor_surat' => $nomorSurat,
            'tanggal_keluar' => now(),
            'tujuan' => $tujuan,
            'perihal' => $dokumen->judul,
            'file' => $dokumen->file_path,
            'status' => 'Terkirim',
        ]);
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
            $rules['kategori_arsip'] = 'required|in:UMUM,SDM,ASSET,HUKUM,KEUANGAN,TIDAK_DIARSIPKAN';
            $rules['file_balasan'] = 'nullable|file|mimes:'.self::ALLOWED_FILE_MIMES.'|max:10240';
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

            // Only archive if category is not "TIDAK_DIARSIPKAN"
            if ($request->kategori_arsip !== 'TIDAK_DIARSIPKAN') {
                $updateData['is_archived'] = true;
                $updateData['tanggal_arsip'] = now();
            } else {
                $updateData['is_archived'] = false;
                $updateData['tanggal_arsip'] = null;
            }

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

            // Auto-create Surat Masuk for instansi user (balasan dari staff) ONLY IF a file is uploaded
            if ($dokumen->instansi_id && isset($updateData['balasan_file'])) {
                $fileToAttach = $updateData['balasan_file'];

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
     * Get audit history
     */
    public function audits(string $id)
    {
        $dokumen = $this->findAccessibleDokumen($id);
        $audits = $dokumen->audits()->with('user:id,name')->get();
        return response()->json($audits);
    }

    /**
     * Download dokumen
     */
    public function download(string $id)
    {
        $dokumen = $this->findAccessibleDokumen($id);

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
     * Preview dokumen
     */
    public function preview(string $id)
    {
        $dokumen = $this->findAccessibleDokumen($id);

        if (! Storage::disk('public')->exists($dokumen->file_path)) {
            abort(404);
        }

        return response()->file(Storage::disk('public')->path($dokumen->file_path));
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

    /**
     * Revisi Dokumen yg sudah selesai
     */
    public function revisi(Request $request, string $id)
    {
        $user = Auth::user();

        if (! $user->isStaff()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'file_balasan' => 'required|file|mimes:'.self::ALLOWED_FILE_MIMES.'|max:10240',
            'catatan_revisi' => 'required|string',
        ]);

        $dokumen = Dokumen::findOrFail($id);

        if ($dokumen->status !== 'selesai') {
            return response()->json(['error' => 'Dokumen belum berstatus selesai'], 400);
        }

        // 1. Update balasan_file
        $file = $request->file('file_balasan');
        $folderCode = $dokumen->instansi ? $dokumen->instansi->kode : 'INTERNAL';
        $filePath = $file->store('dokumen/'.$folderCode.'/balasan', 'public');
        
        // Hapus file lama jika ada (opsional, better for storage)
        if ($dokumen->balasan_file && Storage::disk('public')->exists($dokumen->balasan_file)) {
            Storage::disk('public')->delete($dokumen->balasan_file);
        }

        $oldCatatan = $dokumen->catatan_proses ? $dokumen->catatan_proses . "\n\n" : "";
        $newCatatan = $oldCatatan . "[Revisi: " . date('Y-m-d H:i') . "] " . $request->catatan_revisi;

        $dokumen->update([
            'balasan_file' => $filePath,
            'catatan_proses' => $newCatatan
        ]);

        // 2. Set status terbaca balasan ke false untuk alert
        DB::table('balasan_read_status')->updateOrInsert([
            'dokumen_id' => $dokumen->id,
            'user_id' => $dokumen->user_id,
        ], [
            'terbaca' => false,
            'updated_at' => now(),
        ]);

        // 3. Update SuratMasuk yg berkaitan di Instansi
        if ($dokumen->instansi_id) {
            $suratMasuk = SuratMasuk::where('nomor_surat', 'BALASAN/'.$dokumen->nomor_dokumen)->first();
            if ($suratMasuk) {
                // Hapus surat masuk lama dari storage jika bukan file yg sama dgn $dokumen->balasan_file
                if ($suratMasuk->file && $suratMasuk->file !== $dokumen->balasan_file && Storage::disk('public')->exists($suratMasuk->file)) {
                    Storage::disk('public')->delete($suratMasuk->file);
                }

                $perihalTitle = $suratMasuk->perihal;
                if (!str_contains($perihalTitle, '[REVISI]')) {
                    $perihalTitle = '[REVISI] ' . $perihalTitle;
                }

                $suratMasuk->update([
                    'file' => $filePath,
                    'perihal' => $perihalTitle
                ]);
            } else {
                SuratMasuk::create([
                    'instansi_id' => $dokumen->instansi_id,
                    'nomor_surat' => 'BALASAN/'.$dokumen->nomor_dokumen, // Distinct numbering
                    'tanggal_diterima' => now(),
                    'pengirim' => 'Pusat (Administrator)',
                    'perihal' => '[REVISI] SURAT BALASAN DARI PUSAT: '.$dokumen->judul,
                    'file' => $filePath,
                ]);
            }

            // 4. Notifikasi Telegram (Revisi)
            $targetUsers = User::where('instansi_id', $dokumen->instansi_id)->whereNotNull('telegram_chat_id')->get();
            foreach ($targetUsers as $tUser) {
                $msg = "*UPDATE DOKUMEN REVISI* 🔄\n".
                       "Pusat baru saja merevisi balasan untuk dokumen:\n".
                       "Judul: _{$dokumen->judul}_\n".
                       "Catatan: _{$request->catatan_revisi}_\n\n".
                       "Silakan cek file terbaru di menu Surat Masuk.\n".
                       '[Login Aplikasi]('.url('/login').')';
                try {
                    $this->telegram->sendMessage($tUser->telegram_chat_id, $msg);
                } catch (\Exception $e) {
                    Log::error('Telegram error to Unit for Revisi: '.$e->getMessage());
                }
            }
        }

        return response()->json([
            'message' => 'Dokumen balasan berhasil direvisi dan diteruskan ke instansi.',
            'dokumen' => $dokumen->load(['instansi']),
        ]);
    }
}
