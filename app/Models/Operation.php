<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Operation extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'name',
        'station_id',
        'description',
        'operation_type_id',
        'created_by'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $casts = [
    ];

    /**
     * Relationships
     */

    // Relationship with Station
    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    // Relationship with Operation Type
    public function operationType()
    {
        return $this->belongsTo(OperationType::class);
    }

    // Relationship with Funder
    public function funder()
    {
        return $this->belongsTo(Funder::class, 'funded_by');
    }

    // Relationship with OperationToTeam
    public function operationToTeam()
    {
        return $this->hasMany(OperationToTeam::class, 'operation_id');
    }
}
