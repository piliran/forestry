<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specie extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'specie_cat_id',
        'name',
        'description',
        'matured_specie_count',
        'unmatured_specie_count',
        'planted_seedlings_count',
        'unplanted_seedlings_count',
    ];

    /**
     * Define the relationship with the SpecieCategory model.
     */
    public function category()
    {
        return $this->belongsTo(SpecieCategory::class, 'specie_cat_id');
    }
}
