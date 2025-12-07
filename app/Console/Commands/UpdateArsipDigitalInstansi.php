<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ArsipDigital;
use App\Models\User;

class UpdateArsipDigitalInstansi extends Command
{
    protected $signature = 'arsipdigital:update-instansi';
    protected $description = 'Update kolom instansi_id pada arsip_digital berdasarkan user_id jika ada';

    public function handle()
    {
        $updated = 0;
        $arsipList = ArsipDigital::whereNull('instansi_id')->get();
        foreach ($arsipList as $arsip) {
            // Jika ada user_id di arsip digital, update instansi_id
            if (!empty($arsip->user_id)) {
                $user = User::find($arsip->user_id);
                if ($user && $user->instansi_id) {
                    $arsip->instansi_id = $user->instansi_id;
                    $arsip->save();
                    $updated++;
                }
            }
        }
        $this->info("Update selesai. $updated arsip digital diupdate instansi_id-nya.");
    }
}
