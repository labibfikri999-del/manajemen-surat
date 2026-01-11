<?php

namespace App\Http\Controllers\Aset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Aset\Aset;
use App\Models\Aset\AsetMaintenance;

class MaintenanceController extends Controller
{
    public function index()
    {
        $maintenances = AsetMaintenance::with('aset')
            ->orderBy('scheduled_date', 'desc')
            ->paginate(15);
            
        $stats = [
            'scheduled' => AsetMaintenance::where('status', 'Scheduled')->count(),
            'in_progress' => AsetMaintenance::where('status', 'In Progress')->count(),
            'completed' => AsetMaintenance::where('status', 'Completed')->count(),
            'total_cost' => AsetMaintenance::sum('cost'),
        ];

        return view('aset.maintenance.index', compact('maintenances', 'stats'));
    }

    public function create()
    {
        $asets = Aset::orderBy('name')->get();
        return view('aset.maintenance.create', compact('asets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'aset_id' => 'required',
            'description' => 'required',
            'scheduled_date' => 'required|date',
            'status' => 'required',
        ]);

        AsetMaintenance::create([
            'aset_id' => $request->aset_id,
            'description' => $request->description,
            'cost' => $request->cost ?? 0,
            'status' => $request->status,
            'scheduled_date' => $request->scheduled_date,
            'vendor' => $request->vendor,
        ]);

        return redirect()->route('aset.maintenance.index')->with('success', 'Jadwal maintenance berhasil dibuat.');
    }
    
    public function update(Request $request, $id)
    {
        // Simple status update logic if needed
        $maintenance = AsetMaintenance::findOrFail($id);
        $maintenance->update($request->all());
        return back()->with('success', 'Status maintenance diperbarui.');
    }
        
    public function destroy($id)
    {
        AsetMaintenance::findOrFail($id)->delete();
        return back()->with('success', 'Data maintenance dihapus.');
    }
}
