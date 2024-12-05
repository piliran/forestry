<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Funder extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'organization',
        'phone',
        'address',
        'funded_by'
    ];

    public function operations()
    {
        return $this->hasMany(Operation::class, 'funder_id');
    }
}

