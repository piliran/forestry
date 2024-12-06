<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    //
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'role_category_id'
    ];

    // Define the relationship with RoleCategory
    public function category()
    {
        return $this->belongsTo(RoleCategory::class, 'role_category_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_to_permission', 'role_id', 'permission_id');
    }

    public function privileges()
    {
        return $this->belongsToMany(Privilege::class, 'role_to_privileges', 'role_id', 'privilege_id');
    }


}
