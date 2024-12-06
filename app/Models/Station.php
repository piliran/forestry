<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Station extends Model
{
    //
    use SoftDeletes;
    use HasFactory;

    // Define fillable fields for mass assignment
    protected $fillable = [
        'name',
        'location',
        'email',
        'contact_person',
        'district_id',
    ];

    public function area()
    {
        return $this->hasMany(Area::class);
    }


    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    /**
     * Get the operations associated with the station.
     */
    public function operations()
    {
        return $this->hasMany(Operation::class, 'station_id');
    }
}
