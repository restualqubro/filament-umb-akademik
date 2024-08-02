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
        Schema::create('surat_pindah', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('surat_id')->references('id')->on('surat');
            $table->string('slip_bebasspp');
            $table->string('surat_pernyataan');
            $table->string('memo_perpus');            
            $table->foreignUlid('dosen_id')->references('id')->on('users')->nullable();
            $table->foreignUlid('admin_id')->references('id')->on('users')->nullable();
            $table->foreignUlid('wrektor_id')->references('id')->on('users')->nulalble();
            $table->string('no_surat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_pindah');
    }
};
