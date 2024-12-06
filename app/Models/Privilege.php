<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Privilege extends Model
{
    use HasFactory;

    protected $fillable = ['table_to_permission_id', 'privilege'];

    // Relationship with TableToPermission model
    public function tableToPermission()
    {
        return $this->belongsTo(TableToPermission::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_to_privileges', 'privilege_id', 'role_id');
    }
}
