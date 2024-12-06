<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TableToPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Fetch all permission ids
        $permissions = DB::table('permissions')->pluck('id')->toArray();

        // Fetch all table ids
        $tables = DB::table('tables')->pluck('id')->toArray();

        // Loop through each table_id and associate it with all permission_ids
        foreach ($tables as $tableId) {
            foreach ($permissions as $permissionId) {
                DB::table('table_to_permissions')->insert([
                    'table_id' => $tableId,
                    'permission_id' => $permissionId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
