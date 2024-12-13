<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OperationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('operation_types')->insert([
            [
                'name' => 'Inspection',
                'description' => 'Operations involving routine inspections of areas or routes.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Maintenance',
                'description' => 'Operations related to maintenance of infrastructure or systems.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Emergency Response',
                'description' => 'Operations dealing with emergency situations or incidents.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
