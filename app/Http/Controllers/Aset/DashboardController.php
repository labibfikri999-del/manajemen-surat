<?php

namespace App\Http\Controllers\Aset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function index()
    {
        // 1. Key Statistics
        $totalAssets = \App\Models\Aset\Aset::count();
        $totalValue = \App\Models\Aset\Aset::sum('price');
        $maintenanceNeeded = \App\Models\Aset\Aset::whereIn('condition', ['Rusak Ringan', 'Rusak Berat'])->count();
        
        // Active Loans (Peminjaman in current month)
        $activeLoans = \App\Models\Aset\AsetMutation::where('type', 'Peminjaman')
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->count();

        // 2. Chart Data: Assets by Category
        $categoryStats = \App\Models\Aset\Aset::selectRaw('category, count(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category');

        // 3. Recent Activities (Merged from multiple sources)
        $recentMutations = \App\Models\Aset\AsetMutation::with('aset')
            ->latest('date')
            ->take(3)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'mutation',
                    'title' => $item->type . ' - ' . $item->aset->name,
                    'description' => $item->notes ?? 'Dipindahkan ke ' . $item->destination_location,
                    'time' => $item->created_at,
                    'color' => 'blue',
                ];
            });

        $recentMaintenances = \App\Models\Aset\AsetMaintenance::with('aset')
            ->latest('scheduled_date')
            ->take(3)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'maintenance',
                    'title' => 'Maintenance - ' . $item->aset->name,
                    'description' => $item->description,
                    'time' => $item->created_at,
                    'color' => 'orange',
                ];
            });

        // Merge and sort
        $recentActivities = $recentMutations->concat($recentMaintenances)
            ->sortByDesc('time')
            ->take(5);

        // 4. Monthly Growth (Assets added per month this year)
        $monthlyGrowth = \App\Models\Aset\Aset::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->pluck('count', 'month');
            
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $monthlyGrowth->get($i, 0);
        }

        return view('aset.dashboard', compact(
            'totalAssets', 
            'totalValue', 
            'maintenanceNeeded', 
            'activeLoans',
            'recentActivities',
            'chartData',
            'categoryStats'
        ));
    }
}
