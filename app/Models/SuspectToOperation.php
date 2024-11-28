<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuspectToOperation extends Model
{
    //
    protected $fillable = [
        'suspect_id',
        'operation_id',
    ];

    /**
     * Get the suspect associated with this operation.
     */
    public function suspect()
    {
        return $this->belongsTo(Suspect::class);
    }

    /**
     * Get the operation associated with this suspect.
     */
    public function operation()
    {
        return $this->belongsTo(Operation::class);
    }
}
