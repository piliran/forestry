<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SpeciesCategory extends Model
{
    use SoftDeletes;


    protected $table = 'specie_categories';

    protected $fillable = [
        'name',       
        'description',   
    ];

    public function species()
    {
        return $this->hasMany(Species::class, 'id');
    }
}
