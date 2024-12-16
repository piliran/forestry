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
                'item' => 'Motor vehicle',

                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'item' => 'Push bikes',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'item' => 'Axes',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'item' => 'Hoes',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'item' => 'Shovels',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'item' => 'Panga knives',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'item' => 'Cross-cut saws',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'item' => 'Bow-saws',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'item' => 'Pit-saws',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'item' => 'Charcoal bags',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'item' => 'Planks',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'item' => 'Poles',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'item' => 'Logs',
                'created_at' => now(),
                'updated_at' => now(),
            ],[
                'item' => 'Fuel wood',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
