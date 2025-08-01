<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'admin_fname' => fake()->firstName(),
            'admin_lname' => fake()->lastName(),
            'admin_uname' => fake()->unique()->userName(),
            'admin_type' => fake()->randomElement(['admin', 'super', 'moderator']),
            'admin_email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user is a super admin.
     */
    public function super(): static
    {
        return $this->state(fn(array $attributes) => [
            'admin_type' => 'super',
        ]);
    }

    /**
     * Indicate that the user is a regular admin.
     */
    public function admin(): static
    {
        return $this->state(fn(array $attributes) => [
            'admin_type' => 'admin',
        ]);
    }
}
