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
        Schema::create('active_mail', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('mail_id')->references('id')->on('mail');
            $table->string('leave_letter');
            $table->string('statement_letter');
            $table->string('full_payment_bill');            
            $table->foreignUlid('dosen_id')->references('id')->on('users')->nullable()->unsigned();
            $table->foreignUlid('kaprodi_id')->references('id')->on('users')->nullable()->unsigned();
            $table->foreignUlid('dekan_id')->references('id')->on('users')->nullable()->unsigned();
            $table->foreignUlid('wrektor_id')->references('id')->on('users')->nulalble()->unsigned();
            $table->string('mail_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('active_mail');
    }
};
