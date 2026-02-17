<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SdmSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'company_name', 'value' => 'RS Islam NTB', 'type' => 'text', 'description' => 'Nama Instansi'],
            ['key' => 'company_address', 'value' => 'Jl. Kesehatan No. 10, Mataram, Nusa Tenggara Barat', 'type' => 'text', 'description' => 'Alamat Lengkap'],
            ['key' => 'company_logo', 'value' => null, 'type' => 'text', 'description' => 'Logo Instansi'],
            ['key' => 'work_start_time', 'value' => '08:00', 'type' => 'time', 'description' => 'Jam Masuk'],
            ['key' => 'work_end_time', 'value' => '16:00', 'type' => 'time', 'description' => 'Jam Pulang'],
            ['key' => 'payroll_tax_rate', 'value' => '0', 'type' => 'number', 'description' => 'Pajak PPh 21 (%)'],
            ['key' => 'bpjs_rate', 'value' => '0', 'type' => 'number', 'description' => 'Potongan BPJS (%)'],
        ];

        foreach ($settings as $setting) {
            \App\Models\SDM\SdmSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
