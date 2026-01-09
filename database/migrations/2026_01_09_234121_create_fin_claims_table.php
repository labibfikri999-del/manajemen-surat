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
        Schema::create('fin_claims', function (Blueprint $table) {
            $table->id();
            $table->string('provider'); // BPJS, Prudential, etc.
            $table->decimal('amount', 15, 2);
            $table->enum('status', ['Verifikasi', 'Submitted', 'Pending', 'Paid'])->default('Pending');
            $table->date('submitted_at'); // To calc "days ago"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fin_claims');
    }
};
