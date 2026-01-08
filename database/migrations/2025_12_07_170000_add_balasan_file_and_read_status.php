<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBalasanFileAndReadStatus extends Migration
{
    public function up()
    {
        Schema::table('dokumens', function (Blueprint $table) {
            $table->string('balasan_file')->nullable();
        });
        Schema::create('balasan_read_status', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dokumen_id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('terbaca')->default(false);
            $table->timestamps();
            $table->foreign('dokumen_id')->references('id')->on('dokumens')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('dokumens', function (Blueprint $table) {
            $table->dropColumn('balasan_file');
        });
        Schema::dropIfExists('balasan_read_status');
    }
}
