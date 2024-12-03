<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use SoftDeletes;

    protected $table = 'staff';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'level_id',
        'user_id',
    ];

    /**
     * Relationship with the RoleCategory model.
     */
    public function level()
    {
        return $this->belongsTo(RoleCategory::class, 'level_id');
    }

    /**
     * Relationship with the User model.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship with the Station model.
     */
    public function station()
    {
        return $this->hasOneThrough(
            Station::class,
            StaffToStation::class,
            'staff_id', // Foreign key on StaffToStation
            'id',       // Foreign key on Station
            'id',       // Local key on Staff
            'station_id'// Local key on StaffToStation
        );
    }
}
