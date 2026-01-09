<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Financial Highlights (Real Data)
        $totalPemasukan = \App\Models\Keuangan\FinTransaction::where('type', 'pemasukan')->sum('amount');
        $totalPengeluaran = \App\Models\Keuangan\FinTransaction::where('type', 'pengeluaran')->sum('amount');
        $laba = $totalPemasukan - $totalPengeluaran;
        
        // Count Pending Claims for the "Claim Pending" card
        $pendingClaimsTotal = \App\Models\Keuangan\FinClaim::whereIn('status', ['Verifikasi', 'Submitted', 'Pending'])->sum('amount');


        $stats = [
            'pemasukan' => 'Rp ' . number_format($totalPemasukan, 0, ',', '.'),
            'pengeluaran' => 'Rp ' . number_format($totalPengeluaran, 0, ',', '.'),
            'laba' => 'Rp ' . number_format($laba, 0, ',', '.'),
            'pending_claims' => 'Rp ' . number_format($pendingClaimsTotal, 0, ',', '.'),
        ];

        // 2. Real Claims List
        $claims = \App\Models\Keuangan\FinClaim::orderBy('submitted_at', 'desc')->take(5)->get()->map(function($claim) {
            $claim->days = \Carbon\Carbon::parse($claim->submitted_at)->diffInDays(now());
            $claim->amount_formatted = 'Rp ' . number_format($claim->amount, 0, ',', '.');
            return $claim;
        });

        // 3. Department Budget Usage (Real Calculation)
        $rawBudgets = \App\Models\Keuangan\FinBudget::all();
        $budgets = $rawBudgets->map(function($budget) {
            // Sum expenses for this category
            $usedAmount = \App\Models\Keuangan\FinTransaction::where('type', 'pengeluaran')
                            ->where('category', $budget->department)
                            ->sum('amount');
            
            $percentage = $budget->limit_amount > 0 ? ($usedAmount / $budget->limit_amount) * 100 : 0;
            
            return (object)[
                'dept' => $budget->department,
                'used' => round($percentage),
                'limit' => 'Rp ' . number_format($budget->limit_amount / 1000000, 0) . 'jt', // Short format like 'Rp 500jt'
                'used_amount' => $usedAmount
            ];
        });

        // 4. Chart Data (Monthly Pemasukan vs Pengeluaran for Current Year)
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $pemasukanData = array_fill(0, 12, 0);
        $pengeluaranData = array_fill(0, 12, 0);

        $transactions = \App\Models\Keuangan\FinTransaction::whereYear('transaction_date', date('Y'))
                            ->selectRaw('MONTH(transaction_date) as month, type, sum(amount) as total')
                            ->groupBy('month', 'type')
                            ->get();

        foreach ($transactions as $t) {
            $index = $t->month - 1; // Month 1 -> Index 0
            if ($t->type == 'pemasukan') {
                $pemasukanData[$index] = $t->total;
            } else {
                $pengeluaranData[$index] = $t->total;
            }
        }

        return view('keuangan.dashboard', compact('stats', 'claims', 'budgets', 'months', 'pemasukanData', 'pengeluaranData'));
    }

    public function neraca()
    {
        $assets = [
            'lancar' => [
                'Kas & Setara Kas' => 'Rp 850.000.000',
                'Piutang Usaha (BPJS/Asuransi)' => 'Rp 1.200.000.000',
                'Persediaan Obat & Alkes' => 'Rp 450.000.000',
                'Perlengkapan Medis' => 'Rp 150.000.000'
            ],
            'tetap' => [
                'Bangunan & Gedung' => 'Rp 12.000.000.000',
                'Peralatan Medis (MRI, CT-Scan)' => 'Rp 8.500.000.000',
                'Kendaraan Ambulans' => 'Rp 850.000.000',
                'Akumulasi Penyusutan' => '(Rp 1.500.000.000)'
            ]
        ];

        $liabilities = [
            'pendek' => [
                'Utang Usaha (Obat)' => 'Rp 350.000.000',
                'Utang Gaji & Jasa Medis' => 'Rp 450.000.000',
                'Biaya YMH Dibayar' => 'Rp 120.000.000'
            ],
            'panjang' => [
                'Utang Bank Jangka Panjang' => 'Rp 4.500.000.000',
                'Kewajiban Imbalan Kerja' => 'Rp 850.000.000'
            ]
        ];

        $equity = [
            'Modal Yayasan/Pemilik' => 'Rp 12.000.000.000',
            'Laba Ditahan' => 'Rp 4.230.000.000',
            'Laba Tahun Berjalan' => 'Rp 1.330.000.000'
        ];

        return view('keuangan.neraca', compact('assets', 'liabilities', 'equity'));
    }

    public function arusKas()
    {
        $arusKas = [
            'operasional' => [
                ['desc' => 'Penerimaan dari Pasien Rawat Inap', 'amount' => 1250000000, 'type' => 'in'],
                ['desc' => 'Penerimaan dari Pasien Rawat Jalan', 'amount' => 850000000, 'type' => 'in'],
                ['desc' => 'Pembayaran ke Pemasok Obat', 'amount' => -450000000, 'type' => 'out'],
                ['desc' => 'Pembayaran Gaji & Jasa Medis', 'amount' => -850000000, 'type' => 'out'],
                ['desc' => 'Pembayaran Biaya Operasional (Listrik/Air)', 'amount' => -120000000, 'type' => 'out'],
            ],
            'investasi' => [
                ['desc' => 'Pembelian Alat USG Baru', 'amount' => -350000000, 'type' => 'out'],
                ['desc' => 'Penjualan Aset Lama', 'amount' => 45000000, 'type' => 'in'],
            ],
            'pendanaan' => [
                ['desc' => 'Pembayaran Cicilan Pokok Bank', 'amount' => -150000000, 'type' => 'out'],
            ]
        ];

        return view('keuangan.arus-kas', compact('arusKas'));
    }

    public function catatan()
    {
        $catatan = [
            [
                'title' => 'Kebijakan Akuntansi - Pengakuan Pendapatan',
                'date' => '01 Jan 2026',
                'content' => 'Pendapatan dari layanan medis diakui pada saat layanan diberikan kepada pasien. Untuk pasien BPJS, pendapatan diakui berdasarkan tarif INA-CBG yang berlaku.'
            ],
            [
                'title' => 'Rincian Aset Tetap - Peralatan Medis',
                'date' => '31 Des 2025',
                'content' => 'Saldo peralatan medis mencakup pembelian baru mesin USG 4D senilai Rp 350.000.000 dan peremajaan alat bedah senilai Rp 150.000.000. Penyusutan dihitung menggunakan metode garis lurus dengan masa manfaat 5-8 tahun.'
            ],
            [
                'title' => 'Liabilitas Kontinjensi',
                'date' => '31 Des 2025',
                'content' => 'Rumah sakit sedang dalam proses penyelesaian sengketa lahan parkir belakang dengan nilai klaim potensial Rp 50.000.000. Manajemen berpendapat bahwa kemungkinan pembayaran klaim tersebut kecil.'
            ]
        ];

        return view('keuangan.catatan', compact('catatan'));
    }
    public function downloadPdf()
    {
        // 1. Data Fetching (Same as Index)
        $totalPemasukan = \App\Models\Keuangan\FinTransaction::where('type', 'pemasukan')->sum('amount');
        $totalPengeluaran = \App\Models\Keuangan\FinTransaction::where('type', 'pengeluaran')->sum('amount');
        $laba = $totalPemasukan - $totalPengeluaran;
        $pendingClaimsTotal = \App\Models\Keuangan\FinClaim::whereIn('status', ['Verifikasi', 'Submitted', 'Pending'])->sum('amount');

        $stats = [
            'pemasukan' => 'Rp ' . number_format($totalPemasukan, 0, ',', '.'),
            'pengeluaran' => 'Rp ' . number_format($totalPengeluaran, 0, ',', '.'),
            'laba' => 'Rp ' . number_format($laba, 0, ',', '.'),
            'pending_claims' => 'Rp ' . number_format($pendingClaimsTotal, 0, ',', '.'),
        ];

        $claims = \App\Models\Keuangan\FinClaim::orderBy('submitted_at', 'desc')->get();
        
        $budgets = \App\Models\Keuangan\FinBudget::all()->map(function($budget) {
            $usedAmount = \App\Models\Keuangan\FinTransaction::where('type', 'pengeluaran')
                            ->where('category', $budget->department)
                            ->sum('amount');
            $percentage = $budget->limit_amount > 0 ? ($usedAmount / $budget->limit_amount) * 100 : 0;
            return (object)[
                'dept' => $budget->department,
                'used' => round($percentage),
                'limit' => 'Rp ' . number_format($budget->limit_amount / 1000000, 0) . 'jt',
                'used_amount_formatted' => 'Rp ' . number_format($usedAmount, 0, ',', '.')
            ];
        });

        // 2. Generate PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('keuangan.pdf.dashboard-report', compact('stats', 'claims', 'budgets'));
        return $pdf->download('Laporan_Keuangan_Per_' . date('d_M_Y') . '.pdf');
    }
}
