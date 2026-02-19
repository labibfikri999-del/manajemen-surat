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

        // 1. Fetch Accounts from Database
        $accounts = \App\Models\Keuangan\FinAccount::all();

        // 2. Calculate "Kas & Setara Kas" dynamically from Transactions (Revenue - Expenses)
        // Adjust the base "Kas" balance with current month's flow if needed, 
        // OR just use the transactions flow + initial balance. 
        // For simplicity in this audit, let's assume 'Kas & Setara Kas' in FinAccount is the starting balance 
        // and we add year-to-date performance.
        
        $kasAccount = $accounts->where('name', 'Kas & Setara Kas')->first();
        $date = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        // Calculate Net Income YTD (Year to Date) to adjust Equity/Cash
        // In a real system, you'd close the books monthly.
        // Here we'll simulate: Cash = Initial + (Income - Expense)
        
        $totalPemasukan = FinTransaction::where('type', 'pemasukan')
            ->where('transaction_date', '<=', $date)
            ->sum('amount');
            
        $totalPengeluaran = FinTransaction::where('type', 'pengeluaran')
            ->where('transaction_date', '<=', $date)
            ->sum('amount');
            
        $netIncomeYTD = $totalPemasukan - $totalPengeluaran;
        
        // 3. Organize Data for View
        $assets = [
            'lancar' => $accounts->where('type', 'asset_current')->pluck('balance', 'name')->toArray(),
            'tetap' => $accounts->where('type', 'asset_fixed')->pluck('balance', 'name')->toArray(),
        ];
        
        // Override Kas with Dynamic Calculation if desired, or just add Net Income to it
        // Let's assume the seeded 'Kas' is the opening balance.
        if(isset($assets['lancar']['Kas & Setara Kas'])) {
            $assets['lancar']['Kas & Setara Kas'] += $netIncomeYTD;
        }

        $liabilities = [
            'pendek' => $accounts->where('type', 'liability_short')->pluck('balance', 'name')->toArray(),
            'panjang' => $accounts->where('type', 'liability_long')->pluck('balance', 'name')->toArray(),
        ];

        $equity = $accounts->where('type', 'equity')->pluck('balance', 'name')->toArray();
        
        // Add Current Year Earnings to Equity
        $equity['Laba Periode Berjalan'] = $netIncomeYTD;

        // Calculate Totals
        $totalAssets = array_sum($assets['lancar']) + array_sum($assets['tetap']);
        $totalLiabilities = array_sum($liabilities['pendek']) + array_sum($liabilities['panjang']);
        $totalEquity = array_sum($equity);

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
