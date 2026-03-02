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
        Schema::table('dokumens', function (Blueprint $table) {
        // Using raw statement for ENUM modification since Doctrine DBAL doesn't support ENUM natively
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE dokumens MODIFY COLUMN kategori_arsip ENUM('UMUM','SDM','ASSET','HUKUM','KEUANGAN','SURAT_KELUAR','SK','TIDAK_DIARSIPKAN')");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dokumens', function (Blueprint $table) {
        // Reverting the column back to original ENUM values (be careful of data loss if 'TIDAK_DIARSIPKAN' was heavily used)
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE dokumens MODIFY COLUMN kategori_arsip ENUM('UMUM','SDM','ASSET','HUKUM','KEUANGAN','SURAT_KELUAR','SK')");
        });
    }
};
