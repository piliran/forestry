<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'station_id',
        'name',
        'code',
        'location',
        'coordinates',
        'chairperson',
    ];

    public function station()
    {
        return $this->belongsTo(Station::class);
    }
}
