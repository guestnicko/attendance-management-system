<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = fake()->dateTimeBetween('now', '+2 months');
        $isWholeDay = fake()->boolean(80); // 80% chance of whole day events

        $checkInStart = fake()->time('H:i:s', '08:00:00');
        $checkInEnd = fake()->time('H:i:s', '09:00:00');
        $checkOutStart = fake()->time('H:i:s', '16:00:00');
        $checkOutEnd = fake()->time('H:i:s', '17:00:00');

        $afternoonCheckInStart = null;
        $afternoonCheckInEnd = null;
        $afternoonCheckOutStart = null;
        $afternoonCheckOutEnd = null;

        // If not whole day, add afternoon schedule
        if (!$isWholeDay) {
            $afternoonCheckInStart = fake()->time('H:i:s', '13:00:00');
            $afternoonCheckInEnd = fake()->time('H:i:s', '14:00:00');
            $afternoonCheckOutStart = fake()->time('H:i:s', '17:00:00');
            $afternoonCheckOutEnd = fake()->time('H:i:s', '18:00:00');
        }

        return [
            'event_name' => fake()->randomElement([
                'Class Attendance',
                'Laboratory Session',
                'Examination Day',
                'Group Activity',
                'Project Presentation',
                'Workshop Session',
                'Seminar Attendance',
                'Practical Exam',
                'Research Defense',
                'Capstone Project'
            ]),
            'date' => $date->format('Y-m-d'),
            'checkIn_start' => $checkInStart,
            'checkIn_end' => $checkInEnd,
            'checkOut_start' => $checkOutStart,
            'checkOut_end' => $checkOutEnd,
            'afternoon_checkIn_start' => $afternoonCheckInStart,
            'afternoon_checkIn_end' => $afternoonCheckInEnd,
            'afternoon_checkOut_start' => $afternoonCheckOutStart,
            'afternoon_checkOut_end' => $afternoonCheckOutEnd,
            'isWholeDay' => $isWholeDay ? 'true' : 'false',
            'event_status' => fake()->randomElement(['pending', 'active', 'completed', 'cancelled']),
            'admin_id' => User::factory(),
        ];
    }

    /**
     * Indicate that the event is active.
     */
    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'event_status' => 'active',
        ]);
    }

    /**
     * Indicate that the event is whole day.
     */
    public function wholeDay(): static
    {
        return $this->state(fn(array $attributes) => [
            'isWholeDay' => 'true',
            'afternoon_checkIn_start' => null,
            'afternoon_checkIn_end' => null,
            'afternoon_checkOut_start' => null,
            'afternoon_checkOut_end' => null,
        ]);
    }

    /**
     * Indicate that the event is half day.
     */
    public function halfDay(): static
    {
        return $this->state(fn(array $attributes) => [
            'isWholeDay' => 'false',
        ]);
    }
}
