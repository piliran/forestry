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

    // Relationship with FunderToOperation
    public function funder()
    {
        return $this->hasOne(FunderToOperation::class, 'operation_id');
    }

    // Relationship with RouteToOperation
    public function route()
    {
        return $this->hasOne(RouteToOperation::class, 'operation_id');
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
