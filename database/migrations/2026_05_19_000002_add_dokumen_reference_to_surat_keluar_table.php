<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat_keluar', function (Blueprint $table) {
            $table->foreignId('dokumen_id')->nullable()->after('id')->constrained('dokumens')->cascadeOnDelete();
            $table->string('broadcast_group_id', 64)->nullable()->index()->after('dokumen_id');
        });
    }

    public function down(): void
    {
        Schema::table('surat_keluar', function (Blueprint $table) {
            $table->dropForeign(['dokumen_id']);
            $table->dropColumn(['dokumen_id', 'broadcast_group_id']);
        });
    }
};
