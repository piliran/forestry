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
    
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
