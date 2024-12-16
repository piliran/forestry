<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfiscateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('confiscates')->insert([
            [
                'name' => 'Motor vehicle',

                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Push bikes',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'name' => 'Axes',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'name' => 'Hoes',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'name' => 'Shovels',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'name' => 'Panga knives',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'name' => 'Cross-cut saws',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'name' => 'Bow-saws',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'name' => 'Pit-saws',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'name' => 'Charcoal bags',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'name' => 'Planks',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'name' => 'Poles',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'name' => 'Logs',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'name' => 'Fuel wood',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
