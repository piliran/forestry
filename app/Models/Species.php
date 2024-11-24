<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Species extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'unplanted_seedlings_count',
        'planted_seedlings_count',
        'unmatured_specie_count',
        'matured_specie_count',
        'description',
        'specie_cat_id',
    ];

   

    public function category()
    {
        return $this->belongsTo(SpeciesCategory::class, 'specie_cat_id');
    }
}
