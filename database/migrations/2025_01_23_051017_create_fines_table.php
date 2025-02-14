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
        Schema::create('fines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_id')->references('id')->on('student_attendances')->nullable();
            $table->foreignId('event_id')->references('id')->on('events');
            $table->string('student_id')->references('id')->on('students');
            $table->decimal('fine_amount', 8, 2);
            $table->boolean('morning_checkIn_missed')->default(false);
            $table->boolean('morning_checkOut_missed')->default(false);
            $table->boolean('afternoon_checkIn_missed')->default(false);
            $table->boolean('afternoon_checkOut_missed')->default(false);
            $table->decimal('total_fines', 8, 2);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fines');
    }
};
