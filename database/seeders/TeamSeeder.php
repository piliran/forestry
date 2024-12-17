<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('teams')->insert([
            [
                'name' => 'Maintenance Team',
                'description' => 'Responsible for maintaining infrastructure and systems.',
                'station_id' => 1, // Replace with an actual station ID
                'created_by' => 1, // Replace with an actual user ID
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Inspection Team',
                'description' => 'Handles routine inspections of equipment and systems.',
                'station_id' => 2, // Replace with an actual station ID
                'created_by' => 1, // Replace with an actual user ID
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Emergency Response Team',
                'description' => 'Responds to emergencies and urgent repairs.',
                'station_id' => 3, // Replace with an actual station ID
                'created_by' => 2, // Replace with an actual user ID
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
