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
        Schema::create('student', function (Blueprint $table) {            
            $table->id();
            $table->foreignUlid('user_id')->references('id')->on('users');                        
            $table->foreignUlid('lecture_id')->references('id')->on('users')->nullable();            
            $table->foreignId('program_data_id')->references('id')->on('program_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student');
    }
};
