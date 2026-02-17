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
        Schema::create('sdm_pendidikans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sdm_pegawai_id')->constrained('sdm_pegawais')->cascadeOnDelete();
            $table->string('jenjang'); // SD, SMP, SMA, D3, S1, S2, S3
            $table->string('institusi');
            $table->string('jurusan')->nullable();
            $table->year('tahun_lulus');
            $table->string('dokumen_path')->nullable(); // Upload ijazah
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sdm_pendidikans');
    }
};
