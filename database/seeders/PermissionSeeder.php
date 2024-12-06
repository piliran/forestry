<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $permissions = [
            [
                'name' => 'create',
                'description' => 'Permission to create a new entity',
            ],
            [
                'name' => 'edit',
                'description' => 'Permission to edit an existing entity',
            ],
            [
                'name' => 'update',
                'description' => 'Permission to update an existing entity',
            ],
            [
                'name' => 'delete',
                'description' => 'Permission to delete a entity',
            ],
            [
                'name' => 'view',
                'description' => 'Permission to view entity details',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission['name']], $permission);
        }
    }
}
