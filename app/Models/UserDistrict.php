<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDistrict extends Model
{
    // Define the table name explicitly (optional if it matches convention)
    protected $table = 'user_districts';

    // Define fillable fields for mass assignment
    protected $fillable = [
        'user_id',
        'district_id',
    ];

    // Relationship to the User model
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship to the District model
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id');
    }
}
