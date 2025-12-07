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
            'file' => 'required|file|mimes:doc,docx,pdf|max:10240', // Word/PDF, Max 10MB
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
        $user = auth()->user();

        if (!$user->isDirektur()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'catatan' => 'nullable|string',
            'signature' => 'nullable|string', // Base64 signature
        ]);

        $dokumen = Dokumen::findOrFail($id);

        $updateData = [
            'status' => $request->status,
            'catatan_validasi' => $request->catatan,
            'validated_by' => $user->id,
            'tanggal_validasi' => now(),
        ];

        // Handle Signature if Approved
        if ($request->status === 'disetujui' && $request->filled('signature')) {
            try {
                // 1. Decode & Save Signature Image
                $signatureData = $request->signature;
                $signatureData = str_replace('data:image/png;base64,', '', $signatureData);
                $signatureData = str_replace(' ', '+', $signatureData);
                $imageContent = base64_decode($signatureData);

                $signatureFilename = 'signature_' . $dokumen->id . '_' . time() . '.png';
                $signaturePath = 'signatures/' . $signatureFilename;
                
                Storage::disk('public')->put($signaturePath, $imageContent);
                $updateData['signature_path'] = $signaturePath;

                // 2. Embed to PDF if applicable
                $extension = strtolower(pathinfo($dokumen->file_path, PATHINFO_EXTENSION));
                if ($extension === 'pdf') {
                    $originalPath = Storage::disk('public')->path($dokumen->file_path);
                    $signatureAbsPath = Storage::disk('public')->path($signaturePath);
                    $signedFileName = pathinfo($dokumen->file_name, PATHINFO_FILENAME) . '_signed.pdf';
                    $signedPath = dirname($dokumen->file_path) . '/' . $signedFileName;
                    $signedAbsPath = Storage::disk('public')->path($signedPath);

                    // Initialize FPDI
                    $pdf = new \setasign\Fpdi\Fpdi();
                    
                    // Import original file
                    $pageCount = $pdf->setSourceFile($originalPath);

                    // Copy all existing pages
                    for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                        $templateId = $pdf->importPage($pageNo);
                        $size = $pdf->getTemplateSize($templateId);
                        $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                        $pdf->useTemplate($templateId);
                    }

                    // Add NEW Page for Signature
                    $pdf->AddPage();
                    $pdf->SetFont('Arial', 'B', 16);
                    $pdf->Cell(0, 10, 'LEMBAR PENGESAHAN DIGITAL', 0, 1, 'C');
                    $pdf->Ln(10);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Cell(0, 10, 'Dokumen ini telah divalidasi dan ditandatangani secara digital oleh:', 0, 1, 'L');
                    $pdf->Ln(5);
                    
                    $pdf->SetFont('Arial', 'B', 12);
                    $pdf->Cell(0, 10, strtoupper($user->name), 0, 1, 'L');
                    $pdf->SetFont('Arial', '', 11);
                    $pdf->Cell(0, 10, 'Direktur YARSI NTB', 0, 1, 'L');
                    $pdf->Cell(0, 10, 'Tanggal: ' . now()->format('d F Y H:i'), 0, 1, 'L');
                    $pdf->Ln(10);

                    // Place Signature Image (x, y, w, h)
                    $pdf->Image($signatureAbsPath, 10, $pdf->GetY(), 60); 
                    
                    $pdf->Ln(40);
                    $pdf->SetFont('Arial', 'I', 8);
                    $pdf->Cell(0, 10, 'Dokumen ini sah dan valid secara sistem.', 0, 1, 'L');

                    // Output new PDF
                    $pdf->Output($signedAbsPath, 'F');

                    // Update DB point to new signed file
                    $updateData['file_path'] = $signedPath;
                    $updateData['file_name'] = $signedFileName;
                }

            } catch (\Exception $e) {
                \Log::error('Signature Error: ' . $e->getMessage());
                // Continue validation even if signing fails, but log it
            }
        }

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
        $user = auth()->user();

        if (!$user->isStaff()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $rules = [
            'status' => 'required|in:diproses,selesai',
            'catatan' => 'nullable|string',
        ];

        // Jika status selesai, wajib pilih kategori
        if ($request->status === 'selesai') {
            $rules['kategori_arsip'] = 'required|in:UMUM,SDM,ASSET,HUKUM,KEUANGAN';
            $rules['file_pengganti'] = 'nullable|file|max:10240'; // Max 10MB
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
            
            // Handle file pengganti upload
            if ($request->hasFile('file_pengganti')) {
                $file = $request->file('file_pengganti');
                $fileName = $file->getClientOriginalName();
                $filePath = $file->store('dokumen/' . $dokumen->instansi->kode . '/pengganti', 'public');
                
                $updateData['file_pengganti_path'] = $filePath;
                $updateData['file_pengganti_name'] = $fileName;
                $updateData['file_pengganti_type'] = $file->getClientOriginalExtension();
                $updateData['file_pengganti_size'] = $file->getSize();
            }
        }

        $dokumen->update($updateData);

        return response()->json([
            'message' => $request->status === 'selesai' 
                ? 'Dokumen berhasil diselesaikan dan diarsipkan ke folder ' . $request->kategori_arsip 
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
