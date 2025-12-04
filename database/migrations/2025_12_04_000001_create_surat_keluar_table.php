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
        Schema::create('surat_keluar', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->nullable();
            $table->date('tanggal_keluar')->nullable();
            $table->string('tujuan')->nullable();
            $table->string('perihal')->nullable();
            $table->string('file')->nullable();
            $table->string('status')->default('Draft');
            $table->foreignId('klasifikasi_id')->nullable()->constrained('klasifikasi')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_keluar');
    }
};
