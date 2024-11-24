<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RouteType extends Model
{
    //
    use SoftDeletes;
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
