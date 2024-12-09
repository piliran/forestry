<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Operation extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'station_id',
        'route_id',
        'description',
        'funded_by',
        'operation_type_id',
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

    // Relationship with Route
    public function route()
    {
        return $this->belongsTo(Route::class);
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
