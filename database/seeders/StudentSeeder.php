<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Create BSIS students
    Student::factory(25)->bsis()->enrolled()->create();
    Student::factory(5)->bsis()->create(['s_status' => 'DROPPED']);
    Student::factory(3)->bsis()->create(['s_status' => 'GRADUATED']);

    // Create BSIT students
    Student::factory(25)->bsit()->enrolled()->create();
    Student::factory(5)->bsit()->create(['s_status' => 'DROPPED']);
    Student::factory(3)->bsit()->create(['s_status' => 'GRADUATED']);

    // Create students with specific year levels
    Student::factory(10)->bsis()->enrolled()->create(['s_lvl' => '1st Year']);
    Student::factory(8)->bsis()->enrolled()->create(['s_lvl' => '2nd Year']);
    Student::factory(6)->bsis()->enrolled()->create(['s_lvl' => '3rd Year']);
    Student::factory(4)->bsis()->enrolled()->create(['s_lvl' => '4th Year']);

    Student::factory(10)->bsit()->enrolled()->create(['s_lvl' => '1st Year']);
    Student::factory(8)->bsit()->enrolled()->create(['s_lvl' => '2nd Year']);
    Student::factory(6)->bsit()->enrolled()->create(['s_lvl' => '3rd Year']);
    Student::factory(4)->bsit()->enrolled()->create(['s_lvl' => '4th Year']);
  }
}
