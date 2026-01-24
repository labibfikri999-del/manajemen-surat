<?php

namespace App\Http\Controllers\Aset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Aset\Aset;
use App\Models\Aset\AsetMaintenance;
use App\Http\Requests\Aset\StoreMaintenanceRequest;
use App\Http\Requests\Aset\UpdateMaintenanceRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MaintenanceController extends Controller
{
    public function index(): View
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

    public function create(): View
    {
        $asets = Aset::orderBy('name')->get();
        return view('aset.maintenance.create', compact('asets'));
    }

    public function store(StoreMaintenanceRequest $request): RedirectResponse
    {
        // Validation is handled by StoreMaintenanceRequest

        $data = [
            'aset_id' => $request->aset_id,
            'description' => $request->description,
            'cost' => $request->cost ?? 0,
            'status' => $request->status,
            'scheduled_date' => $request->scheduled_date,
            'vendor' => $request->vendor,
        ];

        // Set completion_date if status is Completed
        if ($request->status === 'Completed') {
            $data['completion_date'] = now();
        }

        AsetMaintenance::create($data);

        return redirect()->route('aset.maintenance.index')->with('success', 'Jadwal maintenance berhasil dibuat.');
    }
    
    public function update(UpdateMaintenanceRequest $request, int $id): RedirectResponse
    {
        $maintenance = AsetMaintenance::findOrFail($id);
        
        $data = $request->validated();
        
        // Set completion_date if status is changed to Completed and it wasn't completed before
        if ($request->status === 'Completed' && $maintenance->status !== 'Completed') {
            $data['completion_date'] = now();
        }
        
        $maintenance->update($data);
        return back()->with('success', 'Status maintenance diperbarui.');
    }
        
    public function destroy(int $id): RedirectResponse
    {
        AsetMaintenance::findOrFail($id)->delete();
        return back()->with('success', 'Data maintenance dihapus.');
    }
}
