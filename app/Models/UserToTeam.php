<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserToTeam extends Model
{
    //
    protected $fillable = [
        'user_id',
        'team_id',
    ];

    /**
     * Get the suspect associated with this operation.
     */
    public function user()
    {
        return $this->belongsTo(Suspect::class);
    }

    /**
     * Get the operation associated with this suspect.
     */
    public function team()
    {
        return $this->belongsTo(Operation::class);
    }
}
