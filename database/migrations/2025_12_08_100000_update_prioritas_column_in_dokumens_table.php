<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Change enum to string to support "SEGERA", "AMAT SEGERA" and potential future values
        // This is safer than constantly updating the enum definition
        DB::statement('ALTER TABLE dokumens MODIFY COLUMN prioritas VARCHAR(50) NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Attempt to revert to original enum, but this might fail if data contains new values
        // So we might leave it as string or try-catch, but for this task logic:
        DB::statement("ALTER TABLE dokumens MODIFY COLUMN prioritas ENUM('BIASA', 'PENTING', 'MENDESAK') NULL");
    }
};
