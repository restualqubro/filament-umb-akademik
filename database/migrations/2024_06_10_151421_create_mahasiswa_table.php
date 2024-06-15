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
        Schema::create('mahasiswa', function (Blueprint $table) {            
            $table->id();
            $table->foreignUlid('user_id')->references('id')->on('users');
            $table->char('nik', 20)->nullable();
            $table->string('pddikti')->nullable();
            $table->foreignUlid('dosen_id')->references('id')->on('users')->nullable();            
            $table->foreignUlid('prodi_id')->references('id')->on('prodi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
