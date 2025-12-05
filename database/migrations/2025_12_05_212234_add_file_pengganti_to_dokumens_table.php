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
            $table->string('file_pengganti_path')->nullable()->after('file_size');
            $table->string('file_pengganti_name')->nullable()->after('file_pengganti_path');
            $table->string('file_pengganti_type')->nullable()->after('file_pengganti_name');
            $table->integer('file_pengganti_size')->nullable()->after('file_pengganti_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dokumens', function (Blueprint $table) {
            $table->dropColumn(['file_pengganti_path', 'file_pengganti_name', 'file_pengganti_type', 'file_pengganti_size']);
        });
    }
};
