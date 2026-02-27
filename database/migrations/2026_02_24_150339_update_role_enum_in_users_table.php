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
            // Because SQLite does not support altering Enum, we handle it conditionally
            if (config('database.default') !== 'sqlite') {
                // Drop the old column and create it again as string because ENUM modifications via Doctrine DBAL are risky in older/some Laravel versions, or we just typecast it to string
                $table->string('role')->default('instansi')->change();
            } else {
                 // For SQLite, just change to string
                 $table->string('role')->default('instansi')->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
             if (config('database.default') !== 'sqlite') {
                 // Convert back to enum if necessary, or leave as string
                 // $table->enum('role', ['direktur', 'staff', 'instansi'])->default('instansi')->change();
             }
        });
    }
};
