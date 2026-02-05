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
        Schema::create('event_settings', function (Blueprint $table) {
            $table->id("event_setting_id");
            $table->foreignId('insti_attend_event_id')->constrained('insti_attend_events')->onDelete('cascade');
            $table->date('date');
            $table->time('checkIn_start');
            $table->time('checkIn_end');
            $table->time('checkOut_start');
            $table->time('checkOut_end');
            $table->time('afternoon_checkIn_start')->nullable();
            $table->time('afternoon_checkIn_end')->nullable();
            $table->time('afternoon_checkOut_start')->nullable();
            $table->time('afternoon_checkOut_end')->nullable();
            $table->string('event_status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_settings');
    }
};
