<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SuratKeluarController extends Controller
{
    // Get all surat keluar
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = SuratKeluar::with('lampirans');

        // Role-based filtering
        if ($user->role === 'instansi') {
            $query->where('instansi_id', $user->instansi_id);
        }
        // staff & direktur see all data

        // Date Filtering
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_keluar', [$request->start_date, $request->end_date]);
        }

        // Sorting
        $query->latest();

        $data = $query->get()->map(function ($item) {
            if ($item->file) {
                $item->file_url = Storage::url($item->file);
            } elseif ($item->konten) {
                $item->file_url = url('/api/surat-keluar/' . $item->id . '/download');
            } else {
                $item->file_url = null;
            }
            return $item;
        });

        return response()->json($data);
    }

    public function export(Request $request)
    {
        $user = Auth::user();
        $query = SuratKeluar::query();
        $instansiName = 'YARSI NTB';

        if ($user->role === 'instansi') {
            $query->where('instansi_id', $user->instansi_id);
            if ($user->instansi) {
                $instansiName = strtoupper($user->instansi->nama);
            }
        }

        $periode = 'Semua Waktu';
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_keluar', [$request->start_date, $request->end_date]);
            $periode = $request->start_date . ' s/d ' . $request->end_date;
        }

        $data = $query->latest()->get();

        $rows = [];
        // Header Title
        $rows[] = ['<b><center>LAPORAN SURAT KELUAR</center></b>', null, null, null, null, null];
        $rows[] = ['<b><center>' . $instansiName . '</center></b>', null, null, null, null, null];
        $rows[] = ['<center>Periode: ' . $periode . '</center>', null, null, null, null, null];
        $rows[] = ['']; // Empty row

        // Table Header
        $rows[] = ['<b><center>NO</center></b>', '<b><center>NOMOR SURAT</center></b>', '<b><center>TANGGAL KELUAR</center></b>', '<b><center>TUJUAN</center></b>', '<b><center>PERIHAL</center></b>', '<b><center>STATUS</center></b>'];

        foreach ($data as $i => $item) {
            $rows[] = [
                '<center>' . ($i + 1) . '</center>',
                $item->nomor_surat,
                '<center>' . $item->tanggal_keluar . '</center>',
                $item->tujuan,
                $item->perihal,
                '<center>' . $item->status . '</center>',
            ];
        }

        $xlsx = \Shuchkin\SimpleXLSXGen::fromArray($rows);
        $xlsx->mergeCells('A1:F1');
        $xlsx->mergeCells('A2:F2');
        $xlsx->mergeCells('A3:F3');
        $xlsx->downloadAs('Laporan_Surat_Keluar_'.date('Y-m-d_H-i').'.xlsx');
        exit;
    }

    // Store new surat keluar
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_surat' => 'required|string',
            'tanggal_keluar' => 'required|date|before_or_equal:today',
            'tujuan' => 'required|string',
            'perihal' => 'required|string',
            'konten' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,png,jpg,jpeg,doc,docx,xls,xlsx,ppt,pptx,zip,rar,csv,txt|max:10240',
            'lampirans.*' => 'nullable|file|mimes:pdf,png,jpg,jpeg,doc,docx,xls,xlsx,ppt,pptx,zip,rar,csv,txt|max:10240',
        ]);

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('surat-keluar', $filename, 'public');
            $validated['file'] = $path;
        }

        $validated['status'] = 'Draft';

        // Auto-assign instansi_id for instansi users
        $user = Auth::user();
        if ($user->role === 'instansi') {
            $validated['instansi_id'] = $user->instansi_id;
        }

        $surat = SuratKeluar::create($validated);
        
        if ($surat->file) {
            $surat->file_url = Storage::url($surat->file);
        } elseif ($surat->konten) {
            $surat->file_url = url('/api/surat-keluar/' . $surat->id . '/download');
        } else {
            $surat->file_url = null;
        }

        // Handle Multiple Attachments (Lampirans)
        if ($request->hasFile('lampirans')) {
            foreach ($request->file('lampirans') as $lampiran) {
                $lampFilename = time().'_'.$lampiran->getClientOriginalName();
                $lampPath = $lampiran->storeAs('surat-keluar-lampiran', $lampFilename, 'public');
                $surat->lampirans()->create([
                    'file_path' => $lampPath,
                    'file_name' => $lampiran->getClientOriginalName(),
                    'file_type' => $lampiran->getClientOriginalExtension(),
                    'file_size' => $lampiran->getSize(),
                ]);
            }
        }

        return response()->json($surat->load('lampirans'), 201);
    }

    // Update surat keluar
    public function update(Request $request, $id)
    {
        $surat = SuratKeluar::findOrFail($id);

        $validated = $request->validate([
            'nomor_surat' => 'required|string',
            'tanggal_keluar' => 'required|date|before_or_equal:today',
            'tujuan' => 'required|string',
            'perihal' => 'required|string',
            'status' => 'nullable|string',
            'konten' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,png,jpg,jpeg,doc,docx,xls,xlsx,ppt,pptx,zip,rar,csv,txt|max:10240',
            'lampirans.*' => 'nullable|file|mimes:pdf,png,jpg,jpeg,doc,docx,xls,xlsx,ppt,pptx,zip,rar,csv,txt|max:10240',
        ]);

        // Handle file upload
        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($surat->file && Storage::disk('public')->exists($surat->file)) {
                Storage::disk('public')->delete($surat->file);
            }

            $file = $request->file('file');
            $filename = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('surat-keluar', $filename, 'public');
            $validated['file'] = $path;
        }

        $surat->update($validated);

        if ($surat->file) {
            $surat->file_url = Storage::url($surat->file);
        } elseif ($surat->konten) {
            $surat->file_url = url('/api/surat-keluar/' . $surat->id . '/download');
        } else {
            $surat->file_url = null;
        }

        // Handle Multiple Attachments (Lampirans)
        if ($request->hasFile('lampirans')) {
            foreach ($request->file('lampirans') as $lampiran) {
                $lampFilename = time().'_'.$lampiran->getClientOriginalName();
                $lampPath = $lampiran->storeAs('surat-keluar-lampiran', $lampFilename, 'public');
                $surat->lampirans()->create([
                    'file_path' => $lampPath,
                    'file_name' => $lampiran->getClientOriginalName(),
                    'file_type' => $lampiran->getClientOriginalExtension(),
                    'file_size' => $lampiran->getSize(),
                ]);
            }
        }

        return response()->json($surat->load('lampirans'));
    }

    // Delete surat keluar
    public function destroy($id)
    {
        $surat = SuratKeluar::findOrFail($id);

        // Delete file if exists
        if ($surat->file && Storage::disk('public')->exists($surat->file)) {
            Storage::disk('public')->delete($surat->file);
        }

        // Delete lampirans files
        foreach ($surat->lampirans as $lampiran) {
            if (Storage::disk('public')->exists($lampiran->file_path)) {
                Storage::disk('public')->delete($lampiran->file_path);
            }
        }

        $surat->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }

    // Download file
    public function download($id)
    {
        $surat = SuratKeluar::findOrFail($id);

        if (! $surat->file && ! $surat->konten) {
            return response()->json(['message' => 'File or content not found'], 404);
        }

        // Log audit
        $surat->logAudit('downloaded', null, ['file' => $surat->file, 'konten' => $surat->konten ? 'Generated PDF' : null]);

        // If file exists, download the file directly
        if ($surat->file && Storage::disk('public')->exists($surat->file)) {
            $path = Storage::disk('public')->path($surat->file);
            return response()->download($path);
        }

        // If no file but konten exists, generate PDF on the fly
        if ($surat->konten) {
            $instansiName = Auth::user()->instansi ? Auth::user()->instansi->nama : 'SISTEM MANAJEMEN SURAT';
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.surat-keluar', [
                'surat' => $surat,
                'instansi_name' => $instansiName
            ]);
            
            return $pdf->stream('Surat_Keluar_' . str_replace('/', '_', $surat->nomor_surat) . '.pdf');
        }
        
        return response()->json(['message' => 'File not found'], 404);
    }

    // Get audit history
    public function audits($id)
    {
        $surat = SuratKeluar::findOrFail($id);
        $audits = $surat->audits()->with('user:id,name')->get();
        return response()->json($audits);
    }

    // Auto-generate Nomor Surat Keluar
    public function generateNomor()
    {
        $user = Auth::user();
        
        $kodeInstansi = 'UMUM';
        if ($user->role === 'instansi' && $user->instansi) {
            $kodeInstansi = strtoupper($user->instansi->kode);
        }

        $year = date('Y');
        $month = date('n'); // 1 to 12
        $romawiBulan = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        $romawi = $romawiBulan[$month - 1];

        // Format: [NomorUrut]/[KodeInstansi]/[RomawiBulan]/[Tahun]
        // Contoh prefix pencarian: %/YARSI/II/2026
        $searchPrefix = "/{$kodeInstansi}/{$romawi}/{$year}";
        
        $lastSurat = SuratKeluar::where('nomor_surat', 'like', "%{$searchPrefix}")
            ->orderBy('id', 'desc')
            ->first();

        $urutan = 1;
        if ($lastSurat) {
            $parts = explode('/', $lastSurat->nomor_surat);
            if (count($parts) > 0) {
                $urutan = intval($parts[0]) + 1;
            }
        }

        $nomorBaru = sprintf("%03d%s", $urutan, $searchPrefix);

        return response()->json(['nomor_surat' => $nomorBaru]);
    }
}
