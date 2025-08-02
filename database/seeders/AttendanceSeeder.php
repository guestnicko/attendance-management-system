<?php

namespace Database\Seeders;

use App\Models\StudentAttendance;
use App\Models\Event;
use App\Models\Student;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Get active events and enrolled students
    $events = Event::where('event_status', 'active')->get();
    $students = Student::where('s_status', 'ENROLLED')->get();

    // Create attendance records for each event and student combination
    foreach ($events as $event) {
      foreach ($students->random(rand(20, 40)) as $student) {
        StudentAttendance::factory()->create([
          'event_id' => $event->id,
          'student_rfid' => $student->s_rfid,
          'id_student' => $student->id,
        ]);
      }
    }

    // Create complete morning attendances
    StudentAttendance::factory(25)->completeMorning()->create();

    // Create complete afternoon attendances
    StudentAttendance::factory(20)->completeAfternoon()->create();

    // Create absent records
    StudentAttendance::factory(15)->absent()->create();
  }
}
