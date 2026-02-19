<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function index()
    {
        $budgets = \App\Models\Keuangan\FinBudget::all();
        return view('keuangan.budget.index', compact('budgets'));
    }

    public function update(Request $request, $id)
    {
        $request->merge([
            'limit_amount' => str_replace(',', '', $request->input('limit_amount'))
        ]);
        
        $request->validate([
            'limit_amount' => 'required|numeric|min:0',
        ]);

        $budget = \App\Models\Keuangan\FinBudget::findOrFail($id);
        $budget->update([
            'limit_amount' => $request->limit_amount
        ]);

        return redirect()->route('keuangan.budget.index')->with('success', 'Anggaran berhasil diperbarui!');
    }
}
