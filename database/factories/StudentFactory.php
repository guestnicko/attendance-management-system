<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $programs = ['BSIS', 'BSIT'];
        $levels = ['1st Year', '2nd Year', '3rd Year', '4th Year'];
        $sections = ['A', 'B', 'C', 'D'];
        $statuses = ['ENROLLED', 'DROPPED', 'GRADUATED', 'TO BE UPDATED'];

        return [
            's_rfid' => fake()->unique()->numerify('RFID####'),
            's_studentID' => fake()->unique()->numerify('2023-####'),
            's_fname' => fake()->firstName(),
            's_lname' => fake()->lastName(),
            's_mname' => fake()->optional(0.7)->firstName(),
            's_suffix' => fake()->optional(0.1)->randomElement(['Jr.', 'Sr.', 'II', 'III', 'IV']),
            's_program' => fake()->randomElement($programs),
            's_lvl' => fake()->randomElement($levels),
            's_set' => fake()->randomElement($sections),
            's_image' => fake()->optional(0.3)->imageUrl(200, 200, 'people'),
            's_status' => fake()->randomElement($statuses),
        ];
    }

    /**
     * Indicate that the student is enrolled.
     */
    public function enrolled(): static
    {
        return $this->state(fn(array $attributes) => [
            's_status' => 'ENROLLED',
        ]);
    }

    /**
     * Indicate that the student is in BSIS program.
     */
    public function bsis(): static
    {
        return $this->state(fn(array $attributes) => [
            's_program' => 'BSIS',
        ]);
    }

    /**
     * Indicate that the student is in BSIT program.
     */
    public function bsit(): static
    {
        return $this->state(fn(array $attributes) => [
            's_program' => 'BSIT',
        ]);
    }
}
