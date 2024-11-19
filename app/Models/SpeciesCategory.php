<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpeciesCategory extends Model
{
    protected $fillable = [
        'name',
       
        'description',
   
    ];

    public function species()
    {
        return $this->hasMany(Species::class, 'id');
    }
}
