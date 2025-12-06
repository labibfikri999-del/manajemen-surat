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
        Schema::create('dokumens', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_dokumen', 100)->unique();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('file_path'); // Path file yang diupload
            $table->string('file_name'); // Nama file asli
            $table->string('file_type')->nullable(); // PDF, DOCX, dll
            $table->bigInteger('file_size')->nullable(); // Ukuran dalam bytes
            
            // Relasi
            $table->foreignId('instansi_id')->nullable()->constrained('instansis')->onDelete('set null');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Yang upload
            $table->foreignId('validated_by')->nullable()->constrained('users')->onDelete('set null'); // Direktur yang validasi
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null'); // Staff yang proses
            
            // Status tracking
            $table->enum('status', ['pending', 'review', 'disetujui', 'ditolak', 'diproses', 'selesai'])->default('pending');
            $table->text('catatan_validasi')->nullable(); // Catatan dari direktur
            $table->text('catatan_proses')->nullable(); // Catatan dari staff
            $table->timestamp('tanggal_validasi')->nullable();
            $table->timestamp('tanggal_proses')->nullable();
            $table->timestamp('tanggal_selesai')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumens');
    }
};
