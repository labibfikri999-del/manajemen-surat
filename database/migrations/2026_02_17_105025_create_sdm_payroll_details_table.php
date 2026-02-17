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
        Schema::create('sdm_payroll_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sdm_payroll_id')->constrained('sdm_payrolls')->cascadeOnDelete();
            $table->string('component_name'); // e.g., 'Gaji Pokok', 'Tunjangan Transport', 'Potongan BPJS'
            $table->enum('type', ['earning', 'deduction']);
            $table->decimal('amount', 15, 2);
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sdm_payroll_details');
    }
};
