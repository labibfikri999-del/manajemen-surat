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
        // 1. Tabel Utama: Inventaris Aset
        if (!Schema::hasTable('asets')) {
            Schema::create('asets', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique(); // Kode Aset (e.g., AST-2024-001)
                $table->string('name');
                $table->string('category'); // Elektronik, Furniture, Kendaraan, dll
                $table->string('brand')->nullable();
                $table->string('model')->nullable();
                $table->string('location'); // Ruang Server, Lobby, dll
                $table->enum('condition', ['Baik', 'Rusak Ringan', 'Rusak Berat'])->default('Baik');
                $table->date('purchase_date')->nullable();
                $table->decimal('price', 15, 2)->default(0); // Harga Beli
                $table->string('photo')->nullable(); // Path foto aset
                $table->text('notes')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        }

        // 2. Tabel Mutasi (Perpindahan & Peminjaman)
        if (!Schema::hasTable('aset_mutations')) {
            Schema::create('aset_mutations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('aset_id')->constrained('asets')->onDelete('cascade');
                $table->enum('type', ['Mutasi', 'Peminjaman', 'Pengembalian']);
                $table->string('person_in_charge'); // Penanggung Jawab / Peminjam
                $table->string('origin_location')->nullable();
                $table->string('destination_location');
                $table->date('date');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 3. Tabel Maintenance (Perbaikan/Servis)
        if (!Schema::hasTable('aset_maintenances')) {
            Schema::create('aset_maintenances', function (Blueprint $table) {
                $table->id();
                $table->foreignId('aset_id')->constrained('asets')->onDelete('cascade');
                $table->string('description'); // Jenis Perbaikan
                $table->decimal('cost', 15, 2)->default(0);
                $table->enum('status', ['Scheduled', 'In Progress', 'Completed', 'Cancelled'])->default('Scheduled');
                $table->date('scheduled_date');
                $table->date('completion_date')->nullable();
                $table->string('vendor')->nullable(); // Nama bengkel/teknisi
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aset_maintenances');
        Schema::dropIfExists('aset_mutations');
        Schema::dropIfExists('asets');
    }
};
