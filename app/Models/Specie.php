<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specie extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'name', 
        'specie_cat_id',
        'description',
        'matured_specie_count',
        'unmatured_specie_count',
        'planted_seedlings_count',
        'unplanted_seedlings_count',
    ];

    // Define the relationship with SpecieCategory
    public function category()
    {
        return $this->belongsTo(SpecieCategory::class, 'specie_cat_id');
    }
}
