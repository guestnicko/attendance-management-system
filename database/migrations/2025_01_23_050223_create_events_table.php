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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('event_name');
            $table->date('date');
            $table->time('checkIn_start');
            $table->time('checkIn_end');
            $table->time('checkOut_start');
            $table->time('checkOut_end');
            $table->time('afternoon_checkIn_start')->nullable();
            $table->time('afternoon_checkIn_end')->nullable();
            $table->time('afternoon_checkOut_start')->nullable();
            $table->time('afternoon_checkOut_end')->nullable();
            $table->string('isWholeDay')->default('false');
            $table->string('event_status')->default('pending');
            $table->integer('fines_amount')->default(25);
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
