<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            UserSeeder::class,

            DepartmentSeeder::class,
            ZoneSeeder::class,
            DistrictSeeder::class,

            UserDistrictSeeder::class,

            StationSeeder::class,
            RoleCategorySeeder::class,
            RoleSeeder::class,

            PermissionSeeder::class,
            TableSeeder::class,
            TableToPermissionSeeder::class,
            PrivilegeSeeder::class,
            OffenseSeeder::class,

            AreaSeeder::class,
            RouteTypeSeeder::class,
            RouteSeeder::class,
            OperationTypeSeeder::class,
            OperationSeeder::class,
            SpecieCategorySeeder::class,
            SpeciesSeeder::class,
            ConfiscateSeeder::class,
            FunderSeeder::class,


        ]);

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
