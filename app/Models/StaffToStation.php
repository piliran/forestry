<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffToStation extends Model
{
    // use SoftDeletes;

  
    // Specify the table if it doesn't follow Laravel's naming convention
    protected $table = 'staff_to_stations';

    // Specify the primary key if it's not 'id'
    protected $primaryKey = 'id';

    // Allow mass assignment for specific columns
    protected $fillable = [
        'staff_id',
        'station_id',
    ];

    // Define the relationship with the Staff model
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'id');
    }

    // Define the relationship with the Station model
    public function station()
    {
        return $this->belongsTo(Station::class, 'station_id', 'id');
    }
}
