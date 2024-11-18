<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{

    use HasFactory;

    // Define the fillable fields
    protected $fillable = [
        'name',
        'location',
        'phone',
        'email',
        'chairperson',
    ];
}
