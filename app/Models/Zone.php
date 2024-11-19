<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    protected $fillable = [
        
        'name',
        'phone',
        'department_id',
        'location',
        'website',
    
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
