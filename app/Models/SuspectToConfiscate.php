<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuspectToConfiscate extends Model
{
    //
    use HasFactory;

    // Define the table name if it's not the default plural of the class name
    protected $table = 'suspect_to_confiscates';

    // Define the attributes that are mass assignable
    protected $fillable = [
        'suspect_id',
        'confiscate_id',
        'quantity',
        'reason'
    ];

    // Define the relationship with the Suspect model
    public function suspect()
    {
        return $this->belongsTo(Suspect::class, 'suspect_id');
    }

    // Define the relationship with the Confiscate model
    public function confiscate()
    {
        return $this->belongsTo(Confiscate::class, 'confiscate_id');
    }
}
