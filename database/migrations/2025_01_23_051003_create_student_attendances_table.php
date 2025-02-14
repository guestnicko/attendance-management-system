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
        Schema::create('student_attendances', function (Blueprint $table) {
            $table->id();
            // Morning attendance
            $table->time('attend_checkIn')->nullable();
            $table->time('attend_checkOut')->nullable();
            // Afternoon attendance
            $table->time('attend_afternoon_checkIn')->nullable();
            $table->time('attend_afternoon_checkOut')->nullable();
            $table->string('event_id');
            $table->string('student_rfid');
            $table->string('id_student');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_attendances');
    }
};
