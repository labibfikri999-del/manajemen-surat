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
        Schema::table('dokumens', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['instansi_id']);
            
            // Make column nullable
            $table->unsignedBigInteger('instansi_id')->nullable()->change();
            
            // Re-add foreign key with set null on delete
            $table->foreign('instansi_id')->references('id')->on('instansis')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dokumens', function (Blueprint $table) {
            $table->dropForeign(['instansi_id']);
            $table->unsignedBigInteger('instansi_id')->nullable(false)->change();
            $table->foreign('instansi_id')->references('id')->on('instansis')->onDelete('cascade');
        });
    }
};
