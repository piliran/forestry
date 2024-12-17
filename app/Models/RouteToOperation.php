<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RouteToOperation extends Model
{
    protected $fillable = [
        'route_id',
        'operation_id',
    ];

    /**
     * Relationships
     */

    // Relationship with Route
    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id');
    }

    // Relationship with Operation
    public function operation()
    {
        return $this->belongsTo(Operation::class, 'operation_id');
    }
}
