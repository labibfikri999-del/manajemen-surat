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
        $query = SuratKeluar::query();

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
            $item->file_url = $item->file ? Storage::url($item->file) : null;
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
            'tanggal_keluar' => 'required|date',
            'tujuan' => 'required|string',
            'perihal' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,png,jpg,jpeg,doc,docx,xlsx,zip,rar|max:10240',
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
        $surat->file_url = $surat->file ? Storage::url($surat->file) : null;

        return response()->json($surat, 201);
    }

    // Update surat keluar
    public function update(Request $request, $id)
    {
        $surat = SuratKeluar::findOrFail($id);

        $validated = $request->validate([
            'nomor_surat' => 'required|string',
            'tanggal_keluar' => 'required|date',
            'tujuan' => 'required|string',
            'perihal' => 'required|string',
            'status' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,png,jpg,jpeg,doc,docx,xlsx,zip,rar|max:10240',
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
        $surat->file_url = $surat->file ? Storage::url($surat->file) : null;

        return response()->json($surat);
    }

    // Delete surat keluar
    public function destroy($id)
    {
        $surat = SuratKeluar::findOrFail($id);

        // Delete file if exists
        if ($surat->file && Storage::disk('public')->exists($surat->file)) {
            Storage::disk('public')->delete($surat->file);
        }

        $surat->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }

    // Download file
    public function download($id)
    {
        $surat = SuratKeluar::findOrFail($id);

        if (! $surat->file || ! Storage::disk('public')->exists($surat->file)) {
            return response()->json(['message' => 'File not found'], 404);
        }

        $path = Storage::disk('public')->path($surat->file);

        return response()->download($path);
    }
}
