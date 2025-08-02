<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Event;
use App\Models\StudentAttendance;
use App\Models\Fine;
use App\Models\Log;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin users
        User::factory()->create([
            'admin_fname' => 'Test',
            'admin_lname' => 'User',
            'admin_uname' => 'TestUser',
            'admin_type' => 'super',
            'password' => '123',
            'admin_email' => 'test@example.com',
        ]);

        // Create additional admin users
        User::factory(3)->create([
            'admin_type' => 'admin',
        ]);

        // Call specific seeders
        $this->call([
            FineSettingsSeeder::class,
            StudentSeeder::class,
            EventSeeder::class,
            AttendanceSeeder::class,
            FineSeeder::class,
            LogSeeder::class,
        ]);
    }
}
