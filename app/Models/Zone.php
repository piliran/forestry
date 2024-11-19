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

<<<<<<< HEAD
    
=======
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
>>>>>>> 6a8be31028bcdd4ecb0a4c99a3208adfd7cbbcc1
}
