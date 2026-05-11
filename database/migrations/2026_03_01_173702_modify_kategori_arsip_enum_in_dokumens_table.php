<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (config('database.default') !== 'sqlite') {
            Schema::table('dokumens', function (Blueprint $table) {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE dokumens MODIFY COLUMN kategori_arsip ENUM('UMUM','SDM','ASSET','HUKUM','KEUANGAN','SURAT_KELUAR','SK','TIDAK_DIARSIPKAN')");
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (config('database.default') !== 'sqlite') {
            Schema::table('dokumens', function (Blueprint $table) {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE dokumens MODIFY COLUMN kategori_arsip ENUM('UMUM','SDM','ASSET','HUKUM','KEUANGAN','SURAT_KELUAR','SK')");
            });
        }
    }
};
