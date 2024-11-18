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
        'district_id',
        'encroached_area_id',
        'suspect_id',
   
        'proof',
    ];



    public function District()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function Suspect()
    {
        return $this->belongsTo(Suspect::class, 'suspect_id');
    }

    public function Encroached()
    {
        return $this->belongsTo(Encroached::class, 'encroached_area_id');
    }
}
