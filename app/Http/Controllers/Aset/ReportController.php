<?php

namespace App\Http\Controllers\Aset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Aset\Aset;
use App\Models\Aset\AsetMutation;
use App\Models\Aset\AsetMaintenance;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        // Simple stats for the report dashboard
        $stats = [
            'total_asset' => Aset::count(),
            'total_value' => Aset::sum('price'),
            'total_maintenance_cost' => AsetMaintenance::sum('cost'),
            'maintenance_count' => AsetMaintenance::count(),
        ];

        // Recent activities
        $recentMutations = AsetMutation::with('aset')->latest()->limit(5)->get();
        $recentMaintenances = AsetMaintenance::with('aset')->latest()->limit(5)->get();

        return view('aset.report.index', compact('stats', 'recentMutations', 'recentMaintenances'));
    }
}
