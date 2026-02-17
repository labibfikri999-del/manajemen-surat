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
            // Check if columns exist before adding (safeguard) or just add matches to design
            if (!Schema::hasColumn('sdm_pegawais', 'tempat_lahir')) {
                $table->string('tempat_lahir')->nullable()->after('jenis_kelamin');
            }
            if (!Schema::hasColumn('sdm_pegawais', 'tanggal_lahir')) {
                $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            }
            if (!Schema::hasColumn('sdm_pegawais', 'alamat_lengkap')) {
                $table->text('alamat_lengkap')->nullable()->after('email');
            }
            if (!Schema::hasColumn('sdm_pegawais', 'foto')) {
                $table->string('foto')->nullable()->after('user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sdm_pegawais', function (Blueprint $table) {
            //
        });
    }
};
