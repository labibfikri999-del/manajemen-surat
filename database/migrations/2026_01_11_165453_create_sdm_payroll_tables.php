<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add salary info to sdm_pegawais
        Schema::table('sdm_pegawais', function (Blueprint $table) {
            $table->decimal('gaji_pokok', 15, 2)->default(0)->after('status');
            $table->decimal('tunjangan', 15, 2)->default(0)->after('gaji_pokok');
        });

        // Create Payrolls table
        Schema::create('sdm_payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sdm_pegawai_id')->constrained('sdm_pegawais')->cascadeOnDelete();
            $table->integer('month');
            $table->integer('year');
            $table->decimal('basic_salary', 15, 2);
            $table->decimal('allowances', 15, 2)->default(0);
            $table->decimal('deductions', 15, 2)->default(0);
            $table->decimal('net_salary', 15, 2);
            $table->string('status')->default('Pending'); // Pending, Paid
            $table->date('payment_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sdm_payrolls');
        
        Schema::table('sdm_pegawais', function (Blueprint $table) {
            $table->dropColumn(['gaji_pokok', 'tunjangan']);
        });
    }
};
