<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Route extends Model
{
    //
    use SoftDeletes;
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
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function RouteType()
    {
        return $this->belongsTo(RouteType::class, 'route_type_id');
    }
}
