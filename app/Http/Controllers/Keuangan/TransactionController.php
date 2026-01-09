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
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
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

    public function pemasukan()
    {
        $transactions = \App\Models\Keuangan\FinTransaction::where('type', 'pemasukan')
            ->orderBy('transaction_date', 'desc')
            ->paginate(10);
            
        $totalBulanIni = \App\Models\Keuangan\FinTransaction::where('type', 'pemasukan')
            ->whereMonth('transaction_date', date('m'))
            ->sum('amount');

        return view('keuangan.transaksi.index', [
            'title' => 'Pemasukan',
            'type' => 'pemasukan',
            'transactions' => $transactions,
            'total' => $totalBulanIni
        ]);
    }

    public function pengeluaran()
    {
        $transactions = \App\Models\Keuangan\FinTransaction::where('type', 'pengeluaran')
            ->orderBy('transaction_date', 'desc')
            ->paginate(10);

        $totalBulanIni = \App\Models\Keuangan\FinTransaction::where('type', 'pengeluaran')
            ->whereMonth('transaction_date', date('m'))
            ->sum('amount');
            
        return view('keuangan.transaksi.index', [
            'title' => 'Pengeluaran',
            'type' => 'pengeluaran',
            'transactions' => $transactions,
            'total' => $totalBulanIni
        ]);
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
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
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
