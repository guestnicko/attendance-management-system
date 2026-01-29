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
            $table->foreignId('student_id')->constrained('students');
            $table->string('event_id');
            $table->foreignId("fee_id")->constrained('fees');
            // Morning attendance
            $table->dateTime('morning_check_in')->nullable();
            $table->dateTime('morning_check_out')->nullable();
            // Afternoon attendance
            $table->dateTime('afternoon_check_in')->nullable();
            $table->dateTime('afternoon_check_out')->nullable();
            $table->decimal("total_fines", 8, 2)->default(0);
            $table->date("attendance_date");
            $table->timestamps();
            $table->foreignId("updated_by")->constrained('users');
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
