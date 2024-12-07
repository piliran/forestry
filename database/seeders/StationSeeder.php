<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\District; // Import the District model to reference district IDs
use App\Models\User; // Import the User model to reference contact person (user) IDs

class StationSeeder extends Seeder
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
        DB::table('stations')->insert([
            [
                'name' => 'Station 1',
                'location' => 'Location 1',
                'district_id' => District::first()->id, // Assuming a district exists
                'email' => 'station1@example.com',
                'contact_person' => User::first()->id, // Assuming a user exists
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'name' => 'Station 2',
                'location' => 'Location 2',
                'district_id' => District::skip(1)->first()->id, // Reference another district
                'email' => 'station2@example.com',
                'contact_person' => User::skip(1)->first()->id, // Reference another user
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ]
        ]);

        // Insert random data using Faker for 10 more stations
        // for ($i = 0; $i < 10; $i++) {
        //     DB::table('stations')->insert([
        //         'name' => 'Station ' . ($i + 3), // "Station 3", "Station 4", etc.
        //         'location' => $faker->address, // Random location
        //         'district_id' => District::inRandomOrder()->first()->id, // Random district reference
        //         'email' => $faker->unique()->safeEmail, // Random unique email
        //         'contact_person' => User::inRandomOrder()->first()->id, // Random user reference
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //         'deleted_at' => null,
        //     ]);
        // }
    }
}
