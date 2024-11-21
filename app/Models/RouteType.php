<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteType extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function routes()
    {
        return $this->hasMany(Route::class, 'id');
    }
}
