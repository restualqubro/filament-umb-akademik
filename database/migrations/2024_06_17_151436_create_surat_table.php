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
        Schema::create('surat', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('mahasiswa_id')->references('id')->on('users');
            $table->string('akademik_id')->references('code')->on('tahunakademik');
            $table->foreignUlid('operator_id')->references('id')->on('users')->nullable();
            $table->string('update_detail')->nullable();
            $table->enum('status', ['Baru', 'Checked', 'Verifikasi', 'Validasi', 'Disetujui', 'Ditolak', 'Perbaikan']);
            $table->enum('jenis', ['1', '2', '3', '4', '5']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat');
    }
};
