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
        Schema::create('fin_transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['pemasukan', 'pengeluaran']);
            $table->decimal('amount', 15, 2);
            $table->string('category'); // e.g., 'Rawat Inap', 'Listrik', 'Gaji'
            $table->text('description')->nullable();
            $table->date('transaction_date');
            $table->string('attachment')->nullable(); // Path to proof/receipt
            $table->foreignId('user_id')->constrained('users'); // Who inputted this
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fin_transactions');
    }
};
