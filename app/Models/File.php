<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    //
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'suspect_to_confiscates_id',
        'file',
    ];

    /**
     * Define the relationship with the SuspectToConfiscate model.
     */
    public function suspectToConfiscate()
    {
        return $this->belongsTo(SuspectToConfiscate::class, 'suspect_to_confiscates_id');
    }
}
