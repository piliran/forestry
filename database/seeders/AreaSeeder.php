<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('areas')->insert([
            [
                'name' => 'Central Zone',
                'station_id' => 1, // Replace with an actual station ID
                'code' => 'CZ001',
                'location' => 'Downtown',
                'latitude' => -13.963137,
                'longitude' => 33.774119,
                'contact_person' => 1, // Replace with an actual user ID
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'North Zone',
                'station_id' => 2, // Replace with an actual station ID
                'code' => 'NZ002',
                'location' => 'Uptown',
                'latitude' => -11.468867,
                'longitude' => 34.020518,
                'contact_person' => 2, // Replace with an actual user ID
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'South Zone',
                'station_id' => 3, // Replace with an actual station ID
                'code' => 'SZ003',
                'location' => 'Suburban',
                'latitude' => -10.123456,
                'longitude' => 35.654321,
                'contact_person' => 3, // Replace with an actual user ID
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
