<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Encroached extends Model
{
    protected $fillable = [
        'area_id',
        'latitude',
        'longitude',
      
    ];

    public function Area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

 
}
