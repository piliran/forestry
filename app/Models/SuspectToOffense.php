<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuspectToOffense extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'suspect_id',
        'offense_id'
    ];

    public function suspect()
    {
        return $this->belongsTo(Suspect::class);
    }

    public function offense()
    {
        return $this->belongsTo(Offense::class);
    }

}
