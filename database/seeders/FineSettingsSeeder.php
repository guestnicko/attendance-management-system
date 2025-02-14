<?php

namespace Database\Seeders;

use App\Models\FineSettings;
use Illuminate\Database\Seeder;

class FineSettingsSeeder extends Seeder
{
    public function run(): void
    {
        FineSettings::firstOrCreate(
            ['id' => 1],
            [
                'fine_amount' => 25,
                'morning_checkin' => true,
                'morning_checkout' => true,
                'afternoon_checkin' => true,
                'afternoon_checkout' => true
            ]
        );
    }
}
