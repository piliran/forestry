<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Encroached extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'area_id',
        'encroachment_type',
        'estimated_area',
        'remarks',
        'latitude',
        'longitude',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    /**
     * Get the area that the encroachment belongs to.
     */
    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
 
}
