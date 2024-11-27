<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    //
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'station_id',
        'name',
        'code',
        'location',
        'coordinates',
        'latitude',
        'longitude',
        'chairperson',
    ];

    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    public function encroacheds()
    {
        return $this->hasMany(Encroached::class);
    }
    
}
