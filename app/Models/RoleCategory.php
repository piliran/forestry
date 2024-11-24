<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoleCategory extends Model
{
    //
    use SoftDeletes;
    use HasFactory;

    protected $fillable = ['name'];

    public function roles()
    {
        return $this->hasMany(Role::class, 'id');
    }
}
