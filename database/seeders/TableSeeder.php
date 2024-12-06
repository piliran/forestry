<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Table;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Fetch all table names from the database
        $tableNames = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = ?", [env('DB_DATABASE')]);
        $defaultTables = [
            'migrations',
            'cache_locks',
            'password_resets',
            'password_reset_tokens',
            'personal_access_tokens',
            'failed_jobs',
            'job_batches',
            'jobs',
            'cache',
            'sessions',
        ];
        foreach ($tableNames as $table) {
            $tableName = $table->table_name; // Access the table_name property

           // Skip default Laravel tables
           if (in_array($tableName, $defaultTables)) {
            continue;
        }
            $formattedName = str_replace('_', ' ', strtolower($tableName));


            Table::firstOrCreate(
                ['name' => $formattedName],

            );
        }
    }
}
