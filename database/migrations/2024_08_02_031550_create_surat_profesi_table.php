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
        Schema::create('surat_profesi', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('surat_id')->references('id')->on('surat');
            $table->string('slip_beasspp');
            $table->string('surat_pernyataan');
            $table->string('memo_perpus');            
            $table->foreignUlid('dosen_id')->references('id')->on('users')->nullable()->unsigned();
            $table->foreignUlid('kaprodi_id')->references('id')->on('users')->nullable()->unsigned();
            $table->foreignUlid('dekan_id')->references('id')->on('users')->nullable()->unsigned();
            $table->foreignUlid('wrektor_id')->references('id')->on('users')->nulalble()->unsigned();
            $table->string('no_surat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_profesi');
    }
};
