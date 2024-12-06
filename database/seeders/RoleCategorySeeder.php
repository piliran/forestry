<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RoleCategory;
use Illuminate\Support\Facades\DB;

class RoleCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $roleCategories = [
            [
                'name' => 'district',

            ],
            [
                'name' => 'zone',

            ],
            [
                'name' => 'svtp',

            ],
            [
                'name' => 'hq',

            ],
        ];

        foreach ($roleCategories as $category) {
            RoleCategory::firstOrCreate(['name' => $category['name']], $category);
        }
    }
}
