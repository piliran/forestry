<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'operation_type_id',
        'station_id',
    ];

    /**
     * Get the operation type associated with the operation.
     */
    public function operationType()
    {
        return $this->belongsTo(OperationType::class, 'operation_type_id');
    }

    /**
     * Get the station associated with the operation.
     */
    public function station()
    {
        return $this->belongsTo(Station::class, 'station_id');
    }
}
