<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SuratMasuk;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function exportPdf(Request $request)
    {
        // Ambil semua surat dengan filters jika ada
        $query = SuratMasuk::with('klasifikasi');
        
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_surat', 'like', "%$search%")
                  ->orWhere('pengirim', 'like', "%$search%")
                  ->orWhere('perihal', 'like', "%$search%");
            });
        }
        
        if ($request->klasifikasi_id) {
            $query->where('klasifikasi_id', $request->klasifikasi_id);
        }
        
        $surats = $query->orderBy('tanggal', 'desc')->get();
        
        $html = view('exports.surat_pdf', ['surats' => $surats])->render();
        
        $pdf = Pdf::loadHTML($html)
                  ->setPaper('a4')
                  ->setOption('isRemoteEnabled', true);
        
        return $pdf->download('surat-masuk-' . now()->format('Y-m-d') . '.pdf');
    }
    
    public function exportCsv(Request $request)
    {
        // Ambil semua surat dengan filters jika ada
        $query = SuratMasuk::with('klasifikasi');
        
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_surat', 'like', "%$search%")
                  ->orWhere('pengirim', 'like', "%$search%")
                  ->orWhere('perihal', 'like', "%$search%");
            });
        }
        
        if ($request->klasifikasi_id) {
            $query->where('klasifikasi_id', $request->klasifikasi_id);
        }
        
        $surats = $query->orderBy('tanggal', 'desc')->get();
        
        $filename = 'surat-masuk-' . now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        
        $csv = fopen('php://temp', 'r+');
        
        // Header row
        fputcsv($csv, ['No Surat', 'Tanggal', 'Pengirim', 'Perihal', 'Klasifikasi']);
        
        // Data rows
        foreach ($surats as $surat) {
            fputcsv($csv, [
                $surat->no_surat,
                $surat->tanggal,
                $surat->pengirim,
                $surat->perihal,
                $surat->klasifikasi->nama ?? '-',
            ]);
        }
        
        rewind($csv);
        $contents = stream_get_contents($csv);
        fclose($csv);
        
        return response($contents, 200, $headers);
    }
}
