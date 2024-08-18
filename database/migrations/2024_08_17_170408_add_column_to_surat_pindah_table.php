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
        Schema::table('surat_pindah', function (Blueprint $table) {
            $table->enum('jenis', ['Universitas', 'Politeknik', 'Sekolah Tinggi']);
            $table->string('tujuan');
            $table->string('alasan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_pindah', function (Blueprint $table) {            
        });
    }
};
