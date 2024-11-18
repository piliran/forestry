<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
        'name',
     
        'district_id',
        
    ];

    public function District()
    {
        return $this->belongsTo(District::class, 'district_id');
    }


}
