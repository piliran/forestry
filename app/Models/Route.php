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
        'type',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
