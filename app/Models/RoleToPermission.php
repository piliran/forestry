<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoleToPermission extends Model
{
    //
    use HasFactory;
    use SoftDeletes;

    protected $table = 'role_to_permission';

    protected $fillable = [
        'role_id',
        'permission_id',
    ];

    // Define relationships
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
