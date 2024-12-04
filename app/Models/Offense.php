<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offense extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'penalty'
    ];
}
