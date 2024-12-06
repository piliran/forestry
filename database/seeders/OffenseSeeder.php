<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OffenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('offenses')->insert([
            [
                'name' => 'Illegal Logging',
                'description' => 'Cutting down trees without a permit or in a restricted area.',
                'penalty' => 'Fine up to MWK 500,000 or 5 years imprisonment.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Charcoal Production Without Permit',
                'description' => 'Producing or selling charcoal without proper authorization.',
                'penalty' => 'Fine up to MWK 200,000 or 2 years imprisonment.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Encroachment on Forest Reserves',
                'description' => 'Settling or farming in protected forest areas.',
                'penalty' => 'Fine up to MWK 1,000,000 or 3 years imprisonment.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Illegal Transportation of Forest Products',
                'description' => 'Transporting forest products such as timber or firewood without a license.',
                'penalty' => 'Fine up to MWK 300,000 or confiscation of vehicle and products.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Unlawful Setting of Fires',
                'description' => 'Starting fires in forest reserves or protected areas without authorization.',
                'penalty' => 'Fine up to MWK 400,000 or 3 years imprisonment.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Illegal Hunting in Forest Reserves',
                'description' => 'Killing or capturing animals in forest reserves without a permit.',
                'penalty' => 'Fine up to MWK 600,000 or 4 years imprisonment.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
