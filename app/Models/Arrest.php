<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Arrest extends Model
{
    //
    use SoftDeletes;
    use HasFactory;

    // Define fillable fields
    protected $fillable = [
        'description',
        'date',
        'location',
        'proof',
        // 'confiscate_id',
        'suspect_id',
        'crime_id'
    ];

    // Define relationship with Confiscate
    public function confiscate()
    {
        return $this->belongsTo(Confiscate::class);
    }

    public function suspect()
    {
        return $this->belongsTo(Suspect::class);
    }

    public function crime()
    {
        return $this->belongsTo(Crime::class);
    }
}
