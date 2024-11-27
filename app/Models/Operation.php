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
        'date_of_operation',
        'date_time_of_deployment',
        'date_time_of_withdrawal',
        'type_of_patrol',
        'funded_by',
        'route_id',
        'team_leader',
        'operation_type_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'date_of_operation' => 'date',
        'date_time_of_deployment' => 'datetime',
        'date_time_of_withdrawal' => 'datetime',
    ];

    // Relationship with Station
    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    // Relationship with Route
    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    // Relationship with Team Leader (User)
    public function teamLeader()
    {
        return $this->belongsTo(User::class, 'team_leader');
    }

    // Relationship with Operation Type
    public function operationType()
    {
        return $this->belongsTo(OperationType::class);
    }

}
