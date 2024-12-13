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
        'route_id',
        'funded_by',
        'created_by',
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
        return $this->belongsTo(OperationType::class, 'operation_type_id');
    }

    // Relationship with Funder
    public function funder()
    {
        return $this->belongsTo(Funder::class, 'funded_by');
    }


    // Relationship with Funder
    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id');
    }

    // Relationship with OperationToTeam
    public function operationToTeam()
    {
        return $this->hasMany(OperationToTeam::class, 'operation_id');
    }

    // Relationship with Schedule
    public function schedule()
    {
        return $this->hasMany(Schedule::class, 'operation_id');
    }
}
