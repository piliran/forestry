<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //
    use HasFactory;

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
        return $this->belongsToMany(Permission::class, 'role_to_permissions');
    }

}
