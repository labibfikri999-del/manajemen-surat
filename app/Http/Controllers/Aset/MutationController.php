<?php

namespace App\Http\Controllers\Aset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Aset\Aset;
use App\Models\Aset\AsetMutation;
use App\Http\Requests\Aset\StoreMutationRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MutationController extends Controller
{
    public function index(): View
    {
        $mutations = AsetMutation::with('aset')->orderBy('created_at', 'desc')->paginate(15);
        
        $stats = [
            'mutasi' => AsetMutation::where('type', 'Mutasi')->count(),
            'peminjaman' => AsetMutation::where('type', 'Peminjaman')->count(),
            'recent' => AsetMutation::where('created_at', '>=', now()->subDays(7))->count(),
        ];

        return view('aset.mutation.index', compact('mutations', 'stats'));
    }

    public function create(): View
    {
        $asets = Aset::orderBy('name')->get();
        return view('aset.mutation.create', compact('asets'));
    }

    public function store(StoreMutationRequest $request): RedirectResponse
    {
        // Validation is handled by StoreMutationRequest

        $aset = Aset::findOrFail($request->aset_id);

        AsetMutation::create([
            'aset_id' => $request->aset_id,
            'type' => $request->type,
            'person_in_charge' => $request->person_in_charge,
            'origin_location' => $aset->location, // Save current location as origin
            'destination_location' => $request->destination_location,
            'date' => $request->date,
            'notes' => $request->notes,
        ]);

        // Update Aset Location if type is Mutation
        if ($request->type === 'Mutasi') {
            $aset->update(['location' => $request->destination_location]);
        }

        return redirect()->route('aset.mutation.index')->with('success', 'Mutasi/Peminjaman berhasil dicatat.');
    }
}
