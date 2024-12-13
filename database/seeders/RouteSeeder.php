<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('routes')->insert([
            [
                'area_id' => 1, // Replace with an existing area ID
                'route_type_id' => 1, // Replace with an existing route type ID
                'name' => 'Main Street Route',
                'code' => 'MSR001',
                'location' => 'Downtown',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'area_id' => 2, // Replace with an existing area ID
                'route_type_id' => 2, // Replace with an existing route type ID
                'name' => 'Countryside Route',
                'code' => 'CSR002',
                'location' => 'Countryside',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'area_id' => 3, // Replace with an existing area ID
                'route_type_id' => 3, // Replace with an existing route type ID
                'name' => 'Highway 101',
                'code' => 'HW101',
                'location' => 'Regional',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
