<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPrivilege extends Model
{
    protected $table = 'user_privileges';

    protected $fillable = [
        'user_id',
        'privilege_id',
    ];

    // Define relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function privilege()
    {
        return $this->belongsTo(Privilege::class);
    }
}
