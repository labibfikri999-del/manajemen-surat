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
        $query = SuratMasuk::query();

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
            'tanggal_diterima' => 'required|date',
            'pengirim' => 'required|string',
            'perihal' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,png,jpg,jpeg,doc,docx,xlsx,zip,rar|max:10240',
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

        $surat = SuratMasuk::create($validated);
        $surat->file_url = $surat->file ? Storage::url($surat->file) : null;

        return response()->json($surat, 201);
    }

    // Update surat masuk
    public function update(Request $request, $id)
    {
        $surat = SuratMasuk::findOrFail($id);

        $validated = $request->validate([
            'nomor_surat' => 'required|string|unique:surat_masuk,nomor_surat,'.$id,
            'tanggal_diterima' => 'required|date',
            'pengirim' => 'required|string',
            'perihal' => 'required|string',
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
            $path = $file->storeAs('surat-masuk', $filename, 'public');
            $validated['file'] = $path;
        }

        $surat->update($validated);
        $surat->file_url = $surat->file ? Storage::url($surat->file) : null;

        return response()->json($surat);
    }

    // Delete surat masuk
    public function destroy($id)
    {
        $surat = SuratMasuk::findOrFail($id);

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
        $surat = SuratMasuk::findOrFail($id);

        if (! $surat->file || ! Storage::disk('public')->exists($surat->file)) {
            return response()->json(['message' => 'File not found'], 404);
        }

        $path = Storage::disk('public')->path($surat->file);

        return response()->download($path);
    }
}
