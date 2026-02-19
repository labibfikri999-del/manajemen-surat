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
        Schema::create('fin_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Kas Kecil", "Bank BSI", "Gedung"
            $table->enum('type', ['asset_current', 'asset_fixed', 'liability_short', 'liability_long', 'equity']);
            $table->bigInteger('balance')->default(0); // Current value
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fin_accounts');
    }
};
