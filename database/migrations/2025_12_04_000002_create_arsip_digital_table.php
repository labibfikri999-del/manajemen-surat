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
        Schema::create('arsip_digital', function (Blueprint $table) {
            $table->id();
            $table->string('nama_dokumen')->nullable();
            $table->string('kategori')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('nama_file')->nullable();
            $table->string('file_path')->nullable();
            $table->string('tipe')->nullable();
            $table->string('ukuran')->nullable();
            $table->date('tanggal_upload')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsip_digital');
    }
};
