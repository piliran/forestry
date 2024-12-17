<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Operation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'station_id',
        'description',
        'operation_type_id',
        'created_by',
    ];

    /**
     * Relationships
     */

    // Relationship with Station
    public function station()
    {
        return $this->belongsTo(Station::class, 'station_id');
    }

    // Relationship with Operation Type
    public function operationType()
    {
        return $this->belongsTo(OperationType::class, 'operation_type_id');
    }



    // Relationship to get full Funder details
public function funder()
{
    return $this->hasOneThrough(
        Funder::class,         // Final Model (Funder)
        FunderToOperation::class, // Intermediate Model
        'operation_id',        // Foreign key on FunderToOperation
        'id',                  // Foreign key on Funder
        'id',                  // Local key on Operation
        'funded_by'            // Local key on FunderToOperation
    );
}

// Relationship to get full Route details
public function route()
{
    return $this->hasOneThrough(
        Route::class,           // Final Model (Route)
        RouteToOperation::class,// Intermediate Model
        'operation_id',         // Foreign key on RouteToOperation
        'id',                   // Foreign key on Route
        'id',                   // Local key on Operation
        'route_id'              // Local key on RouteToOperation
    );
}


    // Relationship with OperationToTeam
    public function operationToTeam()
    {
        return $this->hasMany(OperationToTeam::class, 'operation_id');
    }

    // Relationship with Suspects
    public function suspects()
    {
        return $this->belongsToMany(Suspect::class, 'suspect_to_operations', 'operation_id', 'suspect_id');
    }

    // Relationship with Schedule
    public function schedule()
    {
        return $this->hasMany(Schedule::class, 'operation_id');
    }
}
