<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function create()
    {
        return view('keuangan.transaksi.create');
    }

    public function store(Request $request)
    {
        // Remove commas from amount (visual formatting) to get raw integer
        $request->merge([
            'amount' => str_replace(',', '', $request->input('amount'))
        ]);

        $validated = $request->validate([
            'type' => 'required|in:pemasukan,pengeluaran',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'transaction_date' => 'required|date',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xlsx,zip,rar|max:10240'
        ]);

        $path = null;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('finance_attachments', 'public');
        }

        \App\Models\Keuangan\FinTransaction::create([
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'transaction_date' => $validated['transaction_date'],
            'attachment' => $path,
            'user_id' => auth()->id()
        ]);

        return redirect()->route('keuangan.dashboard')->with('success', 'Transaksi berhasil dicatat!');
    }

    public function pemasukan(Request $request)
    {
        return $this->getTransactions($request, 'pemasukan');
    }

    public function pengeluaran(Request $request)
    {
        return $this->getTransactions($request, 'pengeluaran');
    }

    private function getTransactions(Request $request, $type)
    {
        $query = \App\Models\Keuangan\FinTransaction::where('type', $type);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('transaction_date', [$request->start_date, $request->end_date]);
        }

        if ($request->has('export') && $request->export == 'csv') {
            return $this->exportCsv($query->get(), $type);
        }

        $transactions = $query->orderBy('transaction_date', 'desc')->paginate(10);
            
        $totalBulanIni = \App\Models\Keuangan\FinTransaction::where('type', $type)
            ->whereMonth('transaction_date', date('m'))
            ->sum('amount');
        
        // Calculate filtered total if filter is active
        $filteredTotal = $request->filled('start_date') ? $query->sum('amount') : null;

        return view('keuangan.transaksi.index', [
            'title' => ucfirst($type),
            'type' => $type,
            'transactions' => $transactions,
            'total' => $totalBulanIni,
            'filteredTotal' => $filteredTotal
        ]);
    }

    private function exportCsv($transactions, $type)
    {
        $fileName = 'laporan_' . $type . '_' . date('Y-m-d_His') . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = array('Tanggal', 'Kategori', 'Keterangan', 'Jumlah', 'Lampiran');

        $callback = function() use($transactions, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($transactions as $t) {
                $row['Tanggal']  = $t->transaction_date;
                $row['Kategori'] = $t->category;
                $row['Keterangan'] = $t->description;
                $row['Jumlah']   = $t->amount;
                $row['Lampiran'] = $t->attachment ? 'Ada' : 'Tidak';

                fputcsv($file, array($row['Tanggal'], $row['Kategori'], $row['Keterangan'], $row['Jumlah'], $row['Lampiran']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function edit($id)
    {
        $transaction = \App\Models\Keuangan\FinTransaction::findOrFail($id);
        return view('keuangan.transaksi.edit', compact('transaction'));
    }

    public function update(Request $request, $id)
    {
        $request->merge([
            'amount' => str_replace(',', '', $request->input('amount'))
        ]);

        $validated = $request->validate([
            'type' => 'required|in:pemasukan,pengeluaran',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'transaction_date' => 'required|date',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xlsx,zip,rar|max:10240'
        ]);

        $transaction = \App\Models\Keuangan\FinTransaction::findOrFail($id);

        if ($request->hasFile('attachment')) {
            // Delete old file if exists
            if ($transaction->attachment) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($transaction->attachment);
            }
            $validated['attachment'] = $request->file('attachment')->store('finance_attachments', 'public');
        }

        $transaction->update($validated);

        return redirect()->route('keuangan.' . $validated['type'])->with('success', 'Transaksi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $transaction = \App\Models\Keuangan\FinTransaction::findOrFail($id);
        $type = $transaction->type;
        
        if ($transaction->attachment) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($transaction->attachment);
        }
        
        $transaction->delete();

        return redirect()->route('keuangan.' . $type)->with('success', 'Transaksi berhasil dihapus!');
    }
}
