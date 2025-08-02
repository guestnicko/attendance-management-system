<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StudentAttendance>
 */
class StudentAttendanceFactory extends Factory
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

        $hasMorningAttendance = fake()->boolean(90); // 90% chance of morning attendance
        $hasAfternoonAttendance = fake()->boolean(70); // 70% chance of afternoon attendance

        $morningCheckIn = null;
        $morningCheckOut = null;
        $afternoonCheckIn = null;
        $afternoonCheckOut = null;

        if ($hasMorningAttendance) {
            $morningCheckIn = fake()->time('H:i:s', $event->checkIn_start);
            $morningCheckOut = fake()->boolean(80) ? fake()->time('H:i:s', $event->checkOut_start) : null;
        }

        if ($hasAfternoonAttendance && $event->afternoon_checkIn_start) {
            $afternoonCheckIn = fake()->time('H:i:s', $event->afternoon_checkIn_start);
            $afternoonCheckOut = fake()->boolean(80) ? fake()->time('H:i:s', $event->afternoon_checkOut_start) : null;
        }

        return [
            'attend_checkIn' => $morningCheckIn,
            'attend_checkOut' => $morningCheckOut,
            'attend_afternoon_checkIn' => $afternoonCheckIn,
            'attend_afternoon_checkOut' => $afternoonCheckOut,
            'event_id' => $event->id,
            'student_rfid' => $student->s_rfid,
            'id_student' => $student->id,
        ];
    }

    /**
     * Indicate that the student has complete morning attendance.
     */
    public function completeMorning(): static
    {
        return $this->state(fn(array $attributes) => [
            'attend_checkIn' => fake()->time('H:i:s', '08:30:00'),
            'attend_checkOut' => fake()->time('H:i:s', '17:00:00'),
        ]);
    }

    /**
     * Indicate that the student has complete afternoon attendance.
     */
    public function completeAfternoon(): static
    {
        return $this->state(fn(array $attributes) => [
            'attend_afternoon_checkIn' => fake()->time('H:i:s', '13:30:00'),
            'attend_afternoon_checkOut' => fake()->time('H:i:s', '18:00:00'),
        ]);
    }

    /**
     * Indicate that the student is absent.
     */
    public function absent(): static
    {
        return $this->state(fn(array $attributes) => [
            'attend_checkIn' => null,
            'attend_checkOut' => null,
            'attend_afternoon_checkIn' => null,
            'attend_afternoon_checkOut' => null,
        ]);
    }
}
