<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suspect extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'national_id',
      
        'district_id',
        'village',
        'TA',
        'suspect_photo_path',
    ];

   
    /**
     * Get the district associated with the suspect.
     */
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Mutator for the suspect's photo path.
     * Converts the photo path to a full URL if stored locally.
     */
    public function getSuspectPhotoPathAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }
}
