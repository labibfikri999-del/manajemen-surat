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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['direktur', 'staff', 'instansi'])->default('instansi')->after('email');
            $table->foreignId('instansi_id')->nullable()->after('role')->constrained('instansis')->onDelete('set null');
            $table->string('jabatan')->nullable()->after('instansi_id');
            $table->string('telepon')->nullable()->after('jabatan');
            $table->string('avatar')->nullable()->after('telepon');
            $table->boolean('is_active')->default(true)->after('avatar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['instansi_id']);
            $table->dropColumn(['role', 'instansi_id', 'jabatan', 'telepon', 'avatar', 'is_active']);
        });
    }
};
