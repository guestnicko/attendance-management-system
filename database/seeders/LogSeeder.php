<?php

namespace Database\Seeders;

use App\Models\Log;
use App\Models\User;
use Illuminate\Database\Seeder;

class LogSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Get admin users
    $admins = User::where('admin_type', 'admin')->orWhere('admin_type', 'super')->get();

    // Create general logs
    Log::factory(30)->create([
      'admin_id' => $admins->random()->id
    ]);

    // Create student-related logs
    Log::factory(20)->studentAction()->create([
      'admin_id' => $admins->random()->id
    ]);

    // Create event-related logs
    Log::factory(20)->eventAction()->create([
      'admin_id' => $admins->random()->id
    ]);

    // Create fine-related logs
    Log::factory(15)->fineAction()->create([
      'admin_id' => $admins->random()->id
    ]);

    // Create system logs
    Log::factory(10)->create([
      'admin_type' => 'super',
      'action' => 'SYSTEM',
      'details' => 'System Configuration Update',
      'admin_id' => $admins->where('admin_type', 'super')->first()->id
    ]);
  }
}
