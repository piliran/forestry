<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecieCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('specie_categories')->insert([
            [
                'name' => 'Mammals',
                'description' => 'Warm-blooded vertebrates with hair or fur, most of which give live birth.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Birds',
                'description' => 'Feathered vertebrates that lay eggs and are known for their ability to fly.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Reptiles',
                'description' => 'Cold-blooded vertebrates with scales, including snakes, lizards, and turtles.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fish',
                'description' => 'Aquatic vertebrates with gills and fins, living in fresh or saltwater.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Amphibians',
                'description' => 'Cold-blooded vertebrates that live both in water and on land, like frogs and salamanders.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
