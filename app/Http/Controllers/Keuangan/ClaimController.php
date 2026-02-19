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
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xlsx,zip,rar|max:10240'
        ]);

        $data = $validated;
        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('finance_claims', 'public');
        }

        $claim = \App\Models\Keuangan\FinClaim::create($data);

        // Log creation
        \App\Models\Keuangan\FinClaimLog::create([
            'fin_claim_id' => $claim->id,
            'status' => $claim->status,
            'notes' => 'Klaim baru dibuat',
            'user_id' => auth()->id()
        ]);

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
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xlsx,zip,rar|max:10240'
        ]);

        $claim = \App\Models\Keuangan\FinClaim::findOrFail($id);
        
        $data = $validated;
        if ($request->hasFile('attachment')) {
            // Delete old file if exists
            if ($claim->attachment) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($claim->attachment);
            }
            $data['attachment'] = $request->file('attachment')->store('finance_claims', 'public');
        }

        if ($claim->status != $data['status']) {
             \App\Models\Keuangan\FinClaimLog::create([
                'fin_claim_id' => $claim->id,
                'status' => $data['status'],
                'notes' => 'Status diubah ke ' . $data['status'],
                'user_id' => auth()->id()
            ]);
        }

        $claim->update($data);

        return redirect()->route('keuangan.klaim.index')->with('success', 'Data klaim berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $claim = \App\Models\Keuangan\FinClaim::findOrFail($id);
        if ($claim->attachment) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($claim->attachment);
        }
        $claim->delete();

        return redirect()->route('keuangan.klaim.index')->with('success', 'Data klaim berhasil dihapus!');
    }
}
