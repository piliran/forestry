<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\Zone; // Import the Zone model to reference zone IDs

class DistrictSeeder extends Seeder
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
        DB::table('districts')->insert([
            [
                'name' => 'District 1',
                'zone_id' => Zone::first()->id, // Assuming a zone exists
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'name' => 'District 2',
                'zone_id' => Zone::skip(1)->first()->id, // Reference another zone
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ]
            ,
            [
                'name' => 'District 3',
                'zone_id' => Zone::skip(1)->first()->id, // Reference another zone
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ]
        ]);

        // Insert random data using Faker for 10 more districts
        // for ($i = 0; $i < 10; $i++) {
        //     DB::table('districts')->insert([
        //         'name' => 'District ' . ($i + 3), // "District 3", "District 4", etc.
        //         'zone_id' => Zone::inRandomOrder()->first()->id, // Random zone reference
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //         'deleted_at' => null,
        //     ]);
        // }
    }
}
