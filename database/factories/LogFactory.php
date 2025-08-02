<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Log>
 */
class LogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $actions = [
            'CREATE' => [
                'Student Registration',
                'Event Creation',
                'Fine Assessment',
                'User Account Creation',
                'System Configuration'
            ],
            'UPDATE' => [
                'Student Information',
                'Event Details',
                'Fine Amount',
                'User Profile',
                'System Settings'
            ],
            'DELETE' => [
                'Student Record',
                'Event Cancellation',
                'Fine Removal',
                'User Account',
                'System Data'
            ],
            'VIEW' => [
                'Attendance Report',
                'Student List',
                'Event Schedule',
                'Fine Summary',
                'System Logs'
            ],
            'EXPORT' => [
                'Attendance Data',
                'Student Records',
                'Event Reports',
                'Fine Reports',
                'System Reports'
            ]
        ];

        $actionType = fake()->randomElement(array_keys($actions));
        $actionDetail = fake()->randomElement($actions[$actionType]);

        return [
            'admin_type' => fake()->randomElement(['super', 'admin', 'moderator']),
            'action' => $actionType,
            'details' => $actionDetail,
            'admin_id' => User::factory(),
        ];
    }

    /**
     * Indicate that the log is for student-related actions.
     */
    public function studentAction(): static
    {
        return $this->state(fn(array $attributes) => [
            'action' => fake()->randomElement(['CREATE', 'UPDATE', 'DELETE', 'VIEW']),
            'details' => fake()->randomElement([
                'Student Registration',
                'Student Information Update',
                'Student Record Deletion',
                'Student List View'
            ]),
        ]);
    }

    /**
     * Indicate that the log is for event-related actions.
     */
    public function eventAction(): static
    {
        return $this->state(fn(array $attributes) => [
            'action' => fake()->randomElement(['CREATE', 'UPDATE', 'DELETE', 'VIEW']),
            'details' => fake()->randomElement([
                'Event Creation',
                'Event Details Update',
                'Event Cancellation',
                'Event Schedule View'
            ]),
        ]);
    }

    /**
     * Indicate that the log is for fine-related actions.
     */
    public function fineAction(): static
    {
        return $this->state(fn(array $attributes) => [
            'action' => fake()->randomElement(['CREATE', 'UPDATE', 'DELETE', 'VIEW']),
            'details' => fake()->randomElement([
                'Fine Assessment',
                'Fine Amount Update',
                'Fine Removal',
                'Fine Summary View'
            ]),
        ]);
    }
}
