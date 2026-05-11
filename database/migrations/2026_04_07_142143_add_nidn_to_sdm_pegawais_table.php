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
        Schema::table('sdm_pegawais', function (Blueprint $table) {
            if (!Schema::hasColumn('sdm_pegawais', 'nidn')) {
                $table->string('nidn')->nullable()->after('nip');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sdm_pegawais', function (Blueprint $table) {
            if (Schema::hasColumn('sdm_pegawais', 'nidn')) {
                $table->dropColumn('nidn');
            }
        });
    }
};
