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
            $table->string('username')->unique()->nullable()->after('name');
        });

        // Generate username for existing users
        $users = \App\Models\User::all();
        foreach ($users as $user) {
            $username = explode('@', $user->email)[0];
            // Ensure uniqueness simply for existing data (naive approach)
            $count = \App\Models\User::where('username', $username)->count();
            if ($count > 0) {
                $username .= rand(100, 999);
            }

            // Use DB query to avoid model strictness if model is not yet updated
            \Illuminate\Support\Facades\DB::table('users')
                ->where('id', $user->id)
                ->update(['username' => $username]);
        }

        // Make it not nullable after population
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
        });
    }
};
