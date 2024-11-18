<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'area_id',
        'name',
        'code',
        'location',
        'route_type_id',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class, 'zone_id');
    }

    public function RouteType()
    {
        return $this->belongsTo(RouteType::class, 'route_type_id');
    }
}
