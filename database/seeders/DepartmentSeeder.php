<?php

namespace Database\Seeders;

use App\Models\User;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $userIds = User::pluck('id')->toArray();

        if(empty($userIds))
        {
            throw new \Exception('No users found. Please create some users first.');
        }

        DB::table('departments')->insert([
            'name' => 'Department Of Forestry',
            'code' => 'dof',
            'location' => 'Building A',
            'contact_person' => $userIds[array_rand($userIds)],
            'phone' => '265999888777',
            'email' => 'dof@example.com',
            'website' => 'https://example.com/dof',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
