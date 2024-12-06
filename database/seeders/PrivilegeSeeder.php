<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrivilegeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all table_to_permission records
        $tableToPermissions = DB::table('table_to_permissions')->get();

        foreach ($tableToPermissions as $entry) {
            // Fetch table name
            $tableName = DB::table('tables')->where('id', $entry->table_id)->value('name');

            // Fetch permission name
            $permissionName = DB::table('permissions')->where('id', $entry->permission_id)->value('name');

            // Combine table and permission name to form the privilege
            $privilege = strtolower($permissionName . ' ' . $tableName);

            // Insert into privileges table
            DB::table('privileges')->insert([
                'table_to_permission_id' => $entry->id,
                'privilege' => $privilege,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
