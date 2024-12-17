<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FunderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('funders')->insert([
            [
                'name' => 'Shilly Valley Transformation Programme',
                'email' => 'shillyvalley@example.com',

                'phone' => '123-456-7890',
                'address' => '123 Main Street, Cityville',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'World bank',
                'email' => 'worldbank@example.com',

                'phone' => '987-654-3210',
                'address' => '456 Elm Street, Townsville',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'UNICEF',
                'email' => 'unicef@example.com',

                'phone' => '555-789-1234',
                'address' => '789 Oak Avenue, Villagetown',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
