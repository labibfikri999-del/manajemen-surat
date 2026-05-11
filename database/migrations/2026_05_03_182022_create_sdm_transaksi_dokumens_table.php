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
        Schema::create('sdm_transaksi_dokumens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('sdm_pegawai_id')->nullable()->constrained('sdm_pegawais')->nullOnDelete();
            $table->foreignId('sdm_kategori_dokumen_id')->constrained('sdm_kategori_dokumens')->restrictOnDelete();
            $table->string('nomor_pengajuan')->unique();
            $table->string('judul');
            $table->text('catatan_pegawai')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_type', 50)->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('status')->default('draft');
            $table->text('catatan_staff')->nullable();
            $table->text('catatan_sekjen')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('archived_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index(['sdm_pegawai_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sdm_transaksi_dokumens');
    }
};
