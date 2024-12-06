<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\District; // Import the District model to reference district IDs

class UserSeeder extends Seeder
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
        DB::table('users')->insert([
            [
                'title' => 'Mr.',
                'name' => 'Pilirani Zimba',
                'email' => 'zpiliran@gmail.com',
                'gender' => 'Male',
                'DOB' => '19997-05-15',
                'district_id' => District::first()->id, // Assuming a district exists
                'age' => '39',
                'position' => 'Manager',
                'national_id' => '123456789',
                'phone' => '099-456-7890',
                'account_status' => 'Active',
                'marital_status' => 'Single',
                'email_verified_at' => now(),
                'password' => bcrypt('zpiliran@gmail.com'), // Example encrypted password
                'remember_token' => null,
                'current_team_id' => null,
                'profile_photo_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'title' => 'Mr.',
                'name' => 'Wongani Mkandawile',
                'email' => 'wongani@gmail.com',
                'gender' => 'Male',
                'DOB' => '19994-05-15',
                'district_id' => District::first()->id, // Assuming a district exists
                'age' => '39',
                'position' => 'Manager',
                'national_id' => '123456789',
                'phone' => '099-456-7890',
                'account_status' => 'Active',
                'marital_status' => 'Single',
                'email_verified_at' => now(),
                'password' => bcrypt('wongani@gmail.com'), // Example encrypted password
                'remember_token' => null,
                'current_team_id' => null,
                'profile_photo_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'title' => 'Mr.',
                'name' => 'Arthur Kwisongole',
                'email' => 'kwisongole@gmail.com',
                'gender' => 'Female',
                'DOB' => '1997-07-20',
                'district_id' => District::skip(1)->first()->id, // Reference another district
                'age' => '34',
                'position' => 'Assistant',
                'national_id' => '987654321',
                'phone' => '088-654-3210',
                'account_status' => 'Active',
                'marital_status' => 'Married',
                'email_verified_at' => now(),
                'password' => bcrypt('kwisongole@gmail.com'), // Example encrypted password
                'remember_token' => null,
                'current_team_id' => null,
                'profile_photo_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ]
        ]);

        // Insert random data using Faker for 10 more users
        for ($i = 0; $i < 10; $i++) {
            DB::table('users')->insert([
                'title' => $faker->title,
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'gender' => $faker->randomElement(['Male', 'Female']),
                'DOB' => $faker->date,
                'district_id' => District::inRandomOrder()->first()->id, // Random district reference
                'age' => $faker->numberBetween(18, 60),
                'position' => $faker->jobTitle,
                'national_id' => $faker->unique()->numerify('#########'),
                'phone' => $faker->phoneNumber,
                'account_status' => $faker->randomElement(['Active', 'Inactive']),
                'marital_status' => $faker->randomElement(['Single', 'Married']),
                'email_verified_at' => now(),
                'password' => bcrypt('secret'), // Example encrypted password
                'remember_token' => null,
                'current_team_id' => null,
                'profile_photo_path' => $faker->imageUrl,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ]);
        }
    }
}
