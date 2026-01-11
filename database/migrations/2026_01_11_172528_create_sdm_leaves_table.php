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
        if (!Schema::hasTable('sdm_leaves')) {
            Schema::create('sdm_leaves', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sdm_pegawai_id')->constrained('sdm_pegawais')->onDelete('cascade');
                $table->enum('type', ['Tahunan', 'Sakit', 'Izin', 'Lainnya']);
                $table->date('start_date');
                $table->date('end_date');
                $table->text('reason');
                $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
                $table->text('rejection_reason')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sdm_leaves');
    }
};
