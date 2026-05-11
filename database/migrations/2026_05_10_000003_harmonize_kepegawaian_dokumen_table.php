<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('sdm_transaksi_dokumens')) {
            return;
        }

        if (config('database.default') === 'sqlite') {
            return;
        }

        $nullableLegacyColumns = [
            'pengirim_type' => 'varchar(191) NULL',
            'pengirim_id' => 'bigint(20) unsigned NULL',
            'penerima_type' => 'varchar(191) NULL',
            'penerima_id' => 'bigint(20) unsigned NULL',
            'kategori_id' => 'bigint(20) unsigned NULL',
            'judul_dokumen' => 'varchar(191) NULL',
            'file_path' => 'varchar(191) NULL',
        ];

        foreach ($nullableLegacyColumns as $column => $definition) {
            if (Schema::hasColumn('sdm_transaksi_dokumens', $column)) {
                DB::statement("ALTER TABLE sdm_transaksi_dokumens MODIFY {$column} {$definition}");
            }
        }

        if (Schema::hasColumn('sdm_transaksi_dokumens', 'status')) {
            DB::statement("ALTER TABLE sdm_transaksi_dokumens MODIFY status varchar(50) NOT NULL DEFAULT 'draft'");
        }
    }

    public function down(): void
    {
        // Non-destructive: this table exists in multiple legacy shapes locally.
    }
};
