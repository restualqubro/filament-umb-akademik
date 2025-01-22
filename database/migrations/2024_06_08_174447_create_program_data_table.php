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
        Schema::create('program_data', function (Blueprint $table) {
            $table->id();            
            $table->string('code', 7);
            $table->foreignId('faculty_data_id')->references('id')->on('faculty_data');
            $table->string('program_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_data');
    }
};
