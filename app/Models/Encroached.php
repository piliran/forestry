<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Encroached extends Model
{
    use SoftDeletes;

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
