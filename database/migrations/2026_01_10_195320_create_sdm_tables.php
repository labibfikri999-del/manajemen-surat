<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Pegawai
        Schema::create('sdm_pegawais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nip')->unique();
            $table->string('name');
            $table->string('role'); // Dokter, Perawat, Staff, dll
            $table->string('status')->default('active'); // active, inactive, leave
            $table->date('join_date')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });

        // 2. Shifts
        Schema::create('sdm_shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sdm_pegawai_id')->constrained('sdm_pegawais')->cascadeOnDelete();
            $table->string('shift_name'); // Pagi, Siang, Malam
            $table->time('start_time');
            $table->time('end_time');
            $table->date('date');
            $table->string('status')->default('Scheduled'); // On Duty, Scheduled, Completed, Absent
            $table->timestamps();
        });

        // 3. Attendance (Absensi)
        Schema::create('sdm_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sdm_pegawai_id')->constrained('sdm_pegawais')->cascadeOnDelete();
            $table->date('date');
            $table->time('clock_in')->nullable();
            $table->time('clock_out')->nullable();
            $table->string('status'); // Hadir, Telat, Alpha, Ijin
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 4. Leaves (Cuti/Ijin)
        Schema::create('sdm_leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sdm_pegawai_id')->constrained('sdm_pegawais')->cascadeOnDelete();
            $table->string('type'); // Cuti Tahunan, Sakit, Ijin
            $table->date('start_date');
            $table->date('end_date');
            $table->text('reason');
            $table->string('status')->default('Pending'); // Pending, Approved, Rejected
            $table->timestamps();
        });

        // 5. STR/SIP (Alerts)
        Schema::create('sdm_strs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sdm_pegawai_id')->constrained('sdm_pegawais')->cascadeOnDelete();
            $table->string('number');
            $table->string('type'); // STR, SIP
            $table->date('expiry_date');
            $table->string('document_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sdm_strs');
        Schema::dropIfExists('sdm_leaves');
        Schema::dropIfExists('sdm_attendances');
        Schema::dropIfExists('sdm_shifts');
        Schema::dropIfExists('sdm_pegawais');
    }
};
