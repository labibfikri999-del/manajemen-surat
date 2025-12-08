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
        // Add instansi_id to surat_masuk table
        Schema::table('surat_masuk', function (Blueprint $table) {
            $table->unsignedBigInteger('instansi_id')->nullable()->after('id');
            $table->foreign('instansi_id')->references('id')->on('instansis')->onDelete('cascade');
        });

        // Add instansi_id to surat_keluar table
        Schema::table('surat_keluar', function (Blueprint $table) {
            $table->unsignedBigInteger('instansi_id')->nullable()->after('id');
            $table->foreign('instansi_id')->references('id')->on('instansis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_masuk', function (Blueprint $table) {
            $table->dropForeign(['instansi_id']);
            $table->dropColumn('instansi_id');
        });

        Schema::table('surat_keluar', function (Blueprint $table) {
            $table->dropForeign(['instansi_id']);
            $table->dropColumn('instansi_id');
        });
    }
};
