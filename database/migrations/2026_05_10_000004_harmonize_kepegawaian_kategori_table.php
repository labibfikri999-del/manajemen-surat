<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('sdm_kategori_dokumens') || config('database.default') === 'sqlite') {
            return;
        }

        if (Schema::hasColumn('sdm_kategori_dokumens', 'nama_kategori')) {
            DB::statement('ALTER TABLE sdm_kategori_dokumens MODIFY nama_kategori varchar(191) NULL');
        }
    }

    public function down(): void
    {
        // Non-destructive for mixed legacy schemas.
    }
};
