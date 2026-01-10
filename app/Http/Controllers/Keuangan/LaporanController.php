<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Keuangan\FinTransaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function labaRugi(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        // Calculate Revenue (Pemasukan)
        $pemasukan = FinTransaction::where('type', 'pemasukan')
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->sum('amount');

        // Calculate Expenses (Pengeluaran)
        $pengeluaran = FinTransaction::where('type', 'pengeluaran')
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->sum('amount');

        // Calculate Net Profit
        $labaBersih = $pemasukan - $pengeluaran;

        return view('keuangan.laporan.laba-rugi', compact('pemasukan', 'pengeluaran', 'labaBersih', 'month', 'year'));
    }

    public function neraca(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        // Calculate "Kas" (Cash) based on all transactions up to end of selected period
        // For Balance Sheet, we need cumulative balance
        $date = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        
        $totalPemasukan = FinTransaction::where('type', 'pemasukan')
            ->where('transaction_date', '<=', $date)
            ->sum('amount');
            
        $totalPengeluaran = FinTransaction::where('type', 'pengeluaran')
            ->where('transaction_date', '<=', $date)
            ->sum('amount');

        $kas = $totalPemasukan - $totalPengeluaran;

        // Static/Placeholder Data for other Asset/Liability components (as per plan)
        // In a real app, these would come from other tables or general ledger
        $assets = [
            'lancar' => [
                'Kas & Setara Kas' => $kas,
                'Piutang Usaha' => 1200000000,
                'Persediaan' => 450000000,
            ],
            'tetap' => [
                'Bangunan & Gedung' => 12000000000,
                'Peralatan Medis' => 8500000000,
                'Kendaraan' => 850000000,
                'Akumulasi Penyusutan' => -1500000000 // Negative value
            ]
        ];

        $liabilities = [
            'pendek' => [
                'Utang Usaha' => 350000000,
                'Utang Gaji' => 450000000,
            ],
            'panjang' => [
                'Utang Bank' => 4500000000,
                'Kewajiban Lain' => 850000000
            ]
        ];

        // Equity = Assets - Liabilities
        $totalAssets = array_sum($assets['lancar']) + array_sum($assets['tetap']);
        $totalLiabilities = array_sum($liabilities['pendek']) + array_sum($liabilities['panjang']);
        $totalEquity = $totalAssets - $totalLiabilities;

        $equity = [
            'Modal Yayasan' => 12000000000,
            'Laba Ditahan' => $totalEquity - 12000000000 // Plug figure to balance
        ];

        return view('keuangan.neraca', compact('assets', 'liabilities', 'equity', 'month', 'year', 'totalAssets', 'totalLiabilities', 'totalEquity'));
    }

    public function arusKas(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        // Fetch transactions for the period
        $transactions = FinTransaction::whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->get();

        // Simple categorization logic
        // In a real app, you might have a 'cash_flow_category' column.
        // Here we'll default everything to 'operasional' unless specified keywords match.
        
        $arusKas = [
            'operasional' => [],
            'investasi' => [],
            'pendanaan' => []
        ];

        foreach ($transactions as $t) {
            $amount = $t->type == 'pemasukan' ? $t->amount : -$t->amount;
            
            // Basic Keyword Matching
            $desc = strtolower($t->description . ' ' . $t->category);
            
            if (str_contains($desc, 'invest') || str_contains($desc, 'aset') || str_contains($desc, 'beli alat')) {
                $arusKas['investasi'][] = [
                    'desc' => $t->description ?? $t->category,
                    'amount' => $amount,
                    'type' => $t->type == 'pemasukan' ? 'in' : 'out'
                ];
            } elseif (str_contains($desc, 'modal') || str_contains($desc, 'pinjaman') || str_contains($desc, 'bank')) {
                $arusKas['pendanaan'][] = [
                    'desc' => $t->description ?? $t->category,
                    'amount' => $amount,
                    'type' => $t->type == 'pemasukan' ? 'in' : 'out'
                ];
            } else {
                $arusKas['operasional'][] = [
                    'desc' => $t->description ?? $t->category,
                    'amount' => $amount,
                    'type' => $t->type == 'pemasukan' ? 'in' : 'out'
                ];
            }
        }

        return view('keuangan.arus-kas', compact('arusKas', 'month', 'year'));
    }

    public function catatan()
    {
        $catatan = \App\Models\Keuangan\FinNote::orderBy('date', 'desc')->get();
        return view('keuangan.catatan', compact('catatan'));
    }

    public function storeCatatan(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'date' => 'required|date',
        ]);

        \App\Models\Keuangan\FinNote::create([
            'title' => $request->title,
            'content' => $request->content,
            'date' => $request->date,
            'user_id' => auth()->id()
        ]);

        return redirect()->back()->with('success', 'Catatan berhasil ditambahkan.');
    }

    public function updateCatatan(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'date' => 'required|date',
        ]);

        $note = \App\Models\Keuangan\FinNote::findOrFail($id);
        $note->update($request->only(['title', 'content', 'date']));

        return redirect()->back()->with('success', 'Catatan berhasil diperbarui.');
    }

    public function destroyCatatan($id)
    {
        \App\Models\Keuangan\FinNote::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Catatan berhasil dihapus.');
    }
}
