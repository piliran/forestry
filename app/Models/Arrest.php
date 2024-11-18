<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arrest extends Model
{
    //
    use HasFactory;

    // Define fillable fields
    protected $fillable = [
        'description',
        'date',
        'location',
        'proof',
        'confiscate_id',
    ];

    // Define relationship with Confiscate
    public function confiscate()
    {
        return $this->belongsTo(Confiscate::class);
    }
}
