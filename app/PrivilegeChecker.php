<?php

namespace App;

use Illuminate\Support\Facades\DB;

trait PrivilegeChecker
{
    /**
     * Check if the user has the specified privilege for a model.
     *
     * @param int $userId
     * @param string $action
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return bool
     */
    public function hasPrivilege(int $userId, string $action, $model): bool
    {
        // Get the table name from the model
        $tableName = $model->getTable();

        // Convert the table name to match the format in the `tables` table
        $formattedTableName = str_replace('_', ' ', strtolower($tableName));

        // Construct the privilege name (e.g., "edit team operations")
        $privilegeName = "{$action} {$formattedTableName}";

        // Check if the user has this privilege
        return DB::table('user_privileges')
            ->join('privileges', 'user_privileges.privilege_id', '=', 'privileges.id')
            ->where('user_privileges.user_id', $userId)
            ->where('privileges.privilege', $privilegeName)
            ->exists();
    }
}
