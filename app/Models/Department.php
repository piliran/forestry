<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{

    use SoftDeletes;

    protected $fillable = [
        
        'name',
        'phone',
        'location',
        'email',
        'website',
        'code',
        'contact_person',
    
    ];

    public function contactPerson()
    {
        return $this->belongsTo(User::class, 'contact_person');
    }


}
