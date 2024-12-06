<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    //
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
    ];


    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_to_permission', 'permission_id', 'role_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_permissions');
    }


    public function tableToPermissions()
    {
        return $this->hasMany(TableToPermission::class);
    }


}
