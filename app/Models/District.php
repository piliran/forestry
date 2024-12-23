<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class District extends Model
{

    use HasFactory;
    use SoftDeletes;

    // Define the fillable fields
    protected $fillable = [
        'name',
        'location',
        'phone',
        'email',
        'contact_person',
        'country_id',
        'zone_id',
    ];

    public function zone()
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }


    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function suspect()
    {
        return $this->hasMany(Suspect::class);
    }

    public function contactPerson()
    {
        return $this->belongsTo(User::class, 'contact_person');
    }
    
    public function stations()
    {
        return $this->hasMany(Station::class, 'district_id');
    }
}
