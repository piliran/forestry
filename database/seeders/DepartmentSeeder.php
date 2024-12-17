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

        $userIds = DB::table('users')->pluck('id'); // Fetch all user IDs

        if ($userIds->isEmpty()) {
            $this->command->info('No users found. Please seed the users table first.');
            return;
        }

        // Insert static data
        DB::table('departments')->insert([
            [
                'name' => 'Forestry',
                'code' => 'FOR001',
                'location' => 'Main Building',
                'contact_person' => $userIds->random(),
                'phone' => '123-456-7890',
                'email' => 'forestry@example.com',
                'website' => 'https://forestry.example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Finance',
                'code' => 'FIN001',
                'location' => 'Building B',
                'contact_person' => $userIds->random(),
                'phone' => '234-567-8901',
                'email' => 'finance@example.com',
                'website' => 'https://finance.example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Insert random data using Faker
        // for ($i = 0; $i < 10; $i++) {
        //     DB::table('departments')->insert([
        //         'name' => $faker->company,
        //         'code' => $faker->unique()->bothify('???###'),
        //         'location' => $faker->address,
        //         'contact_person' => $faker->name,
        //         'phone' => $faker->phoneNumber,
        //         'email' => $faker->email,
        //         'website' => $faker->url,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }
    }
}
