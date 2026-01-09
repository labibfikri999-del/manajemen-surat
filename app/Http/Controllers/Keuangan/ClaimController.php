<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClaimController extends Controller
{
    public function index()
    {
        $claims = \App\Models\Keuangan\FinClaim::orderBy('created_at', 'desc')->paginate(10);
        return view('keuangan.klaim.index', compact('claims'));
    }

    public function create()
    {
        return view('keuangan.klaim.create');
    }

    public function store(Request $request)
    {
        // Remove commas
        $request->merge([
            'amount' => str_replace(',', '', $request->input('amount'))
        ]);

        $validated = $request->validate([
            'provider' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:Verifikasi,Submitted,Pending,Paid',
            'submitted_at' => 'required|date',
        ]);

        \App\Models\Keuangan\FinClaim::create($validated);

        return redirect()->route('keuangan.klaim.index')->with('success', 'Klaim asuransi berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $claim = \App\Models\Keuangan\FinClaim::findOrFail($id);
        return view('keuangan.klaim.edit', compact('claim'));
    }

    public function update(Request $request, $id)
    {
        $request->merge([
            'amount' => str_replace(',', '', $request->input('amount'))
        ]);

        $validated = $request->validate([
            'provider' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:Verifikasi,Submitted,Pending,Paid',
            'submitted_at' => 'required|date',
        ]);

        $claim = \App\Models\Keuangan\FinClaim::findOrFail($id);
        $claim->update($validated);

        return redirect()->route('keuangan.klaim.index')->with('success', 'Data klaim berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $claim = \App\Models\Keuangan\FinClaim::findOrFail($id);
        $claim->delete();

        return redirect()->route('keuangan.klaim.index')->with('success', 'Data klaim berhasil dihapus!');
    }
}
