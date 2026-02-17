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
        // 1. Master Jabatan
        Schema::create('sdm_master_jabatans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jabatan');
            $table->integer('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->unique(['nama_jabatan']); // Ensure unique names
            $table->timestamps();
        });

        // 2. Riwayat Jabatan
        Schema::create('sdm_riwayat_jabatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sdm_pegawai_id')->constrained('sdm_pegawais')->cascadeOnDelete();
            $table->foreignId('sdm_master_jabatan_id')->constrained('sdm_master_jabatans')->cascadeOnDelete();
            $table->string('kategori')->default('Fungsional'); // Fungsional, Struktural
            $table->string('homebase')->nullable();
            $table->date('tgl_mulai');
            $table->date('tgl_selesai')->nullable();
            $table->boolean('is_active')->default(false); // Status Aktif/Berakhir
            $table->string('dokumen_path')->nullable(); // SK Jabatan
            $table->timestamps();
        });

        // 3. Riwayat Pangkat / Golongan
        Schema::create('sdm_riwayat_pangkats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sdm_pegawai_id')->constrained('sdm_pegawais')->cascadeOnDelete();
            $table->string('golongan'); // III, IV, etc.
            $table->string('ruang'); // A, B, C, etc.
            $table->date('tmt'); // Terhitung Mulai Tanggal
            $table->string('dokumen_path')->nullable(); // SK Pangkat
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sdm_jabatan_tables');
    }
};
