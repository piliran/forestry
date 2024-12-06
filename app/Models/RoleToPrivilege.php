<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleToPrivilege extends Model
{
    protected $table = 'role_to_privileges';

    protected $fillable = [
        'role_id',
        'privilege_id',
    ];

    // Define relationships
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function privilege()
    {
        return $this->belongsTo(Privilege::class);
    }
}
