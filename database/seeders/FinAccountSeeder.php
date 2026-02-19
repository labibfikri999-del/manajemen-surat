<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FinAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $accounts = [
            // Current Assets
            ['name' => 'Kas & Setara Kas', 'type' => 'asset_current', 'balance' => 450000000],
            ['name' => 'Piutang Usaha', 'type' => 'asset_current', 'balance' => 1200000000],
            ['name' => 'Persediaan', 'type' => 'asset_current', 'balance' => 450000000],

            // Fixed Assets
            ['name' => 'Bangunan & Gedung', 'type' => 'asset_fixed', 'balance' => 12000000000],
            ['name' => 'Peralatan Medis', 'type' => 'asset_fixed', 'balance' => 8500000000],
            ['name' => 'Kendaraan', 'type' => 'asset_fixed', 'balance' => 850000000],
            ['name' => 'Akumulasi Penyusutan', 'type' => 'asset_fixed', 'balance' => -1500000000],

            // Current Liabilities
            ['name' => 'Utang Usaha', 'type' => 'liability_short', 'balance' => 350000000],
            ['name' => 'Utang Gaji', 'type' => 'liability_short', 'balance' => 450000000],

            // Long Term Liabilities
            ['name' => 'Utang Bank', 'type' => 'liability_long', 'balance' => 4500000000],
            ['name' => 'Kewajiban Lain', 'type' => 'liability_long', 'balance' => 850000000],

            // Equity
            ['name' => 'Modal Yayasan', 'type' => 'equity', 'balance' => 12000000000],
            // Retained Earnings will be calculated dynamically or added here as a plug
            ['name' => 'Laba Ditahan', 'type' => 'equity', 'balance' => 5350000000], 
        ];

        foreach ($accounts as $acc) {
            \App\Models\Keuangan\FinAccount::updateOrCreate(
                ['name' => $acc['name']],
                $acc
            );
        }
    }
}
