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
        Schema::create('sdm_keluargas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sdm_pegawai_id')->constrained('sdm_pegawais', 'id')->onDelete('cascade');
            $table->string('nama');
            $table->string('hubungan'); // Suami, Istri, Anak, Orang Tua, dll
            $table->date('tgl_lahir');
            $table->string('pekerjaan')->nullable();
            $table->string('dokumen_path')->nullable(); // Dokumen pendukung (KK, Akta)
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sdm_keluargas');
    }
};
