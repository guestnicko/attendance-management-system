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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('s_rfid')->nullable();
            $table->string('s_studentID')->unique();
            $table->string('s_fname');
            $table->string('s_lname');
            $table->string('s_mname')->nullable();
            $table->string('s_suffix')->nullable();
            $table->enum('s_program', ['BSIS', 'BSIT']);
            $table->string('s_lvl');
            $table->string('s_set');
            $table->string('s_image')->nullable();
            $table->enum('s_status', ['ENROLLED', 'DROPPED', 'GRADUATED', 'TO BE UPDATED'])->default('TO BE UPDATED');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
