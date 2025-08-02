<?php

namespace Database\Seeders;

use App\Models\Fine;
use App\Models\Event;
use App\Models\Student;
use Illuminate\Database\Seeder;

class FineSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Get events and students
    $events = Event::all();
    $students = Student::where('s_status', 'ENROLLED')->get();

    // Create fines for students with violations
    foreach ($events as $event) {
      $studentsWithViolations = $students->random(rand(5, 15));

      foreach ($studentsWithViolations as $student) {
        Fine::factory()->create([
          'event_id' => $event->id,
          'student_id' => $student->id,
        ]);
      }
    }

    // Create specific violation types
    Fine::factory(15)->morningViolations()->create();
    Fine::factory(10)->afternoonViolations()->create();
    Fine::factory(5)->noViolations()->create();
  }
}
