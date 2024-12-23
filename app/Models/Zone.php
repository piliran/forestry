<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zone extends Model
{
    use SoftDeletes;

    protected $fillable = [

        'name',
        'phone',
        'department_id',
        'location',
        'website',
        'contact_person'

    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function contactPerson()
    {
        return $this->belongsTo(User::class, 'contact_person');
    }

    public function districts()
{
    return $this->hasMany(District::class, 'zone_id');
}


}
