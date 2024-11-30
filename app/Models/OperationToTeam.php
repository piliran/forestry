<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperationToTeam extends Model
{
    //
    protected $fillable = [
        'operation_id',
        'team_id',
    ];

    /**
     * Get the suspect associated with this operation.
     */
    public function operation()
    {
        return $this->belongsTo(Operation::class);
    }

    /**
     * Get the operation associated with this suspect.
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
