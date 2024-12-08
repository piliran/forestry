<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\Department; // Import the Department model to reference department IDs

class ZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // Insert static data (optional)
        DB::table('zones')->insert([
            [
                'name' => 'Zone 1',
                'department_id' => Department::first()->id, // Assuming a department exists
                'phone' => '123-456-7890',
                'location' => 'Blantyre',
                'website' => 'https://zone1.com',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'name' => 'Zone 2',
                'department_id' => Department::skip(1)->first()->id, // Reference another department
                'phone' => '987-654-3210',
                'location' => 'Nsanje',
                'website' => 'https://zone2.com',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ]
            ,
            [
                'name' => 'Zone 3',
                'department_id' => Department::skip(1)->first()->id, // Reference another department
                'phone' => '987-654-3210',
                'location' => 'Blantyre',
                'website' => 'https://zone2.com',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ]
        ]);

        // Insert random data using Faker for 10 more zones
        // for ($i = 0; $i < 10; $i++) {
        //     DB::table('zones')->insert([
        //         'name' => 'Zone ' . ($i + 3), // "Zone 3", "Zone 4", etc.
        //         'department_id' => Department::inRandomOrder()->first()->id, // Random department reference
        //         'phone' => $faker->phoneNumber,
        //         'location' => $faker->city,
        //         'website' => $faker->url,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //         'deleted_at' => null,
        //     ]);
        // }
    }
}
