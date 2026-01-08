<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Mock Data for "CFO" Dashboard Demonstration
        
        // 1. Financial Highlights
        $stats = [
            'pemasukan' => 'Rp 2.450.000.000',
            'pengeluaran' => 'Rp 1.120.000.000',
            'laba' => 'Rp 1.330.000.000', // Net Profit
            'pending_claims' => 'Rp 450.000.000', // Uang tertahan di asuransi/BPJS
        ];

        // 2. Pending Claims (Critical for Hospital Flow)
        $claims = [
            (object)['provider' => 'BPJS Kesehatan', 'amount' => 'Rp 320.000.000', 'status' => 'Verifikasi', 'days' => 45],
            (object)['provider' => 'Admedika', 'amount' => 'Rp 85.000.000', 'status' => 'Submitted', 'days' => 12],
            (object)['provider' => 'Prudential', 'amount' => 'Rp 45.000.000', 'status' => 'Pending', 'days' => 5],
        ];

        // 3. Department Budget Usage
        $budgets = [
            (object)['dept' => 'Farmasi (Obat)', 'used' => 85, 'limit' => 'Rp 500jt'],
            (object)['dept' => 'Logistik Umum', 'used' => 42, 'limit' => 'Rp 200jt'],
            (object)['dept' => 'Gaji & Honor', 'used' => 90, 'limit' => 'Rp 1.2M'],
        ];

        return view('keuangan.dashboard', compact('stats', 'claims', 'budgets'));
    }
}
