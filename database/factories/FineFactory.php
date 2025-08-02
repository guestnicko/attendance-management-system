<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Fine>
 */
class FineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $event = Event::factory()->create();
        $student = Student::factory()->enrolled()->create();

        $fineAmount = 25; // Default fine amount
        $morningCheckInMissed = fake()->boolean(30);
        $morningCheckOutMissed = fake()->boolean(20);
        $afternoonCheckInMissed = fake()->boolean(25);
        $afternoonCheckOutMissed = fake()->boolean(15);

        $totalFines = 0;
        if ($morningCheckInMissed) $totalFines += $fineAmount;
        if ($morningCheckOutMissed) $totalFines += $fineAmount;
        if ($afternoonCheckInMissed) $totalFines += $fineAmount;
        if ($afternoonCheckOutMissed) $totalFines += $fineAmount;

        return [
            'event_id' => $event->id,
            'student_id' => $student->id,
            'fines_amount' => $fineAmount,
            'morning_checkIn_missed' => $morningCheckInMissed,
            'morning_checkOut_missed' => $morningCheckOutMissed,
            'afternoon_checkIn_missed' => $afternoonCheckInMissed,
            'afternoon_checkOut_missed' => $afternoonCheckOutMissed,
            'total_fines' => $totalFines,
        ];
    }

    /**
     * Indicate that the student has morning violations.
     */
    public function morningViolations(): static
    {
        return $this->state(fn(array $attributes) => [
            'morning_checkIn_missed' => true,
            'morning_checkOut_missed' => fake()->boolean(50),
            'afternoon_checkIn_missed' => false,
            'afternoon_checkOut_missed' => false,
            'total_fines' => 25 + (fake()->boolean(50) ? 25 : 0),
        ]);
    }

    /**
     * Indicate that the student has afternoon violations.
     */
    public function afternoonViolations(): static
    {
        return $this->state(fn(array $attributes) => [
            'morning_checkIn_missed' => false,
            'morning_checkOut_missed' => false,
            'afternoon_checkIn_missed' => true,
            'afternoon_checkOut_missed' => fake()->boolean(50),
            'total_fines' => 25 + (fake()->boolean(50) ? 25 : 0),
        ]);
    }

    /**
     * Indicate that the student has no violations.
     */
    public function noViolations(): static
    {
        return $this->state(fn(array $attributes) => [
            'morning_checkIn_missed' => false,
            'morning_checkOut_missed' => false,
            'afternoon_checkIn_missed' => false,
            'afternoon_checkOut_missed' => false,
            'total_fines' => 0,
        ]);
    }
}
