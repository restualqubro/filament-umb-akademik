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
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('user_id')->references('id')->on('users')->nullable();
            $table->ulid('prodi_id')->unique()->nullable();
            $table->foreign('prodi_id')->references('id')->on('prodi');
            $table->ulid('fakultas_id')->unique()->nullable();
            $table->foreign('fakultas_id')->references('id')->on('fakultas');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};
