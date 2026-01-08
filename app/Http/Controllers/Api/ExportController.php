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
        // Use Dokumen model
        $query = \App\Models\Dokumen::with(['instansi', 'validator', 'processor']);

        $user = auth()->user();
        if ($user && $user->role === 'instansi') {
            $query->where('instansi_id', $user->instansi_id);
        }

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                    ->orWhere('deskripsi', 'like', "%$search%")
                    ->orWhereHas('instansi', function ($q2) use ($search) {
                        $q2->where('nama', 'like', "%$search%");
                    });
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->period) {
            $period = $request->period;
            if ($period === 'daily') {
                $query->whereDate('created_at', now());
            } elseif ($period === 'monthly') {
                $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
            } elseif ($period === 'yearly') {
                $query->whereYear('created_at', now()->year);
            }
        }

        $dokumens = $query->orderBy('created_at', 'desc')->get();

        $html = view('exports.surat_pdf', ['dokumens' => $dokumens])->render();

        $pdf = Pdf::loadHTML($html)
            ->setPaper('a4', 'landscape')
            ->setOption('isRemoteEnabled', true);

        return $pdf->download('laporan-dokumen-'.now()->format('Y-m-d').'.pdf');
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

        $filename = 'surat-masuk-'.now()->format('Y-m-d').'.csv';
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
