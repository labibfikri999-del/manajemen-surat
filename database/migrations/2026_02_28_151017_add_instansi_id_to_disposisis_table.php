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
        Schema::table('disposisis', function (Blueprint $table) {
            if (!Schema::hasColumn('disposisis', 'surat_masuk_id')) {
                $table->foreignId('surat_masuk_id')->constrained('surat_masuk')->onDelete('cascade');
            }
            if (!Schema::hasColumn('disposisis', 'pengirim_id')) {
                $table->foreignId('pengirim_id')->constrained('users')->onDelete('cascade');
            }
            if (!Schema::hasColumn('disposisis', 'penerima_id')) {
                $table->foreignId('penerima_id')->nullable()->constrained('users')->onDelete('cascade');
            }
            if (!Schema::hasColumn('disposisis', 'instansi_id')) {
                $table->foreignId('instansi_id')->nullable()->constrained('instansis')->onDelete('cascade');
            }
            if (!Schema::hasColumn('disposisis', 'instruksi')) {
                $table->text('instruksi')->nullable();
            }
            if (!Schema::hasColumn('disposisis', 'batas_waktu')) {
                $table->date('batas_waktu')->nullable();
            }
            if (!Schema::hasColumn('disposisis', 'status')) {
                $table->string('status')->default('Menunggu'); 
            }
            if (!Schema::hasColumn('disposisis', 'catatan_balasan')) {
                $table->text('catatan_balasan')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('disposisis', function (Blueprint $table) {
            // Kita drop kolom di down() sebaiknya
            $columnsToDrop = ['surat_masuk_id', 'pengirim_id', 'penerima_id', 'instansi_id', 'instruksi', 'batas_waktu', 'status', 'catatan_balasan'];
            foreach (['surat_masuk_id', 'pengirim_id', 'penerima_id', 'instansi_id'] as $fk) {
                if (Schema::hasColumn('disposisis', $fk)) {
                    $table->dropForeign([$fk]);
                }
            }
            foreach ($columnsToDrop as $col) {
                if (Schema::hasColumn('disposisis', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
