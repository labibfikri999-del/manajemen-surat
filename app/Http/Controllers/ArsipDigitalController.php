<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ArsipDigitalController extends Controller
{
    private function archiveQuery()
    {
        $user = Auth::user();
        $query = Dokumen::where('is_archived', true);

        if ($user?->isInstansi()) {
            abort_unless($user->instansi_id, 403, 'User instansi tidak memiliki data instansi.');
            $query->where('instansi_id', $user->instansi_id);
        } elseif ($user && ! $user->isDirektur() && ! $user->isStaff()) {
            $query->where('user_id', $user->id);
        }

        return $query;
    }

    private function findAccessibleArchive($id): Dokumen
    {
        return $this->archiveQuery()->findOrFail($id);
    }

    private function normalizeCategory(?string $category): ?string
    {
        if ($category === null || $category === '') {
            return null;
        }

        return strtoupper($category);
    }

    private function decorateArchive(Dokumen $item): Dokumen
    {
        $item->file_url = $item->file_path ? Storage::url($item->file_path) : null;
        $item->nama_dokumen = $item->judul;
        $item->nama_file = $item->file_name;
        $item->kategori = $item->kategori_arsip;
        $item->tipe = $item->file_type;

        return $item;
    }

    private function applyCategoryFilter($query, string $kategori)
    {
        $kategori = strtoupper($kategori);

        if ($kategori === 'SURAT_KELUAR') {
            return $query->where(function ($q) {
                $q->where('kategori_arsip', 'SURAT_KELUAR')
                    ->orWhereIn('id', SuratKeluar::whereNotNull('dokumen_id')->select('dokumen_id'));
            });
        }

        return $query->where('kategori_arsip', $kategori);
    }

    // Get all files
    public function index()
    {
        $query = $this->archiveQuery();

        $data = $query->latest('tanggal_arsip')->get()->map(fn ($item) => $this->decorateArchive($item));

        return response()->json($data);
    }

    // Upload file
    public function store(Request $request)
    {
        $user = Auth::user();
        abort_unless($user->isDirektur() || $user->isStaff(), 403, 'Hanya Staff dan Direktur yang dapat mengupload arsip.');

        $validated = $request->validate([
            'judul' => 'nullable|required_without:nama_dokumen|string|max:255',
            'nama_dokumen' => 'nullable|required_without:judul|string|max:255',
            'kategori_arsip' => 'nullable|string|in:UMUM,SDM,ASSET,HUKUM,KEUANGAN,SURAT_KELUAR,SK',
            'kategori' => 'nullable|string|in:UMUM,SDM,ASSET,HUKUM,KEUANGAN,SURAT_KELUAR,SK',
            'deskripsi' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,png,jpg,jpeg,doc,docx,xls,xlsx,ppt,pptx,zip,rar,csv,txt|max:10240',
        ]);

        $file = $request->file('file');
        $instansiKode = $user->instansi?->kode ?? 'ARSIP';
        $path = $file->store('dokumen/'.$instansiKode.'/arsip', 'public');
        $category = $this->normalizeCategory($validated['kategori_arsip'] ?? $validated['kategori'] ?? null);

        $arsip = Dokumen::create([
            'nomor_dokumen' => Dokumen::generateNomorDokumen($instansiKode),
            'judul' => $validated['judul'] ?? $validated['nama_dokumen'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'user_id' => $user->id,
            'instansi_id' => $user->instansi_id,
            'status' => 'selesai',
            'kategori_arsip' => $category,
            'is_archived' => true,
            'tanggal_arsip' => now(),
            'processed_by' => $user->id,
            'tanggal_selesai' => now(),
        ]);

        $this->decorateArchive($arsip);

        return response()->json($arsip, 201);
    }

    // Update arsip
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        abort_unless($user->isDirektur() || $user->isStaff(), 403, 'Hanya Staff dan Direktur yang dapat mengubah arsip.');

        $arsip = $this->findAccessibleArchive($id);

        $validated = $request->validate([
            'nama_dokumen' => 'nullable|required_without:judul|string|max:255',
            'judul' => 'nullable|required_without:nama_dokumen|string|max:255',
            'kategori' => 'nullable|string|in:UMUM,SDM,ASSET,HUKUM,KEUANGAN,SURAT_KELUAR,SK,TIDAK_DIARSIPKAN',
            'kategori_arsip' => 'nullable|string|in:UMUM,SDM,ASSET,HUKUM,KEUANGAN,SURAT_KELUAR,SK,TIDAK_DIARSIPKAN',
            'deskripsi' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,png,jpg,jpeg,doc,docx,xls,xlsx,ppt,pptx,zip,rar,csv,txt|max:10240',
        ]);

        $updateData = [
            'judul' => $validated['judul'] ?? $validated['nama_dokumen'],
            'kategori_arsip' => $this->normalizeCategory($validated['kategori_arsip'] ?? $validated['kategori'] ?? null),
            'deskripsi' => $validated['deskripsi'] ?? null,
        ];

        // Handle file upload
        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($arsip->file_path && Storage::disk('public')->exists($arsip->file_path)) {
                Storage::disk('public')->delete($arsip->file_path);
            }

            $file = $request->file('file');
            $instansiKode = $arsip->instansi?->kode ?? $user->instansi?->kode ?? 'ARSIP';
            $path = $file->store('dokumen/'.$instansiKode.'/arsip', 'public');

            $updateData['file_name'] = $file->getClientOriginalName();
            $updateData['file_path'] = $path;
            $updateData['file_type'] = $file->getClientOriginalExtension();
            $updateData['file_size'] = $file->getSize();
        }

        $arsip->update($updateData);
        $this->decorateArchive($arsip);

        return response()->json($arsip);
    }

    // Remove from Arsip Digital (Un-archive) instead of hard delete
    public function destroy($id)
    {
        $user = Auth::user();
        abort_unless($user->isDirektur() || $user->isStaff(), 403, 'Hanya Staff dan Direktur yang dapat mengubah arsip.');

        $dokumen = $this->findAccessibleArchive($id);

        // Instead of hard-deleting the document (which removes it from the Unit Usaha's history as well),
        // we just mark it as not archived.
        $dokumen->update([
            'is_archived' => false,
            'kategori_arsip' => 'TIDAK_DIARSIPKAN',
            'tanggal_arsip' => null
        ]);

        return response()->json(['message' => 'File deleted successfully']);
    }

    // Download file
    public function download($id)
    {
        $arsip = $this->findAccessibleArchive($id);

        if (! $arsip->file_path || ! Storage::disk('public')->exists($arsip->file_path)) {
            return response()->json(['message' => 'File not found'], 404);
        }

        $path = Storage::disk('public')->path($arsip->file_path);

        return response()->download($path, $arsip->file_name ?? 'dokumen.pdf');
    }

    // Get statistics for Arsip Digital page
    public function getStats()
    {
        $query = $this->archiveQuery();
        $totalDokumen = $query->count();
        $totalBytes = $query->sum('file_size');

        // Format size
        if ($totalBytes >= 1073741824) {
            $ukuranTotal = number_format($totalBytes / 1073741824, 2).' GB';
        } elseif ($totalBytes >= 1048576) {
            $ukuranTotal = number_format($totalBytes / 1048576, 2).' MB';
        } elseif ($totalBytes >= 1024) {
            $ukuranTotal = number_format($totalBytes / 1024, 2).' KB';
        } else {
            $ukuranTotal = $totalBytes.' B';
        }

        // Get last access
        $lastAccess = $this->archiveQuery()->latest('updated_at')->first();
        $aksesTerakhir = $lastAccess ? $lastAccess->updated_at->diffForHumans() : 'Belum ada data';

        return response()->json([
            'total_dokumen' => $totalDokumen,
            'ukuran_total' => $ukuranTotal,
            'akses_terakhir' => $aksesTerakhir,
        ]);
    }

    // Get document count by category
    public function getKategoriCount()
    {
        $baseQuery = $this->archiveQuery();
        $counts = [
            'UMUM' => $this->applyCategoryFilter(clone $baseQuery, 'UMUM')->count(),
            'SDM' => $this->applyCategoryFilter(clone $baseQuery, 'SDM')->count(),
            'ASSET' => $this->applyCategoryFilter(clone $baseQuery, 'ASSET')->count(),
            'HUKUM' => $this->applyCategoryFilter(clone $baseQuery, 'HUKUM')->count(),
            'KEUANGAN' => $this->applyCategoryFilter(clone $baseQuery, 'KEUANGAN')->count(),
            'SURAT_KELUAR' => $this->applyCategoryFilter(clone $baseQuery, 'SURAT_KELUAR')->count(),
            'SK' => $this->applyCategoryFilter(clone $baseQuery, 'SK')->count(),
        ];

        return response()->json($counts);
    }

    // Get documents by category
    public function getByKategori($kategori)
    {
        $dokumens = $this->applyCategoryFilter($this->archiveQuery(), $kategori)
            ->with(['instansi', 'processor'])
            ->latest('tanggal_arsip')
            ->get();

        $dokumens->map(fn ($item) => $this->decorateArchive($item));

        return response()->json($dokumens);
    }

    // Download all files in a category as ZIP
    public function downloadKategori($kategori)
    {
        $dokumens = $this->applyCategoryFilter($this->archiveQuery(), $kategori)->get();

        if ($dokumens->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada dokumen dalam kategori ini untuk diunduh.');
        }

        $zipFileName = 'Arsip_'.$kategori.'_'.date('Ymd_His').'.zip';
        $tempDir = storage_path('app/public/temp');
        if (! file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        $zipPath = $tempDir.'/'.$zipFileName;

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($dokumens as $dok) {
                if ($dok->file_path && Storage::disk('public')->exists($dok->file_path)) {
                    $absolutePath = Storage::disk('public')->path($dok->file_path);
                    // Prevent duplicate names in zip
                    $fileNameInZip = $dok->file_name ?? basename($dok->file_path);
                    $zip->addFile($absolutePath, $fileNameInZip);
                }
            }
            $zip->close();
        } else {
            return redirect()->back()->with('error', 'Gagal membuat file ZIP.');
        }

        if (file_exists($zipPath)) {
            return response()->download($zipPath)->deleteFileAfterSend(true);
        } else {
            return redirect()->back()->with('error', 'File ZIP gagal dibuat.');
        }
    }
}
