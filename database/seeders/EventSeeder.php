<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Get admin users for event creation
    $admins = User::where('admin_type', 'admin')->orWhere('admin_type', 'super')->get();

    // Create whole day events
    Event::factory(8)->wholeDay()->active()->create([
      'admin_id' => $admins->random()->id
    ]);

    // Create half day events
    Event::factory(12)->halfDay()->active()->create([
      'admin_id' => $admins->random()->id
    ]);

    // Create pending events
    Event::factory(5)->create([
      'event_status' => 'pending',
      'admin_id' => $admins->random()->id
    ]);

    // Create completed events
    Event::factory(3)->create([
      'event_status' => 'completed',
      'admin_id' => $admins->random()->id
    ]);

    // Create cancelled events
    Event::factory(2)->create([
      'event_status' => 'cancelled',
      'admin_id' => $admins->random()->id
    ]);
  }
}
