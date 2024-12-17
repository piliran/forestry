<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FunderToOperation extends Model
{
    protected $fillable = [
        'funded_by',
        'operation_id',
    ];

    /**
     * Relationships
     */

    // Relationship with Funder
    public function funder()
    {
        return $this->belongsTo(Funder::class, 'funded_by');
    }

    // Relationship with Operation
    public function operation()
    {
        return $this->belongsTo(Operation::class, 'operation_id');
    }
}
