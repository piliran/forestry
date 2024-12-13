<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RouteTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('route_types')->insert([
            [
                'name' => 'Urban',
                'description' => 'Routes within the city or metropolitan areas.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rural',
                'description' => 'Routes in countryside or non-urban areas.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Highway',
                'description' => 'Routes on major roads connecting cities or regions.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
