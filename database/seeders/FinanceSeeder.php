<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Keuangan\FinClaim;
use App\Models\Keuangan\FinBudget;
use Carbon\Carbon;

class FinanceSeeder extends Seeder
{
    public function run()
    {
        // 1. Claims
        FinClaim::create([
            'provider' => 'BPJS Kesehatan',
            'amount' => 320000000,
            'status' => 'Verifikasi',
            'submitted_at' => Carbon::now()->subDays(45)
        ]);
        
        FinClaim::create([
            'provider' => 'Admedika',
            'amount' => 85000000,
            'status' => 'Submitted',
            'submitted_at' => Carbon::now()->subDays(12)
        ]);

        FinClaim::create([
            'provider' => 'Prudential',
            'amount' => 45000000,
            'status' => 'Pending',
            'submitted_at' => Carbon::now()->subDays(5)
        ]);

        // 2. Budgets (Categories matches Transaction categories)
        FinBudget::create(['department' => 'Obat & Farmasi', 'limit_amount' => 500000000]); // 500jt
        FinBudget::create(['department' => 'Logistik Umum', 'limit_amount' => 200000000]); // 200jt
        FinBudget::create(['department' => 'Gaji & Honor', 'limit_amount' => 1200000000]); // 1.2M
    }
}
