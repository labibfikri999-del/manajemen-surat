<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SuratMasukController extends Controller
{
    // Get all surat masuk
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = SuratMasuk::with('lampirans');

        // Role-based filtering
        if ($user->role === 'instansi') {
            $query->where('instansi_id', $user->instansi_id);
        }
        // staff & direktur see all data

        // Date Filtering
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_diterima', [$request->start_date, $request->end_date]);
        }

        // Sorting
        $query->latest();

        $data = $query->get()->map(function ($item) {
            $item->file_url = $item->file ? Storage::url($item->file) : null;
            return $item;
        });

        return response()->json($data);
    }

    public function export(Request $request)
    {
        $user = Auth::user();
        $query = SuratMasuk::query();
        $instansiName = 'YARSI NTB';

        if ($user->role === 'instansi') {
            $query->where('instansi_id', $user->instansi_id);
            if ($user->instansi) {
                $instansiName = strtoupper($user->instansi->nama);
            }
        }

        $periode = 'Semua Waktu';
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_diterima', [$request->start_date, $request->end_date]);
            $periode = $request->start_date . ' s/d ' . $request->end_date;
        }

        $data = $query->latest()->get();

        $rows = [];
        // Header Title
        $rows[] = ['<b><center>LAPORAN SURAT MASUK</center></b>', null, null, null, null];
        $rows[] = ['<b><center>' . $instansiName . '</center></b>', null, null, null, null];
        $rows[] = ['<center>Periode: ' . $periode . '</center>', null, null, null, null];
        $rows[] = ['']; // Empty row

        // Table Header
        $rows[] = ['<b><center>NO</center></b>', '<b><center>NOMOR SURAT</center></b>', '<b><center>TANGGAL DITERIMA</center></b>', '<b><center>PENGIRIM</center></b>', '<b><center>PERIHAL</center></b>'];

        foreach ($data as $i => $item) {
            $rows[] = [
                '<center>' . ($i + 1) . '</center>',
                $item->nomor_surat,
                '<center>' . $item->tanggal_diterima . '</center>',
                $item->pengirim,
                $item->perihal,
            ];
        }

        $xlsx = \Shuchkin\SimpleXLSXGen::fromArray($rows);
        $xlsx->mergeCells('A1:E1');
        $xlsx->mergeCells('A2:E2');
        $xlsx->mergeCells('A3:E3');
        $xlsx->downloadAs('Laporan_Surat_Masuk_'.date('Y-m-d_H-i').'.xlsx');
        exit;
    }

    // Store new surat masuk
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_surat' => 'required|string|unique:surat_masuk',
            'tanggal_diterima' => 'required|date|before_or_equal:today',
            'pengirim' => 'required|string',
            'perihal' => 'required|string',
            'status' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,png,jpg,jpeg,doc,docx,xls,xlsx,ppt,pptx,zip,rar,csv,txt|max:10240',
            'lampirans.*' => 'nullable|file|mimes:pdf,png,jpg,jpeg,doc,docx,xls,xlsx,ppt,pptx,zip,rar,csv,txt|max:10240',
        ]);

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('surat-masuk', $filename, 'public');
            $validated['file'] = $path;
        }

        // Add instansi_id from authenticated user
        if ($request->user()->instansi_id) {
            $validated['instansi_id'] = $request->user()->instansi_id;
        }

        if (!isset($validated['status'])) {
            $validated['status'] = 'Belum Diproses';
        }

        $surat = SuratMasuk::create($validated);
        $surat->file_url = $surat->file ? Storage::url($surat->file) : null;

        // Handle Multiple Attachments (Lampirans)
        if ($request->hasFile('lampirans')) {
            foreach ($request->file('lampirans') as $lampiran) {
                $lampFilename = time().'_'.$lampiran->getClientOriginalName();
                $lampPath = $lampiran->storeAs('surat-masuk-lampiran', $lampFilename, 'public');
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

    // Update surat masuk
    public function update(Request $request, $id)
    {
        $surat = SuratMasuk::findOrFail($id);

        $validated = $request->validate([
            'nomor_surat' => 'required|string|unique:surat_masuk,nomor_surat,'.$id,
            'tanggal_diterima' => 'required|date|before_or_equal:today',
            'pengirim' => 'required|string',
            'perihal' => 'required|string',
            'status' => 'nullable|string',
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
            $path = $file->storeAs('surat-masuk', $filename, 'public');
            $validated['file'] = $path;
        }

        $surat->update($validated);
        $surat->file_url = $surat->file ? Storage::url($surat->file) : null;

        // Handle Multiple Attachments (Lampirans)
        if ($request->hasFile('lampirans')) {
            foreach ($request->file('lampirans') as $lampiran) {
                $lampFilename = time().'_'.$lampiran->getClientOriginalName();
                $lampPath = $lampiran->storeAs('surat-masuk-lampiran', $lampFilename, 'public');
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

    // Delete surat masuk
    public function destroy($id)
    {
        $surat = SuratMasuk::findOrFail($id);

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
        $surat = SuratMasuk::findOrFail($id);

        if (! $surat->file || ! Storage::disk('public')->exists($surat->file)) {
            return response()->json(['message' => 'File not found'], 404);
        }

        // Log audit
        $surat->logAudit('downloaded', null, ['file' => $surat->file]);

        $path = Storage::disk('public')->path($surat->file);

        return response()->download($path);
    }

    // Get audit history
    public function audits($id)
    {
        $surat = SuratMasuk::findOrFail($id);
        $audits = $surat->audits()->with('user:id,name')->get();
        return response()->json($audits);
    }
}
