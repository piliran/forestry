<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OperationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('operations')->insert([
            [
                'name' => 'Daily Maintenance Check',
                'station_id' => 1, // Replace with a valid station ID
                'description' => 'Perform daily maintenance checks on all systems.',
                'operation_type_id' => 2, // Replace with a valid operation_type ID
                'created_by' => 1, // Replace with a valid user ID
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Emergency Repair',
                'station_id' => 2, // Replace with a valid station ID
                'description' => 'Respond to emergency breakdowns and repair systems.',
                'operation_type_id' => 3, // Replace with a valid operation_type ID
                'created_by' => 2, // Replace with a valid user ID
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Routine Inspection',
                'station_id' => 3, // Replace with a valid station ID
                'description' => 'Conduct routine inspections of the station and equipment.',
                'operation_type_id' => 1, // Replace with a valid operation_type ID
                'created_by' => 3, // Replace with a valid user ID
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
