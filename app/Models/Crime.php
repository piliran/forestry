<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Crime extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'penalty'
    ];

}
