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
        Schema::create('sdm_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sdm_pegawai_id')->constrained('sdm_pegawais')->onDelete('cascade');
            $table->string('nama_dokumen');
            $table->enum('kategori', ['Identitas', 'Pendidikan', 'Legalitas', 'Kompetensi', 'Lainnya'])->default('Lainnya');
            $table->string('file_path');
            $table->date('tgl_kadaluarsa')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sdm_documents');
    }
};
