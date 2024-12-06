<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DepartmentSeeder extends Seeder
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
        DB::table('departments')->insert([
            [
                'name' => 'Forestry',
                'code' => 'HR001',
                'location' => 'Main Building',
                'contact_person' => 'test contact',
                'phone' => '123-456-7890',
                'email' => 'hr@example.com',
                'website' => 'https://hr.example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Finance',
                'code' => 'FIN001',
                'location' => 'Building B',
                'contact_person' => 'sample contact',
                'phone' => '234-567-8901',
                'email' => 'finance@example.com',
                'website' => 'https://finance.example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Insert random data using Faker
        for ($i = 0; $i < 10; $i++) {
            DB::table('departments')->insert([
                'name' => $faker->company,
                'code' => $faker->unique()->bothify('???###'),
                'location' => $faker->address,
                'contact_person' => $faker->name,
                'phone' => $faker->phoneNumber,
                'email' => $faker->email,
                'website' => $faker->url,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
