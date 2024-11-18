<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Confiscate extends Model
{
    //
    use HasFactory;

    // Define fillable fields
    protected $fillable = [
        'name',
        'national_id',
        'village',
        'TA',
        'district',
        'country',
        'coordinates',
        'proof',
    ];
}
