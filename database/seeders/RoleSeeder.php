<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ensure you have role categories in the 'role_categories' table
        $roleCategoryId = DB::table('role_categories')->value('id');

        if (!$roleCategoryId) {
            $this->command->info('No role categories found. Please seed the role_categories table first.');
            return;
        }

        // Predefined role names
        $roles = [
            'Monitoring and Evaluation Officer',
            'Natural Resources Management Team Leader',
            'Forestry Assistant',
            'District Forestry Officer',
            'Forestry Specialist',
            'admin',
        ];

        // Insert roles into the database
        foreach ($roles as $role) {
            DB::table('roles')->insert([
                'role_category_id' => $roleCategoryId,
                'name' => $role,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Roles table seeded successfully.');
    }
}
