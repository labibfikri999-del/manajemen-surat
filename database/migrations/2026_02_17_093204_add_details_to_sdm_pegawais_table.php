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
            $table->string('nidn')->nullable()->after('nip');
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->after('name');
            $table->string('pendidikan_terakhir')->nullable()->after('jenis_kelamin');
            $table->string('status_kepegawaian')->default('Tetap')->after('status');
            $table->string('jabatan')->nullable()->after('role');
            $table->string('unit_kerja')->nullable()->after('jabatan');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sdm_pegawais', function (Blueprint $table) {
            $table->dropColumn([
                'nidn',
                'jenis_kelamin',
                'pendidikan_terakhir',
                'status_kepegawaian',
                'jabatan',
                'unit_kerja'
            ]);
        });
    }
};
