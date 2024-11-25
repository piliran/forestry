<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Confiscate extends Model
{
    //
    use SoftDeletes;
    use HasFactory;

    // Define fillable fields
    protected $fillable = [
        'item',
        'quantity',        
        'suspect_id',   
        'proof',
    ];



    public function District()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function Suspect()
    {
        return $this->belongsTo(Suspect::class, 'suspect_id');
    }

    public function Encroached()
    {
        return $this->belongsTo(Encroached::class, 'encroached_area_id');
    }

    public function getProofAttribute($value)
    {
        return $value ? Storage::url($value) : null;
    }
}
