<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Funder extends Model
{
use Illuminate\Database\Eloquent\SoftDeletes;
use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'organization',
        'phone',
        'address',
    ];
}
