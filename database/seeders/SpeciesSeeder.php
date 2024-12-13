<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpeciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('species')->insert([
            [
                'specie_cat_id' => 1, // Assuming 1 corresponds to a valid `specie_categories` ID like Mammals
                'name' => 'Elephant',
                'description' => 'Large herbivorous mammals with trunks.',
                'matured_specie_count' => '150',
                'unmatured_specie_count' => '80',
                'planted_seedlings_count' => '0',
                'unplanted_seedlings_count' => '0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'specie_cat_id' => 2, // Assuming 2 corresponds to Birds
                'name' => 'Eagle',
                'description' => 'Large birds of prey with exceptional eyesight.',
                'matured_specie_count' => '50',
                'unmatured_specie_count' => '20',
                'planted_seedlings_count' => '0',
                'unplanted_seedlings_count' => '0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'specie_cat_id' => 3, // Assuming 3 corresponds to Reptiles
                'name' => 'Python',
                'description' => 'Large nonvenomous snakes found in tropical regions.',
                'matured_specie_count' => '70',
                'unmatured_specie_count' => '30',
                'planted_seedlings_count' => '0',
                'unplanted_seedlings_count' => '0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'specie_cat_id' => 4, // Assuming 4 corresponds to Fish
                'name' => 'Tilapia',
                'description' => 'Freshwater fish widely farmed for food.',
                'matured_specie_count' => '2000',
                'unmatured_specie_count' => '1000',
                'planted_seedlings_count' => '0',
                'unplanted_seedlings_count' => '0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'specie_cat_id' => 5, // Assuming 5 corresponds to Amphibians
                'name' => 'Frog',
                'description' => 'Amphibians known for their jumping ability and croaking sounds.',
                'matured_specie_count' => '300',
                'unmatured_specie_count' => '150',
                'planted_seedlings_count' => '0',
                'unplanted_seedlings_count' => '0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
