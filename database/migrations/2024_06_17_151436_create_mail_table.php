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
        Schema::create('mail', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('student_id')->references('id')->on('users');
            $table->string('academic_year_id')->references('code')->on('academic_year');
            $table->foreignUlid('operator_id')->references('id')->on('users')->nullable();
            $table->string('latest_updates')->nullable();
            $table->enum('approval_status', ['New', 'Checked', 'Verification', 'Validation', 'Approved', 'Rejected', 'Correction']);
            $table->string('approval_note')->nullable();
            $table->enum('mail_type', ['Cuti', 'Aktif dari Cuti', 'Pindah Perguruan Tinggi', 'Mengundurkan Diri', 'Tidak Lanjut Profesi']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mail');
    }
};
